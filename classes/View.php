<?php

class Affilinet_View
{
    public static function start()
    {
        require_once AFFILINET_PLUGIN_DIR . 'views' . DIRECTORY_SEPARATOR . 'start.php';
    }

    public static function settings()
    {
        require_once AFFILINET_PLUGIN_DIR . 'views' . DIRECTORY_SEPARATOR . 'settings.php';
    }

    public static function signup()
    {
        require_once AFFILINET_PLUGIN_DIR . 'views' . DIRECTORY_SEPARATOR . 'signup.php';
    }

    public static function reporting()
    {
        add_filter('admin_print_footer_scripts', array(__CLASS__, 'reporting_footer_script'));

        require_once AFFILINET_PLUGIN_DIR . 'views' . DIRECTORY_SEPARATOR . 'reporting.php';
    }

    public static function reporting_footer_script()
    {
        ?>
        <script type="text/javascript">
            function euroFormatter(v, axis)
            {
                return v.toFixed(axis.tickDecimals) + " €";

            }

            var options = {
                yaxes: [
                    {
                        position: "left",
                        min: 0,
                        tickDecimals: 0
                    },
                    {
                        position: "right",
                        min: 0,
                        tickDecimals: 0,
                        tickFormatter: euroFormatter

                    }
                ],
                xaxis: {
                    mode: "time",
                    timeformat: "%e.%b.%Y"
                },
                series: {
                    lines: {show: true},
                    points: {show: true}
                }
            };


            jQuery.plot('#graph-placeholder',
                [
                    {data: affilinet_report_data_views, label: "<?php _e('Views', 'affilinet'); ?>", yaxis: 1},
                    {data: affilinet_report_data_clicks, label: "<?php _e('Clicks', 'affilinet'); ?>", yaxis: 1},
                    {
                        data: affilinet_report_data_total,
                        label: "<?php _e('Open Commission', 'affilinet'); ?>",
                        yaxis: 2
                    }
                ], options);

        </script>
        <?php
    }

    public static function settings_script($hook)
    {
        // this js is only needed on settings page
        if ($hook !== 'affilinet_page_affilinet_settings') {
            return;
        }

        include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version
        /** @var String $wp_version */
        if ($wp_version < 3.5) {
            wp_enqueue_script( 'affilinet_settings', plugins_url('/affilinet/js/affilinet_settings.js'), array('jquery'), false, true );
        } else {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'affilinet_settings', plugins_url('/js/affilinet_settings.js', AFFILINET_PLUGIN_DIR.'affilinet' ), array( 'wp-color-picker' , 'jquery'), false, true );
        }

    }

}
