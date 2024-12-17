# Adler Playbook

This playbook configures a moodle instance with AdLer.

The following roles are available:
- test_users: Creates test users
- moodle_dev_env: For moodle development (don't install plugins)
- integration_test: For integration and end-to-end tests (activate mobile services)

The following environment variables are used:
- DECLARATIVE_SETUP_MANAGER_PASSWORD: Password for the manager user
- DECLARATIVE_SETUP_STUDENT_PASSWORD: Password for the student user

## files/plugins.json
This file is required if `moodle_dev_env` role is not used. Get the current version from
[ProjektAdLer/MoodleAdlerLMS/files/plugin.json](https://github.com/ProjektAdLer/MoodleAdlerLMS/blob/main/plugins.json).
See plugins.json.sample for an example.

If this plugin is used in the [AdLer LMS Moodle image](https://github.com/ProjektAdLer/MoodleAdlerLMS), the plugin.json
file is automatically copied to the `files` directory.

## Kompabilität
Folgende Versionen werden unterstützt (mit mariadb und postresql getestet):

siehe [plugin_compatibility.json](plugin_compatibility.json)
