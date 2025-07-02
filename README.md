# cividocsbuilder

<em>cividocsbuilder</em> builds the CiviCRM documentation website.

The main source are these books:

* User Guide
* Sysadmin Guide
* Installation Guide
* Developer Documentation

[See Gitlab](https://lab.civicrm.org/documentation/docs).

cividocsbuilder will take the latest version of these books and turn them into an MkDocs site.

## Installation

Clone this repo into the directory where you host your website(s). e.g. /var/www/vhosts or /home/yourname/public_html.

Go into that new directory and execute buildcividocs.php:

```
cd cividocsbuilder
php buildcividocs.php
```

This will generate the CiviCRM documentation site into the directory cividocsbuilder/output/site.

Configure Apache or Nginx to take this directory as the root of the documentation site.

## Periodic Updates

To reflect the changes in the guides on https://lab.civicrm.org/documentation/docs, you should periodically rebuild the documentation site.

You can configure cron to execute the build script e.g. every hour:

```
0 * * * * /usr/bin/php /var/www/vhosts/cividocsbuilder/buildcividocs.php
```
