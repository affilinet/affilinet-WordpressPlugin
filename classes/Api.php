<?php


class Affilinet_Api
{

    /**
     * Login at the aregisteredve a token
     *
     * Returns false if password mismatch
     *
     * @return bool|String $credentialToken
     */
    public static function logon()
    {
        try {
            $logon_client = new \SoapClient('https://api.affili.net/V2.0/Logon.svc?wsdl');
            $params = array(
                "Username" => get_option('affilinet_publisher_id'),
                "Password" => get_option('affilinet_standard_webservice_password'),
                "WebServiceType" => "Publisher"
            );
            $token = $logon_client->__soapCall("Logon", array($params));

            if ($token !== false) {
                update_option('affilinet_webservice_login_is_correct', 'true', true);
                wp_cache_delete ( 'alloptions', 'options' );
            }

            return $token;
        } catch (\SoapFault $e) {
            update_option('affilinet_webservice_login_is_correct', 'false', true);
            wp_cache_delete ( 'alloptions', 'options' );

            Affilinet_Helper::displayHugeAdminMessage(__('Could not connect to affilinet API. Please recheck your Webservice Password and Publisher ID', 'affilinet'));

            return false;
        }
    }

    public static function getDailyStatistics(\DateTime $start_date, \DateTime $end_date)
    {
        try {
            $token =  self::logon();
            if ($token === false) {
                return false;
            }
            $daily_statistics_client = new \SoapClient('https://api.affili.net/V2.0/PublisherStatistics.svc?wsdl');
            $params = array(
                'CredentialToken' => $token,
                'GetDailyStatisticsRequestMessage' => array(
                    'StartDate' => (int) date_format($start_date, 'U'),
                    'EndDate' => (int) date_format($end_date, 'U'),
                    'SubId' => '',
                    'ProgramTypes' => 'All',
                    'ValuationType' => 'DateOfRegistration',
                    'ProgramId' => Affilinet_PerformanceAds::getProgramIdByPlatform(get_option('affilinet_platform'))

                )
            );
            $statistics = $daily_statistics_client->__soapCall('GetDailyStatistics', array($params));
            if (isset($statistics->DailyStatisticsRecords->DailyStatisticRecords->DailyStatisticsRecord)) {
                return $statistics->DailyStatisticsRecords->DailyStatisticRecords->DailyStatisticsRecord;
            }

            Affilinet_Helper::displayHugeAdminMessage(__('No data in selected time frame', 'affilinet'));

            return null;
        } catch (\SoapFault $e) {
            Affilinet_Helper::displayHugeAdminMessage(__('Could not connect to affilinet API. Please recheck your Webservice Password and Publisher ID', 'affilinet'));

            return false;
        }
    }

    public static function checkPartnershipStatus()
    {
        try {
            $token =  self::logon();
            if ($token === false) {
                return false;
            }

            $client = new \SoapClient('https://api.affili.net/V2.0/PublisherProgram.svc?wsdl');
            $params = array(
                'CredentialToken' => $token,
                'DisplaySettings' => array(
                    'CurrentPage' => 1,
                    'PageSize' => 1,
                    'SortByEnum' => 'ProgramId',
                    'SortOrderEnum' => 'Descending'

                ),
                'GetProgramsQuery' => array(

                    'ProgramIds' => array(
                        Affilinet_PerformanceAds::getProgramIdByPlatform(get_option('affilinet_platform'))
                    ),
                    'PartnershipStatus' => array(
                        'Active', 'Paused', 'Waiting', 'Refused', 'NoPartnership', 'Cancelled'
                    )

                )
            );
            $programs = $client->__soapCall('GetPrograms', array($params));
            if ($programs->TotalResults === 0 ) {
                update_option('affilinet_webservice_login_is_correct', 'false', true);
                Affilinet_Helper::displayHugeAdminMessage(__('Wrong platform selected.<br> It seems like your account is registered to another country\'s platform.', 'affilinet'), 'error',  'fa-exclamation-triangle');
            }


            switch ($programs->ProgramCollection->Program->PartnershipStatus) {
                case 'Active':
                    Affilinet_Helper::displayHugeAdminMessage(__('Great, it looks like you already have a partnership with PerformanceAds! <br> Feel free to start using the plugin right away!', 'affilinet'), 'success',  'fa-check-circle');
                    break;
                case 'Paused' :
                case 'Waiting' :
                case 'NoPartnership':
                    Affilinet_Helper::displayHugeAdminMessage(
                        __('Please be aware that in order to earn commission for delivering creatives, a partnership with the PerformanceAds program is required.<br>Please apply <a target="_blank" href="http://publisher.affili.net/Programs/ProgramInfo.aspx?pid=', 'affilinet')
                        . Affilinet_PerformanceAds::getProgramIdByPlatform(get_option('affilinet_platform')) .
                        __('">here</a>. Your partnership will be automatically accepted.', 'affilinet'), 'warning',  'fa-exclamation-triangle');
                    break;
                case 'Refused' :
                case 'Cancelled' :
                    $link = Affilinet_Helper::getQualityStandardsLink();
                    Affilinet_Helper::displayHugeAdminMessage(__('Unfortunately your partnership with PerformanceAds has been cancelled, as your website does not meet our quality standards. <br> For more information please visit our <a target="_blank" href="', 'affilinet')
                        . $link .
                        __('">quality standards page.</a>', 'affilinet'), 'error',  'fa-ban');
                    break;
            }

            return null;
        } catch (\SoapFault $e) {
            Affilinet_Helper::displayHugeAdminMessage(__('Please make sure you have entered the correct PublisherID and Webservice password.', 'affilinet'));

            return false;
        }

    }
}
