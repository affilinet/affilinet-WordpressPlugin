<?php


class Affilinet_Api
{

    /**
     * Login at the api to retrieve a token
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

            return $token;
        } catch (\SoapFault $e) {

            Affilinet_Helper::displayAdminError(__('Could not connect to Affilinet API. Please recheck your Webservice Password and Publisher ID', 'affilinet'));

            return false;
        }
    }

    public static function getDailyStatistics(\DateTime $start_date, \DateTime $end_date)
    {
        try {
            $daily_statistics_client = new \SoapClient('https://api.affili.net/V2.0/PublisherStatistics.svc?wsdl');
            $params = array(
                'CredentialToken' => self::logon(),
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

            Affilinet_Helper::displayAdminError(__('No data in selected time frame', 'affilinet'));

            return null;
        } catch (\SoapFault $e) {
            Affilinet_Helper::displayAdminError(__('Could not connect to Affilinet API. Please recheck your Webservice Password and Publisher ID', 'affilinet'));

            return false;
        }
    }
}
