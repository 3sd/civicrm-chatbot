# CiviCRM Chatbot

A chatbot for CiviCRM. Integrates various chat services (e.g. Facebook messenger) so you can chat with your contacts.

## Requirements

* CiviCRM 5.x
* PHP 7.0

## Installation

This extension is ***currently in beta***. You are welcome to try it out but be prepared for a couple of bumps along the way. Please report any issues you have using the [github issue tracker](https://github.com/3sd/civicrm-chatbot/issues).

**Note:** this repository also contains [chatbot-civirules](chatbot-civirules) an extension that provides integration between Chatbot with CiviRules. Please ensure that you have CiviRules installed before enabling this extension.

## Documentation

[Read our WIP documentation here](docs).

## Developers

Check out the [chatbot-demo](chatbot-demo) directory for a demonstration environment built using buildkit.

### Tests

Tests can be run from the extension directory with:

`CIVICRM_UF=UnitTests phpunit4 tests/phpunit`

## Credits

Development of the CiviCRM Chatbot extension has been generously funded by the [National Democratic Institute](https://ndi.org).

It is maintained by [Michael McAndrew](https://twitter.com/michaelmcandrew) from [Third Sector Design](https://thirdsectordesign.org/) who you can [contact](https://thirdsectordesign.org/contact) for help, support and further development.

It builds on [Botman.io](https://botman.io/), the excellent PHP chatbot framework by [Marcel Pociot](https://twitter.com/marcelpociot).

## Contributing

Contributions large and small are very welcome to this repository. For small changes, feel free to submit a pull request. For larger changes, its better to create an issue first so we can talk things through before you start.

## License

This extension is licensed under [AGPL-3.0](LICENSE.txt).
