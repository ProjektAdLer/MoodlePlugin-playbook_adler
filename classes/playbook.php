<?php

namespace playbook_adler;

use Exception;
use invalid_parameter_exception;
use local_declarativesetup\local\base_playbook;
use local_declarativesetup\local\play\config\config;
use local_declarativesetup\local\play\config\models\config_model;
use local_declarativesetup\local\play\course_category\course_category;
use local_declarativesetup\local\play\course_category\models\course_category_model;
use local_declarativesetup\local\play\course_category\models\role_user_model;
use local_declarativesetup\local\play\exceptions\not_implemented_exception;
use local_declarativesetup\local\play\exceptions\play_was_already_played_exception;
use local_declarativesetup\local\play\exceptions\play_was_not_played_exception;
use local_declarativesetup\local\play\install_plugins\install_plugins;
use local_declarativesetup\local\play\install_plugins\models\install_plugins_model;
use local_declarativesetup\local\play\language\language;
use local_declarativesetup\local\play\language\models\language_model;
use local_declarativesetup\local\play\logos\logos;
use local_declarativesetup\local\play\logos\models\logo_model;
use local_declarativesetup\local\play\role\models\role_model;
use local_declarativesetup\local\play\role\role;
use local_declarativesetup\local\play\user\models\user_model;
use local_declarativesetup\local\play\user\user;
use local_declarativesetup\local\play\web_services\models\web_services_model;
use local_declarativesetup\local\play\web_services\web_services;
use moodle_exception;

class playbook extends base_playbook {
    /**
     * @throws play_was_not_played_exception
     * @throws not_implemented_exception
     * @throws play_was_already_played_exception
     * @throws invalid_parameter_exception
     * @throws moodle_exception
     */
    protected function playbook_implementation(): void {
        // first ensure maintenance mode is active. A playbook can take some time and users should not use
        // the system while it is being configured.
        $play = new config([
            new config_model('maintenance_enabled', 1),
            new config_model('maintenance_message', 'This site is currently under maintenance. Please try again later.'),
        ]);
        $play->play();

        // Install plugins
        if (!$this->has_role('moodle_dev_env')) {
            $play = new install_plugins($this->load_plugins_to_install());
            $play->play();
        }

        // Install german locale
        $play = new language([
            new language_model('de'),
            new language_model('en'),
        ]);
        $play->play();

        // Create adler_manager role
        $play = new role(new role_model(
            'adler_manager',
            [
                'moodle/course:delete' => CAP_ALLOW,
                'moodle/course:enrolconfig' => CAP_ALLOW,
                'moodle/question:add' => CAP_ALLOW,
                'moodle/question:managecategory' => CAP_ALLOW,
                'moodle/restore:configure' => CAP_ALLOW,
                'moodle/restore:restoreactivity' => CAP_ALLOW,
                'moodle/restore:restorecourse' => CAP_ALLOW,
                'moodle/restore:restoresection' => CAP_ALLOW,
                'moodle/restore:restoretargetimport' => CAP_ALLOW,
                'moodle/restore:rolldates' => CAP_ALLOW,
                'moodle/restore:uploadfile' => CAP_ALLOW,
                'moodle/restore:userinfo' => CAP_ALLOW,
                'moodle/restore:viewautomatedfilearea' => CAP_ALLOW,
                'moodle/h5p:deploy' => CAP_ALLOW,
            ],
            [CONTEXT_COURSECAT],
        ));
        $play->play();

        // enable web services
        if ($this->has_role('integration_test')) {
            $play = new web_services(new web_services_model(
                web_services_model::STATE_ENABLED,
                ['rest'],
                enable_moodle_mobile_service: web_services_model::STATE_ENABLED
            ));
        } else {
            $play = new web_services(new web_services_model(
                web_services_model::STATE_ENABLED,
                ['rest']
            ));
        }
        $play->play();

        $play = new role(new role_model(
            'user',
            [
                'moodle/webservice:createtoken' => CAP_ALLOW,
                'webservice/rest:use' => CAP_ALLOW,
            ],
            null,
            false
        ));
        $play->play();

        $play = new logos(new logo_model(
            __DIR__ . '/../files/logos/22-04-20_adler_logo_3d_long.png',
            __DIR__ . '/../files/logos/AdLer_Logo.png',
            __DIR__ . '/../files/logos/AdLer_Logo_favicon.png',
        ));
        $play->play();

        if ($this->has_role('test_users')) {
            // Create test users
            $play = new user(new user_model(
                'manager',
                $this->get_environment_variable('MANAGER_PASSWORD'),
            ));
            $play->play();
            $play = new user(new user_model(
                'student',
                $this->get_environment_variable('STUDENT_PASSWORD'),
            ));
            $play->play();

            // create adler course category for test users
            $play = new course_category(new course_category_model(
                '/adler/manager',
                users: [
                    new role_user_model('manager', ['adler_manager']),
                    new role_user_model('student', []),
                ],
            ));
            $play->play();
        }

        // Now disable maintenance mode again.
        $play = new config([
            new config_model('maintenance_enabled', 0),
        ]);
        $play->play();
    }

    /**
     * @throws moodle_exception
     * @returns install_plugins_model[]
     */
    private function load_plugins_to_install(): array {
        $filePath = __DIR__ . '/../files/plugins.json';
        $jsonContent = file_get_contents($filePath);
        if ($jsonContent === false) {
            throw new moodle_exception('Failed to read files/plugins.json. Does it exist?');
        }
        $pluginsArray = json_decode($jsonContent, true);

        return array_map(function ($plugin) {
            return new install_plugins_model(
                $plugin['version'],
                $plugin['name'],
                null,
            );
        }, $pluginsArray);
    }

    protected function failed(Exception $e): void {
        $play = new config([
            new config_model('maintenance_enabled', 1),
            new config_model('maintenance_message', 'This site is currently under maintenance. Please try again later.'),
        ]);
        $play->play();
    }
}
