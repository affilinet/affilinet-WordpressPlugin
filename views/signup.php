<div class="wrap">
    <h2><?php _e('Signup', 'affilinet') ?></h2>
    <?php
    if (get_option('affilinet_platform') === false) {
        // No Platform has been setup
        ?>
        <p><?php _e('Please choose a platform where you want to sign up', 'affilinet') ?></p>

        <form method="post" action="options.php">
            <?php settings_fields('affilinet-settings-group'); ?>
            <?php do_settings_sections('affilinet-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label
                            for="affilinet_platform"><?php _e('affilinet Country Platform', 'affilinet') ?></label></th>
                    <td>
                        <select name="affilinet_platform" id="affilinet_platform">
                            <option <?php selected('1', get_option('affilinet_platform')); ?> value="1">
                                affilinet <?php _e('Germany', 'affilinet'); ?></option>
                            <option <?php selected('2', get_option('affilinet_platform')); ?> value="2">
                                affilinet <?php _e('United Kingdom', 'affilinet'); ?></option>
                            <option <?php selected('3', get_option('affilinet_platform')); ?> value="3">
                                affilinet <?php _e('France', 'affilinet'); ?></option>

                            <?php /*
                            <option <?php selected('4', get_option('affilinet_platform')); ?> value="4">
                                affilinet <?php _e('Netherlands', 'affilinet'); ?></option>
                            */ ?>

                            <option <?php selected('6', get_option('affilinet_platform')); ?> value="6">
                                affilinet <?php _e('Switzerland', 'affilinet'); ?></option>
                            <option <?php selected('7', get_option('affilinet_platform')); ?> value="7">
                                affilinet <?php _e('Austria', 'affilinet'); ?></option>

                        </select>
                    </td>
                </tr>


            </table>



            <?php

            if (function_exists('submit_button')) {
                submit_button(__('Save Platform', 'affilinet'));
            } else {
                ?>?>
                <button type="submit"><?php _e('Save', 'affilinet'); ?></button><?php
            }

            ?>

        </form>



    <?php
    } else {

        $platformId = get_option('affilinet_platform');
        $programId = Affilinet_PerformanceAds::getProgramIdByPlatform($platformId);

        $shortLocale = Affilinet_Helper::getShortLocale();

        include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version
        /** @var String $wp_version */

        $link = 'https://modules.affili.net/Signup/' .
            $programId . '?language=' . $shortLocale .
            '&platform=' . $platformId .
            '&referrer=WordPress-' . $wp_version . '-LTPlugin';
        ?>
        <style type="text/css" scoped="scoped">
            html {
                background-color: #EBE8E8 !important;
            }

            #signupIframe {
                height: 3500px;
                overflow: hidden;
                max-width: 768px;
                width: 100%;
                margin-left: -20px;
            }

            @media screen and (max-width: 782px) {
                #signupIframe {
                    margin-left: -10px;
                }
            }
        </style>
        <iframe id="signupIframe" src="<?php echo $link; ?>" style=""></iframe>
    <?php
    }
    ?>


</div>
