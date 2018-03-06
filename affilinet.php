<?php

/*
Plugin Name: affilinet Performance Ads
Description: Integrate our data driven and automated performance display plugin into your WordPress platform and serve your users targeted ads in real time.
Version: 1.9.1
Author: affilinet
Author URI: https://www.affili.net/de/publisher/tools/performance-ads
Text Domain: affilinet-performance-module
License: GPLv2 or later
*/

define("AFFILINET_PLUGIN_DIR", dirname(__FILE__).DIRECTORY_SEPARATOR);
define("AFFILINET_PLUGIN_FILE", plugin_basename( __FILE__) );

foreach (glob(AFFILINET_PLUGIN_DIR . "classes/*.php") as $filename) {
    include $filename;
}

new Affilinet_Plugin();
