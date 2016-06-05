<?php

/*
Plugin Name: Affilinet Performance Ads
Description: Integrate our data driven and automated performance display plugin into your WordPress platform and serve your users targeted ads in real time.
Version: 1.8.1
Author: Affilinet
Author URI: https://www.affili.net/de/publisher/tools/performance-ads
License: GPLv2 or later
*/

// @TODO: does not work  with symlink

define("AFFILINET_PLUGIN_DIR", dirname(__FILE__).DIRECTORY_SEPARATOR);

foreach (glob(AFFILINET_PLUGIN_DIR . "classes/*.php") as $filename) {
    include $filename;
}

new Affilinet_Plugin();
