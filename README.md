# Magento 2 Unicode URL 
Magento 2 module to save UTF-8 characheters  allowing categories and products UTF-8 URL keys

- Support Hebew language text in URL key
- Support Non-Latin language text in URL key

## Installation
Install this module within Magento 2 using composer:

```
composer require landofcoder/module-magento2-unicode-url
```

After this, enable the module as usual:

```
bin/magento module:enable Lof_UnicodeUrl
bin/magento setup:upgrade
bin/magento cache:clean
```
