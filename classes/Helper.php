<?php


class Affilinet_Helper
{

    /**
     * Get the currency String for the given platform
     * @param  Int    $platformId
     * @return String $currencyCode
     */
    public static function getCurrencyForPlatformId($platformId)
    {
        switch ($platformId) {
            case 1: // DE
            case 3: // FR
            case 4: // NL
            case 7: // AT

                return '&euro;';
            case 2: // UK

                return '&pound;';
            case 6: // CH

                return 'CHF';
            default :
                return '';
        }
    }

    /**
     * Return the platforms' view hostname
     * @param  Int         $platformId
     * @return bool|string $hostName
     */
    public static function getViewHostnameForPlatform($platformId)
    {
        switch ($platformId) {
            case 1: // de
            case 7: // at
            case 6: // ch

                return 'banners.webmasterplan.com';
            case 2: //uk

                return 'become.successfultogether.co.uk';
            case 3: //fr

                return 'banniere.reussissonsensemble.fr';
            case 4:
                return 'worden.samenresultaat.nl';
            default :
                return false;
        }
    }

    /**
     * Return the platforms' click hostname
     * @param  Int         $platformId
     * @return bool|string $hostName
     */
    public static function getClickHostnameForPlatform($platformId)
    {
        switch ($platformId) {
            case 1: // DE
            case 7: // AT
            case 6: // CH

                return 'partners.webmasterplan.com';
            case 2: // UK

                return 'being.successfultogether.co.uk';
            case 3: // FR

                return 'clic.reussissonsensemble.fr';
            case 4: // NL

                return 'zijn.samenresultaat.nl';
            default :
                return false;
        }
    }

    /**
     * Get the short locale String
     * will return de for locale like de_DE
     * @return string
     */
    public static function getShortLocale()
    {
        $locale = get_locale();
        $shortLocale = mb_substr($locale, 0, 2);

        return $shortLocale;
    }

    /**
     * Helper to display an error message
     */
    public static function displayHugeAdminMessage($message, $type = 'error', $icon = false)
    {
        ?>
        <div class="notice-<?php echo $type?> notice" style="min-height:75px;">

            <?php
            if ($icon !== false) {
                switch ($type) {
                    case'error' : $color = 'rgb(230, 73, 64)';break;
                    case'warning' : $color = 'rgb(255, 197, 2)';break;
                    case'success' : $color = 'rgb(84, 190, 100)';break;
                    case'info' :
                    default:
                        $color = 'rgb(23, 175, 218)';
                }
                ?>
                <div style="width: 50px;padding: 10px 20px;display: inline-block;">
                    <i class="fa <?php echo $icon; ?>" style="font-size: 40px; color: <?php echo $color;?>; position:absolute; margin-top:10px;"></i>
                </div>
                <?php
            }
            ?>


            <p style="display: inline-block;position: absolute; margin-top: 18px;">
                <strong>
                    <?php echo $message;?>
                </strong>
            </p>
            <div class="clearfix"></div>
        </div>
        <?php
    }

    /**
     * @return string
     */
    public static function getQualityStandardsLink()
    {
        switch ($platformId = get_option('affilinet_platform')) {

            case 7: // AT
                return 'https://www.affili.net/at/advertiser/plattform/sicherheit-und-transparenz';
            case 6: // CH
                return 'https://www.affili.net/ch/advertiser/plattform/sicherheit-und-transparenz';
            case 2: // UK
                return 'https://www.affili.net/uk/advertisers-andagencies/platform/quality-management';
            case 3: // FR
                return 'https://www.affili.net/fr/annonceurs-etagences/plateforme/securite-et-transparence';
            case 4: // NL (not implemented)
            case 1: // DE
            default :
                return 'https://www.affili.net/de/advertiser/plattform/sicherheit-und-transparenz';
        }
    }

    /**
     * Returns current plugin version.
     *
     * @return string Plugin version
     */
    public static function get_plugin_version()
    {
        if ( ! function_exists( 'get_plugins' ) )
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_folder = get_plugin_data(AFFILINET_PLUGIN_DIR.DIRECTORY_SEPARATOR.'affilinet.php')  ;

        return $plugin_folder['Version'];
    }
}
