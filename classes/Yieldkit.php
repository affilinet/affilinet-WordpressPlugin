<?php

class Affilinet_Yieldkit
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

                return 12816;
            case 2: // UK

                return 12973;
            case 3: // FR

                return 13179;
            case 4: // NL

                return 13828;
            case 6: // CH

                return 1317;
            case 7: // AT

                return 13181;

            default :
                return false;
        }
    }

    /**
     * Return the AdCode for the given size
     * @return string|void
     */
    public static function getAdCode()
    {
        $publisherId = get_option('affilinet_publisher_id');
        $platformId = get_option('affilinet_platform');

        if ($publisherId === false || $publisherId === '') {
            return __('No publisher ID given', 'affilinet-performance-module');
        }
        if ($platformId === false || $platformId === '') {
            return __('No platform  chosen', 'affilinet-performance-module');
        }

        $programId = self::getProgramIdByPlatform($platformId);
        $viewUrl = Affilinet_Helper::getViewHostnameForPlatform($platformId);
        $clickUrl = Affilinet_Helper::getClickHostnameForPlatform($platformId);

        $yword      = get_option( 'affilinet_text_monetization');
        if (!$yword) $yword = '0';

        $ylink      = get_option( 'affilinet_link_replacement' );
        if (!$ylink) $ylink = '0';

        $ywidget    = get_option( 'affilinet_text_widget');
        if (!$ywidget) $ywidget = '0';

        $ywidgetpos = get_option( 'affilinet_ywidgetpos' ,'1');
        if (!$ywidgetpos) $ywidgetpos = '1';

        $ywdensity  = get_option( 'affilinet_ywdensity','10' );
        if (!$ywdensity) $ywdensity = '10';

        $ywcap      = get_option( 'affilinet_ywcap','50' );
        if (!$ywcap) $ywcap = '50';

        $ywcolor    = get_option( 'affilinet_ywcolor' );
        if (!$ywcolor) $ywcolor = '000000';
        $ywcolor = str_replace('#','',$ywcolor);
        $hnb = 1;

        $scriptUrl = '//' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&type=html&hnb=' . $hnb . '&js=1' .
            '&yword='. $yword.
            '&ylink='.$ylink
            .'&ywidget='.$ywidget
            .'&yimage=0&ywidgetpos='.$ywidgetpos
            .'&ywdensity='.$ywdensity
            .'&ywcap='.$ywcap
            .'&ywcolor='.$ywcolor
            .'&ywcid=content&ywcclass=site-content';

        $noScriptUrl = '//' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&type=b1&bnb=1' .
            '&yword='. $yword.
            '&ylink='.$ylink
            .'&ywidget='.$ywidget
            .'&yimage=0&ywidgetpos='.$ywidgetpos
            .'&ywdensity='.$ywdensity
            .'&ywcap='.$ywcap
            .'&ywcolor='.$ywcolor
            .'&ywcid=content&ywcclass=site-content';

        $html = '<script language="javascript" type="text/javascript" src="'
            .$scriptUrl
            .'"></script><noscript><a href="'
            .$noScriptUrl
            .'" target="_blank"><img title="1x1" alt="1x1" src="'
            .'//' . $viewUrl . '/view.asp?ref=' . $publisherId . '&site=' . $programId . '&b=1'
            .'" border="0"/></a><br /></noscript>';

        return $html;
    }

}
