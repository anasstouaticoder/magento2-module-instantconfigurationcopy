# Magento 2 Module AnassTouatiCoder InstantConfigurationCopy

    ``anasstouaticoder/magento2-module-instantconfigurationcopy``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [usage](#markdown-header-usage)
 - [License](#markdown-header-License)


## Main Functionalities
Provide easy way to copy fields path and value in configuration section

## Installation
\* = in production please use the `--keep-generated` option

### install from composer 2

 - In magento project root directory run command `composer require anasstouaticoder/magento2-module-instantconfigurationcopy`
 - Enable the module by running `php bin/magento module:enable AnassTouatiCoder_InstantConfigurationCopy`
 - Flush the cache by running `php bin/magento cache:flush`


### Zip file

 - Unzip the zip file in `app/code/AnassTouatiCoder`
 - Enable the module by running `php bin/magento module:enable AnassTouatiCoder_InstantConfigurationCopy`
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

### Type 1 : In Back Office
After installing the plugin, it is easy to activate it, login in as administrator, then navigate 
to Stores => Configuration => Advanced => Developer => Debug => Enable System Config Path Hints For Admin or
Enable System Config copy value For Admin, finally select yes and save.
now under each configuration field you see that there is path : configuration path, copy  path button and copy value button

### Type 2 : Using Magento CLI

Enabling display field paths and copy path button : `bin/magento config:set dev/debug/system_path_hint 1`

Enabling copy value button: `bin/magento config:set dev/debug/system_field_value 1`

Enabling display override value of sub scopes : `bin/magento config:set dev/debug/system_override_values 1`

## Specifications

This plugin provides easy way to copy configuration field paths or its value to the clipboard. It also displays all values configured in sub scopes websites or in a modal.

For copying value and displaying sub values in modal parts, both support these field types : [text, textarea, select, and multiselect]()

## Usage

The idea behind this plugin is to make admins and developers lives much easier, instead of them searching inside the page DOM using inspect tool or in Project files, the plugin is providing them a shortcut to  path configuration and copy button to clipboard.
[See plugin wiki](https://github.com/anasstouaticoder/magento2-module-instantconfigurationcopy/wiki/Project-Demo)

## License

[MIT](https://opensource.org/licenses/MIT)
