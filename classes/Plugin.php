<?php

class Affilinet_Plugin
{

    public function __construct()
    {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('widgets_init', array($this, 'register_widget'));

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_shortcode('affilinet_performance_ad', array($this, 'performance_ad_shortcode'));

        add_action( 'admin_notices', array( $this, 'admin_notice' ));

    }

    function admin_notice() {
        if (get_option('affilinet_webservice_login_is_correct') === 'false') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e('<strong>affilinet Performance Ads:</strong><br> Please make sure you have entered the correct PublisherID and Webservice password.', 'affilinet' ); ?>
                <a class="button" href="admin.php?page=affilinet_settings"><?php _e('Check your settings.', 'affilinet');?></a>
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
        add_submenu_page('affilinet', __('Start', 'affilinet'), __('Start', 'affilinet'), 'manage_options', 'affilinet', 'Affilinet_View::start');
        add_submenu_page('affilinet', __('Settings', 'affilinet'), __('Settings', 'affilinet'), 'manage_options', 'affilinet_settings', 'Affilinet_View::settings');


        if (get_option('affilinet_webservice_login_is_correct', 'false') === 'false') {
            add_submenu_page('affilinet', __('Signup', 'affilinet'), __('Signup', 'affilinet'), 'manage_options', 'affilinet_signup', 'Affilinet_View::signup');
        }

        add_submenu_page('affilinet', __('Reporting', 'affilinet'), __('Reporting', 'affilinet'), 'manage_options', 'affilinet_reporting', 'Affilinet_View::reporting');

        // options menu
        add_options_page('affilinet Settings', 'affilinet', 'manage_options', 'affilinet_options', 'Affilinet_View::settings');
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
        // on settings page integrate font awesome

        if ($hook == 'affilinet_page_affilinet_settings') {
            wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        }



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
        load_plugin_textdomain( 'affilinet', false, dirname(dirname( plugin_basename( __FILE__ ) )) . '/languages' );
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

}
