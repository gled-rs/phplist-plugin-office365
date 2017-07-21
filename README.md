# Send Grid Plugin #

## Description ##

This plugin sends emails through Office365 using their API.

## Installation ##

### Dependencies ###

This plugin is for phplist 3.3.0 or later and requires php version 5.4 or later.

It also requires the php curl extension to be installed.

### Set the plugin directory ###
The default plugin directory is `plugins` within the admin directory.

You can use a directory outside of the web root by changing the definition of `PLUGIN_ROOTDIR` in config.php.
The benefit of this is that plugins will not be affected when you upgrade phplist.

### Install through phplist ###
The recommended way to install is through the Plugins page (menu Config > Manage Plugins) using the package
URL `https://github.com/gled-rs/phplist-plugin-office365/archive/master.zip`.
The installation should create

* the file Office365Plugin.php
* the directory Office365Plugin

### Install manually ###
If the automatic installation does not work then you can install manually.
Download the plugin zip file from <https://github.com/gled-rs/phplist-plugin-office365/archive/master.zip>

Expand the zip file, then copy the contents of the plugins directory to your phplist plugins directory.
This should contain

* the file Office365Plugin.php
* the directory Office365Plugin

##Usage##

For guidance on using the plugin see the plugin's page within the phplist documentation site <https://resources.phplist.com/plugin/Office365>

## Support ##

Please raise any questions or problems in the user forum <https://discuss.phplist.org/>.

