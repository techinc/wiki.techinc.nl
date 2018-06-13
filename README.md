# TechInc wiki

>***NOTE:*** THIS IS AN INITIAL VERSION, DO NOT USE FOR PRODUCTION YET!

### Git repository to manage the codebase for the [TechInc wiki](https://wiki.techinc.nl)

This repo uses **git flow**.  If you don't know how it works, you can look [here](http://nvie.com/posts/a-successful-git-branching-model/) and [here](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow)

### some rules:

>1. the development branch is your working branch.
>2. A release/$number branch is for staging
>3. master branch **with** a tag is to be used on production

***TODO:*** add more rules

## Requirements

In order to run this setup, you need at least the following:
- Centos 7, or Debian Stretch (virtual) machine
- MariaDB 10.3 database server
- PHP 7.1 (production) or 7.2
- A webserver (preferably Nginx)
- Redis for backend caching
- Varnish for frontend-caching
- A frontend webserver for ssl termination

Setting this up is beyond the scope of this repository, but take a look (*soon*) at our Ansible deployment scripts for the TechInc webserver.

## For production deployment

Clone the git repository:
```bash
git clone https://github.com/techinc/wiki.techinc.nl
```
Enter the directory and checkout the latest tagged version:
```bash
git checkout $revision
```
Initialize the submodules:
```bash
git submodule update --init --recursive
```

***TODO:***
>- permissions
>- nginx configurations
>- files/ directory
>- maintenance scripts (sequence etc...)
>- some more?.....

### SELinux

To run Mediawiki in a RHEL / Centos environment with SELinux enforcing, you need to add the following rules (change `/var/www/wiki.techinc.nl/webroot` to the location on your system):

```bash
semanage fcontext -a -t httpd_sys_content_t '/var/www/wiki.techinc.nl/webroot(/.*)?'
semanage fcontext -a -t httpd_sys_script_exec_t '/var/www/wiki.techinc.nl/webroot/.*\.php?'
semanage fcontext -a -t httpd_sys_script_exec_t '/var/www/wiki.techinc.nl/webroot/includes/.*\.php?'
semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/wiki.techinc.nl/webroot/images(/.*)?'
semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/wiki.techinc.nl/webroot/cache(/.*)?'
semanage restorecon -Rv /var/www
setsebool -P httpd_can_network_connect=1
setsebool -P httpd_setrlimit=1
setsebool -P httpd_can_sendmail=1 #if you want your Mediawiki instance to send mail
```

***TODO:***
> Add stuff that's still missing

## For staging

This is still "Work in Progess"




## For development

> Note: You will need to install composer on your development environment

To start with a fresh installation, first switch to the development branch:
```bash
git checkout develop
```
Erase the contents of `webroot/`
```bash
rm -rf webroot/*
```
Next, download the Mediawiki code:
```bash
curl https://releases.wikimedia.org/mediawiki/1.30/mediawiki-1.30.0.tar.gz | tar -xz -C webroot --strip-components=1
```
copy `files/composer.local.json` to `webroot/`

run: `composer update --no-dev`

From the base directory, add the needed submodules:
```bash
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/AdminLinks webroot/extensions/AdminLinks
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/Arrays webroot/extensions/Arrays
git submodule add -b REL1_30 https://github.com/wikimedia/mediawiki-extensions-MsUpload webroot/extensions/MsUpload
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/MyVariables webroot/extensions/MyVariables
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/PageImages webroot/extensions/PageImages
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/Popups webroot/extensions/Popups
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/SemanticDrilldown.git webroot/extensions/SemanticDrilldown
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/SemanticInternalObjects webroot/extensions/SemanticInternalObjects
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/TextExtracts webroot/extensions/TextExtracts
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/UserMerge webroot/extensions/UserMerge
git submodule add -b REL1_30 https://gerrit.wikimedia.org/r/p/mediawiki/extensions/Widgets webroot/extensions/Widgets
git submodule add https://github.com/mediawiki4intranet/RegexParserFunctions webroot/extensions/RegexParserFunctions
git submodule add https://github.com/jornane/mwSimpleSamlAuth.git webroot/extensions/SimpleSamlAuth

# Initialize recursive submodules
git submodule update --init --recursive
```

### prevent leaking of version numbers in Special:Version:
In `webroot/includes/spcecials/SpecialVersion.php`, comment out line #139 (in REL1_30)
```php
  //$this->softwareInformation();
```

