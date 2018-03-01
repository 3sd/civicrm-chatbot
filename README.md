# CiviCRM Chatbot

A chatbot for CiviCRM. Integrates various chat services with CiviCRM so you can chat with your contacts.

## Requirements

* CiviCRM 4.7
* PHP 7.0

## Installation

This extension is currently under development and not yet ready for production use. Developers can try it out by following the instructions below.
## License

This extension is licensed under [AGPL-3.0](LICENSE.txt).

## Developers

Build a development / demonstration environment site using the build scripts in the build directory.

### Tests

Tests can be run from the extension directory with:

`CIVICRM_UF=UnitTests phpunit4 tests/phpunit`

Note: following the buildkit build is the easiest way to ensure that you have all the dependencies in place, and the site set up correctly.

## Setting up Facebook chat



A Facebook app is what links your Facebook Page to your webhook, and is where you will configure various settings for your Messenger bot, generate access tokens, and choose what events are sent to your webhook.
