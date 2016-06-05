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
    public static function displayAdminError($message)
    {
        ?>
        <div class="error">
            <p>
                <?php echo $message;?>
            </p>
        </div>
        <?php
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
