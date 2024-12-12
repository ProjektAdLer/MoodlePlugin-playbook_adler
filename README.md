# Adler Playbook

This playbook configures a moodle instance with AdLer.

The following roles are available:
- test_users: Creates test users
- moodle_dev_env: For moodle development (don't install plugins)
- integration_test: For integration and end-to-end tests (activate mobile services)

The following environment variables are used:
- DECLARATIVE_SETUP_MANAGER_PASSWORD: Password for the manager user
- DECLARATIVE_SETUP_STUDENT_PASSWORD: Password for the student user