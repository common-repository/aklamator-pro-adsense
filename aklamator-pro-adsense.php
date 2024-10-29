<?php
/*
Plugin Name: Aklamator Pro Adsense
Plugin URI: https://www.aklamator.com/wordpress
Description: Aklamator Pro AdSense digital PR plugin enables you to easily place AdSense or other custom Ad code on your wordpress site. It also enables you to sell PR announcements, cross promote web sites using RSS feed and provide new services to your clients in digital advertising.
Version: 2.1.1
Author: Aklamator
Author URI: https://www.aklamator.com/
License: GPL2

Copyright 2015 Aklamator.com (email : info@aklamator.com)

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

if(!defined('AKLA_PRO_PLUGIN_NAME')){
    define('AKLA_PRO_PLUGIN_NAME', plugin_basename(__FILE__));
}

if (!defined('AKLA_PRO_PLUGIN_DIR')) {
    define('AKLA_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('AKLA_PRO_PLUGIN_URL')) {
    define('AKLA_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Include Admin section
require_once AKLA_PRO_PLUGIN_DIR ."includes/class-aklamator-pro-adsense.php";

/*
 * Activation Hook
 */
register_activation_hook( __FILE__, array( 'AklamatorWidgetPro','set_up_optionsPro' ));

/*
 * Uninstall Hook
 */
register_uninstall_hook(__FILE__, array( 'AklamatorWidgetPro','aklamatorPro_uninstall' ));


// Widget section

require_once AKLA_PRO_PLUGIN_DIR. "includes/class-aklamator-pro-widget-adsense.php";

AklamatorWidgetPro::init();