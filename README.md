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
```

You might want to create a Python virtual environment:

```
python -m venv venv
```

Activate the virtual environment:

```
source venv/bin/activate
```

Make sure the Material theme is installed and the plugins:

```
python -m pip install mkdocs-material
pip install mkdocs-breadcrumbs-plugin
pip install pip install mkdocs-categories-plugin
```

Then build the documentation site:

```
./buildcividocs.php
```

This will generate the CiviCRM documentation site into the directory cividocsbuilder/output/site.

Configure Apache or Nginx to take this directory as the root of the documentation site.

Or on your local machine:

```
cd output
mkdocs serve
```

## Periodic Updates

To reflect the changes in the guides on https://lab.civicrm.org/documentation/docs, you should periodically rebuild the documentation site.

You can configure cron to execute the build script e.g. every hour:

```
0 * * * * /usr/bin/php /var/www/vhosts/cividocsbuilder/buildcividocs.php
```

## Future Technology?

Use Zeniscal instead of MKDocs now that the latter is not updated anymore?

See https://squidfunk.github.io/mkdocs-material/blog/2025/11/05/zensical/