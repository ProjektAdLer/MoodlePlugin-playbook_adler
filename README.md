# Adler Playbook

This playbook configures a moodle instance with AdLer.

The following roles are available:
- test_users: Creates test users for manual use
- moodle_dev_env: For moodle development (don't install plugins)
- integration_test: For integration and end-to-end tests (activate mobile services, create test users for automated tests)

The following environment variables are used:
- DECLARATIVE_SETUP_MANAGER_PASSWORD: Password for the manager user (role: test_users)
- DECLARATIVE_SETUP_STUDENT_PASSWORD: Password for the student user (role: test_users)
- DECLERATIVE_SETUP_INTEGRATION_TEST_MANAGER_PASSWORD: Password for the integration test manager user (role: integration_test)
- DECLERATIVE_SETUP_INTEGRATION_TEST_STUDENT_PASSWORD: Password for the integration test student user (role: integration_test)

## files/plugins.json
This file is required if `moodle_dev_env` role is not used. Get the current version from
[ProjektAdLer/MoodleAdlerLMS/files/plugin.json](https://github.com/ProjektAdLer/MoodleAdlerLMS/blob/main/plugins.json).
See plugins.json.sample for an example.

If this plugin is used in the [AdLer LMS Moodle image](https://github.com/ProjektAdLer/MoodleAdlerLMS), the plugin.json
file is automatically copied to the `files` directory.

## Kompabilität
Folgende Versionen werden unterstützt (mit mariadb und postresql getestet):

siehe [plugin_compatibility.json](plugin_compatibility.json)
