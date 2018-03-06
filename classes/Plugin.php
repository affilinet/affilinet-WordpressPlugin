<?php

class Affilinet_Plugin
{

    public function __construct()
    {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('widgets_init', array($this, 'register_widget'));

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_head', array($this, 'enqueue_bidding_script'));

        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_shortcode('affilinet_performance_ad', array($this, 'performance_ad_shortcode'));

        add_action( 'admin_notices', array( $this, 'admin_notice' ));

	    add_filter( 'plugin_action_links_' .plugin_basename(AFFILINET_PLUGIN_FILE ), array( $this, 'plugin_add_settings_link' ) );

	    add_action( 'upgrader_process_complete', array($this, 'plugin_upgraded'));
    }

    function admin_notice() {
        if (get_option('affilinet_webservice_login_is_correct') === 'false') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e('<strong>affilinet Performance Ads:</strong><br> Please make sure you have entered the correct PublisherID and Webservice password.', 'affilinet-performance-module' ); ?>
                <a class="button" href="admin.php?page=affilinet_settings"><?php _e('Check your settings.', 'affilinet-performance-module');?></a>
                </p>
            </div>
            <?php
        }
    }



    /**
     * Register Settings for admin area
     */
    public function admin_init()
    {
        register_setting('affilinet-settings-group', 'affilinet_platform');
        register_setting('affilinet-settings-group', 'affilinet_publisher_id');
        register_setting('affilinet-settings-group', 'affilinet_standard_webservice_password');
        register_setting('affilinet-settings-group', 'affilinet_product_data_webservice_password');

        register_setting('affilinet-settings-group', 'affilinet_webservice_login_is_correct');

        register_setting('affilinet-settings-group', 'affilinet_text_monetization');
        register_setting('affilinet-settings-group', 'affilinet_link_replacement');
        register_setting('affilinet-settings-group', 'affilinet_text_widget');

        register_setting('affilinet-settings-group', 'affilinet_extended_settings');
        register_setting('affilinet-settings-group', 'affilinet_ywidgetpos');
        register_setting('affilinet-settings-group', 'affilinet_ywdensity');
        register_setting('affilinet-settings-group', 'affilinet_ywcap');
        register_setting('affilinet-settings-group', 'affilinet_ywcolor');

    }


    /**
     * Create the admin Menu
     */
    public function admin_menu()
    {
        // create top level menu
        add_menu_page('affilinet', 'affilinet', 'manage_options', 'affilinet', 'Affilinet_View::start', plugin_dir_url(dirname(__FILE__)).'images/affilinet_icon.png');

        // submenu items
        add_submenu_page('affilinet', __('Start', 'affilinet-performance-module'), __('Start', 'affilinet-performance-module'), 'manage_options', 'affilinet', 'Affilinet_View::start');
        add_submenu_page('affilinet', __('Settings', 'affilinet-performance-module'), __('Settings', 'affilinet-performance-module'), 'manage_options', 'affilinet_settings', 'Affilinet_View::settings');


        if (get_option('affilinet_webservice_login_is_correct', 'false') === 'false') {
            add_submenu_page('affilinet', __('Signup', 'affilinet-performance-module'), __('Signup', 'affilinet-performance-module'), 'manage_options', 'affilinet_signup', 'Affilinet_View::signup');
        }

        add_submenu_page('affilinet', __('Reporting', 'affilinet-performance-module'), __('Reporting', 'affilinet-performance-module'), 'manage_options', 'affilinet_reporting', 'Affilinet_View::reporting');

        // options menu
        add_options_page('affilinet Settings', 'affilinet Perf. Ads', 'manage_options', 'affilinet_options', 'Affilinet_View::settings');
    }



	public function plugin_add_settings_link( $links ) {

		$settings_link = '<a href="' . admin_url( 'options-general.php?page=affilinet_options' ) . '">' . __( 'Settings', 'affilinet-performance-module' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

    /**
     * Register the widget
     */
    public function register_widget()
    {
        register_widget('Affilinet_Widget');
    }

    /**
     * Load Admin scripts
     * @param $hook string
     */
    public function admin_enqueue_scripts($hook)
    {
        // on post page add the editor button for affilinet plugin
        if ($hook === 'post.php' || $hook == 'post-new.php') {
            add_action('admin_head', array($this, 'editor_add_buttons'));
            add_action( "admin_head-$hook",array($this, 'affilinet_adminScript') );
        }

        // on reporting page add jquery.flot
        if ($hook === 'affilinet_page_affilinet_reporting') {
            wp_register_script('flot',      plugin_dir_url( plugin_basename( dirname(__FILE__) )  ).'js/jquery-flot/jquery.flot.js', array('jquery'));
            wp_register_script('flot.time', plugin_dir_url( plugin_basename( dirname(__FILE__) )  ).'js/jquery-flot/jquery.flot.time.js', array('jquery', 'flot'));
            wp_enqueue_script('flot');
            wp_enqueue_script('flot.time');
        }
    }

    public function enqueue_bidding_script()
    {

	    switch ($platformId = get_option('affilinet_platform')) {

		    case 7: // AT
			    $country = 'at';
			    break;
		    case 6: // CH
			    $country = 'ch';
			    break;
		    case 1: // DE
			    $country = 'de';
			    break;
		    case 3: // FR
			    $country = 'fr';
			    break;
		    case 2: // UK (prebidding not implemented)
		    case 4: // NL (prebidding not implemented)
		    default :
			    return;
	    }

	    $publisherId = get_option('affilinet_publisher_id');
	    if ($publisherId == null) return;


	    echo '<!-- affilinet prebidding script --><script language="javascript" type="text/javascript">'
             . 'var affnetpbjsConfig = { "' . $country . '": { "publisherId" : "' . $publisherId . '" }};</script>';
	    $link = array(
		    'de' => 'https://html-links.com/banners/9192/js/affnetpbjs_de.min.js',
		    'at' => 'https://html-links.com/banners/12376/js/affnetpbjs_at.min.js',
		    'ch' => 'https://html-links.com/banners/12252/js/affnetpbjs_ch.min.js',
		    'fr' => 'https://html-links.com/banners/12751/js/affnetpbjs_fr.min.js',
	    );

	    echo '<script src="' . $link[ $country ] . '"></script>';
    }

    /**
     * Shortcode
     */
    public function performance_ad_shortcode($params = array())
    {
        // default size parameter
        /**
         * @var String $size
         */
        extract(shortcode_atts(array(
            'size' => '728x90',
        ), $params));

        return Affilinet_PerformanceAds::getAdCode($size);
    }

    /**
     * TRANSLATION
     */
    public function load_textdomain()
    {
        load_plugin_textdomain( 'affilinet-performance-module', false, dirname(dirname( plugin_basename( __FILE__ ) )) . '/languages' );
    }

    /**
     * TinyMCE Editor Button
     */
    public function editor_add_buttons()
    {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        // check if WYSIWYG is enabled
        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array($this, 'add_buttons'));
            add_filter('mce_buttons', array($this, 'register_buttons'));
        }
    }

    /**
     * Load TinyMCE Variables
     */
    public function affilinet_adminScript()
    {
        $img = plugin_dir_url( plugin_basename( dirname(__FILE__) )  ). 'images/';
        ?>
        <!-- TinyMCE Shortcode Plugin -->
        <script type='text/javascript'>
            var affilinet_mce_variables = {
                'image_path': '<?php echo $img; ?>',
                'choose_size': 'Choose size',
                'ad_sizes' : <?php echo Affilinet_Widget::getAllowedSizesJsonForTinyMce();?>

            };
        </script>
        <!-- TinyMCE Shortcode Plugin -->
        <?php
    }

    public function add_buttons($plugin_array)
    {
        $plugin_array['affilinet_mce_button'] = plugin_dir_url( plugin_basename( dirname(__FILE__) )  ). 'js/affilinet_editor_buttons.js';

        return $plugin_array;
    }

    public function register_buttons($buttons)
    {
        array_push($buttons, 'affilinet_mce_button');

        return $buttons;
    }

    public function yielkit_code()
    {
        echo Affilinet_Yieldkit::getAdCode();
    }


    public function plugin_upgraded() {
        // check for correct ads.txt

        $filePath = ABSPATH.DIRECTORY_SEPARATOR.'ads.txt';

        $neededContent =
            '# affilinet-performance-module-start' . PHP_EOL .
            '# Do not modify the following lines' . PHP_EOL .
            '# Ver. 1.9.1' . PHP_EOL .
            'appnexus.com, 8332, RESELLER, f5ab79cb980f11d1' . PHP_EOL .
            'appnexus.com, 8327, RESELLER, f5ab79cb980f11d1' . PHP_EOL .
            'appnexus.com, 8334, RESELLER, f5ab79cb980f11d1' . PHP_EOL .
            'appnexus.com, 8333, RESELLER, f5ab79cb980f11d1'. PHP_EOL .
            '# affilinet-performance-module-end' . PHP_EOL;

        if (file_exists($filePath)) {
            $adsTxtFile = file_get_contents($filePath);
            if (strpos($adsTxtFile, $neededContent) === false) {
                // write to file
                file_put_contents($filePath, PHP_EOL . $neededContent, FILE_APPEND);
            }
        }else {
            file_put_contents($filePath, $neededContent, FILE_APPEND);
        }

    }

}
