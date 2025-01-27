# Adler Playbook

This playbook configures a moodle instance with AdLer.

The following roles are available:
- *test_users*: Creates test users for manual use
  - usernames: manager, student
  - environment variables: 
    - DECLARATIVE_SETUP_MANAGER_PASSWORD: Password for the manager user
    - DECLARATIVE_SETUP_STUDENT_PASSWORD: Password for the student user
- *moodle_dev_env*: For moodle development (don't install plugins)
- *integration_test*: For integration and end-to-end tests 
  - activate mobile services
  - create test users for automated tests (integration_test_manager, integration_test_student)
  - environment variables:
    - DECLERATIVE_SETUP_INTEGRATION_TEST_MANAGER_PASSWORD: Password for the integration test manager user
    - DECLERATIVE_SETUP_INTEGRATION_TEST_STUDENT_PASSWORD: Password for the integration test student user

## files/plugins.json
This file is required if `moodle_dev_env` role is not used. Get the current version from
[ProjektAdLer/MoodleAdlerLMS/files/plugin.json](https://github.com/ProjektAdLer/MoodleAdlerLMS/blob/main/plugins.json).
See plugins.json.sample for an example.

If this plugin is used in the [AdLer LMS Moodle image](https://github.com/ProjektAdLer/MoodleAdlerLMS), the plugin.json
file is automatically copied to the `files` directory.

## Kompabilität
Folgende Versionen werden unterstützt (mit mariadb und postresql getestet):

siehe [plugin_compatibility.json](plugin_compatibility.json)
