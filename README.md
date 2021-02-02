# Custom module to extend Algolia Magento 2 module and send custom collection to Algolia Dashboard

[Magento 2](https://magento.com/) module for easy extension of [Algolia's Magento 2 module](https://github.com/algolia/algoliasearch-magento-2).

## Guides

* [Backend custom events](https://community.algolia.com/magento/doc/m2/backend/)
* [Frontend custom events](https://community.algolia.com/magento/doc/m2/frontend-events/)

## Installation
First need to install the Algoliasearch extension Follow guidelines with this reference link - (https://github.com/algolia/algoliasearch-magento-2)
The template module can be installed via [Composer](https://getcomposer.org/):

```sh
$ cd /path/to/your/magento2/directory
$ composer require parulthakkar-pkg/algoliastorelocator-magento2
$ php bin/magento setup:upgrade
```
#### The Algolia PHP API Client
The extension does most of the heavy lifting when it comes to gathering and preparing the data needed for indexing to Algolia. In terms of interacting with the Algolia Search API, the extension leverages the PHP API Client for backend methods including indexing, configuration, and search queries.

Depending on the extension version you are using, you could have a different PHP API client version powering the extension's backend functionality.

| Extension Version | API Client Version |
| --- | --- |
| v1.x | [1.28.0](https://github.com/algolia/algoliasearch-client-php/tree/1.28.0) |
| v2.x | [2.5.1](https://github.com/algolia/algoliasearch-client-php/tree/2.5.1) |
| v3.x | [2.5.1](https://github.com/algolia/algoliasearch-client-php/tree/2.5.1) |

Refer to these docs when customising your Algolia Magento extension backend:
- [Indexing](https://www.algolia.com/doc/integration/magento-2/how-it-works/indexing/)
- [Dispatched Backend Events](https://www.algolia.com/doc/integration/magento-2/customize/custom-back-end-events/)


Need Help?
------------
Here are some helpful documentation to help with your issue:

- [General FAQs](https://www.algolia.com/doc/integration/magento-2/troubleshooting/general-faq/)
- [Technical Troubleshooting Guide](https://www.algolia.com/doc/integration/magento-2/troubleshooting/technical-troubleshooting/)
- [Indexing Queue](https://www.algolia.com/doc/integration/magento-2/how-it-works/indexing-queue/)
- [Frontend Custom Events](https://www.algolia.com/doc/integration/magento-2/customize/custom-front-end-events/)
- [Dispatched Backend Events](https://www.algolia.com/doc/integration/magento-2/customize/custom-back-end-events/)

For feedback, bug reporting, or unresolved issues with the extension, please contact us at [support@algolia.com](mailto:support@algolia.com). Please include your Magento version, extension version, application ID, and steps to reproducing your issue. Add additional information like screenshots, screencasts, and error messages to help our team better troubleshoot your issues.

