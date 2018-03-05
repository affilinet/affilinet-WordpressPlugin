<?php


class Affilinet_PerformanceAds
{

    /**
     * Get the ProgramID for a Platform Id
     *
     * Returns false if the platform id is invalid
     *
     * @param int $platformId
     *
     * @return bool|int $programId
     */
    public static function getProgramIdByPlatform($platformId)
    {
        switch ($platformId) {
            case 1: // DE

                return 9192;
            case 2: // UK

                return 12752;
            case 3: // FR

                return 12751;
            case 4: // NL

                return 13397;
            case 6: // CH

                return 12252;
            case 7: // AT

                return 12376;

            default :
                return false;
        }
    }

    /**
     * Return the AdCode for the given size
     * $size must be one of '728x90','300x250','250x250', '468x60', '160x600', '120x600', '168x28', '216x36', '300x50', '320x50' , '300x600'
     * @param $size
     * @return string|void
     */
    public static function getAdCode($size)
    {
        $publisherId = get_option('affilinet_publisher_id');
        $platformId = get_option('affilinet_platform');

        if ($publisherId === false || $publisherId === '') {
            return __('No publisher ID given', 'affilinet-performance-module');
        }
        if ($platformId === false || $platformId === '') {
            return __('No platform  chosen', 'affilinet-performance-module');
        }

        /**
         * Disable Netherlands
         */
        if ($platformId == 4) {
            return '';
        }

        $programId = Affilinet_PerformanceAds::getProgramIdByPlatform($platformId);
        $viewUrl = Affilinet_Helper::getViewHostnameForPlatform($platformId);
        $clickUrl = Affilinet_Helper::getClickHostnameForPlatform($platformId);
        $pluginVersion = Affilinet_Helper::get_plugin_version();
        $wpVersion =  get_bloginfo('version');

        $subIdArray = apply_filters('affilinet_subid_array', array('Wordpress'.$wpVersion, 'Plugin'.$pluginVersion));
        if (is_array($subIdArray)) {
            $subId = implode('-', $subIdArray);
        } else {
            $subId = 'Wordpress'.$wpVersion.'-Plugin'.$pluginVersion;
        }


        $hnb = self::getHnbForPlatform($platformId, $size);

        if ($hnb === false) {
            return __('Invalid ad size given. Choose one of "728x90","300x250","250x250","468x60","160x600","120x600"', 'affilinet-performance-module');
        }

        $html = '<script language="javascript" type="text/javascript" src="' .
            '//' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&type=html&hnb=' . $hnb . '&js=1&subid='.$subId.
            '"></script><noscript><a href="' .
            '//' . $clickUrl . '/click.asp?ref=' . $publisherId . '&site=' . $programId . '&type=b1&bnb=1&subid='.$subId.
            '" target="_blank"><img src="' .
            '//' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&b=1&subid='.$subId.
            '" border="0"/></a><br /></noscript>';

        return $html;
    }

    /**
     * Get the HNB paramter for performance Ads
     * @param $platformId
     * @param $size
     * @return bool
     */
    public static function getHnbForPlatform($platformId, $size)
    {
        $hnb = array(
            // DE
            1 => array(
                '728x90'  => 1,
                '300x250' => 4,
                '250x250' => 6,
                '468x60'  => 5,
                '160x600' => 3,
                '120x600' => 2,
                '168x28'  => 13,
                '216x36'  => 14,
                '300x50'  => 15,
                '320x50'  => 16
            ),
            // AT
            7 => array(
                '728x90' => 1,
                '300x250' => 2,
                '250x250' => 6,
                '468x60' => 3,
                '160x600' => 4,
                '120x600' => 5,
                '168x28'  => 13,  // not yet available in AT
                '216x36'  => 14,  // not yet available in AT
                '300x50'  => 15,  // not yet available in AT
                '320x50'  => 16   // not yet available in AT

            ),
            // CH
            6 => array(
                '728x90' => 1,
                '300x250' => 2,
                '250x250' => 6,
                '468x60' => 4,
                '160x600' => 3,
                '120x600' => 5,
                '168x28'  => 13,  // not yet available in CH
                '216x36'  => 14,  // not yet available in CH
                '300x50'  => 15,  // not yet available in CH
                '320x50'  => 16   // not yet available in CH
            ),
            // UK
            2 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5,
                '168x28'  => 13,  // not yet available in UK
                '216x36'  => 14,  // not yet available in UK
                '300x50'  => 15,  // not yet available in UK
                '320x50'  => 16   // not yet available in UK

            ),
            // FR
            3 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5,
                '168x28'  => 13,
                '216x36'  => 14,
                '300x50'  => 15,
                '320x50'  => 16,
                '300x600'  => 17   // only available in FR
            ),
            // NL - currently not implemented
            4 => array(
                '728x90' => 2,
                '300x250' => 3,
                '250x250' => 6,
                '468x60' => 1,
                '160x600' => 4,
                '120x600' => 5,
                '168x28'  => 13,
                '216x36'  => 14,
                '300x50'  => 15,
                '320x50'  => 16
            )
        );
        if (isset($hnb[$platformId]) && isset($hnb[$platformId][$size])) {
            return $hnb[$platformId][$size];
        } else {
            return false;
        }

    }
}
