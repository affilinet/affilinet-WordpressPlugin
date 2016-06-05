<div class="wrap">
    <h2><?php _e('Reporting', 'affilinet') ?></h2>

    <?php
    if (get_option('affilinet_standard_webservice_password') === false
        || get_option('affilinet_standard_webservice_password') == ''
        || get_option('affilinet_publisher_id') == ''
        || get_option('affilinet_publisher_id') === false
    ) {
        // no webservice password or no publisher id given

        ?>
        <p>
            <?php _e('This reporting page is deactivated since you did not entered your publisher web service password on the settings page.', 'affilinet'); ?>

            <br><?php _e('Please provide your', 'affilinet'); ?> <a
                href="<?php echo admin_url('admin.php?page=affilinet_settings'); ?>"><?php _e('webservice password and publisher ID', 'affilinet'); ?>
        </p>
    <?php
    } else {
        // webservice password and publisher id given

        if (!class_exists('SoapClient')) {
            // @todo: integrate http://sourceforge.net/projects/nusoap/
            ?><p>Missing SoapExtension on your webserver</p><?php
        } else {

            if (isset($_GET['month'])) {
                $selected_month = $_GET['month'];
            } else {
                $selected_month = date('n', time());
            }

            $this_year = date('Y', time());
            if (isset($_GET['year'])) {
                $selected_year = $_GET['year'];
            } else {
                $selected_year = $this_year;
            }

            if (phpversion() < 5.3) {
                $selected_end_month = $selected_month;
                $selected_end_year = $selected_year;

                if ($selected_end_month == 12) {
                    $selected_end_month = 1;
                    $selected_end_year = $selected_end_year + 1;
                } else {
                    $selected_end_month++;
                }
                $start_date = date_create('01.' . $selected_month . "." . $selected_year . " 00:00:00");
                $end_date = date_create('01.' . $selected_end_month . "." . $selected_end_year . " 00:00:00");
            } else {
                $start_date = date_create_from_format('j.n.Y h:i:s', '1.' . $selected_month . "." . $selected_year . " 00:00:00");
                $end_date = date_create_from_format('j.n.Y  h:i:s', '1.' . $selected_month . "." . $selected_year . " 00:00:00");
                $end_date->add(DateInterval::createFromDateString('1 month'))->sub(DateInterval::createFromDateString('1 day'));
            }

            $dailyStatistics = Affilinet_Api::getDailyStatistics($start_date, $end_date);

            if ($dailyStatistics !== false) {

                if ($dailyStatistics !== null) : ?>
                    <div id="graph-placeholder" style="width: 100%;height: 400px;"></div>
                <?php endif; ?>

                <div class="tablenav top">
                    <form method="get" action="<?php echo admin_url('admin.php?page=affilinet_reporting'); ?>">
                        <div class="alignleft actions">
                            <label for="filter-by-date-month"
                                   class="screen-reader-text"><?php _e('Select month', 'affilinet'); ?></label>
                            <select name="month" id="filter-by-date-month">
                                <?php


                                for ($i = 1; $i <= 12; $i++) {
                                    $month_name = date_i18n('F', mktime(0, 0, 0, $i, 1, 2011));
                                    ?>
                                    <option <?php selected($i, $selected_month); ?>
                                        value="<?php echo $i; ?>"><?php echo $month_name; ?></option>
                                <?php
                                }

                                ?>
                            </select>
                            <label for="filter-by-date-year"
                                   class="screen-reader-text"><?php _e('Select year', 'affilinet'); ?></label>
                            <select name="year" id="filter-by-date-year">
                                <?php
                                // maximum of 5 years before actual year

                                for ($i = $this_year; ($this_year - $i) < 5; $i--) {
                                    ?>
                                    <option <?php selected($i, $selected_year); ?>
                                        value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <input type="hidden" name="page" value="affilinet_reporting"/>
                            <input type="submit" id="post-query-submit" class="button"
                                   value="<?php _e('Show report', 'affilinet'); ?>">
                        </div>
                    </form>
                    <br class="clear">
                </div>
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="manage-column column-Date                   "
                            scope="col"><?php _e('Date', 'affilinet'); ?></th>
                        <th class="manage-column column-Views                  "
                            scope="col"><?php _e('Views', 'affilinet'); ?></th>
                        <th class="manage-column column-Clicks                 "
                            scope="col"><?php _e('Clicks', 'affilinet'); ?></th>
                        <th class="manage-column column-OpenSalesLeads         "
                            scope="col"><?php _e('Open SalesLeads', 'affilinet'); ?></th>
                        <th class="manage-column column-ConfirmedSalesLeads    "
                            scope="col"><?php _e('Confirmed SalesLeads', 'affilinet'); ?></th>
                        <th class="manage-column column-DeclinedSalesLeads     "
                            scope="col"><?php _e('Declined SalesLeads', 'affilinet'); ?></th>
                        <th class="manage-column column-OpenCommision          "
                            scope="col"><?php _e('Open Commission', 'affilinet'); ?></th>
                        <th class="manage-column column-ConfirmedCommission     "
                            scope="col"><?php _e('Confirmed Commission', 'affilinet'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $graphData = array();

                    $totalViews =
                    $totalClicks =
                    $totalOpenSales =
                    $totalConfirmedSales =
                    $totalCancelledSales =
                    $totalTotalOpenCommission =
                    $totalTotalCommission = 0;

                    if ($dailyStatistics !== null) {

                        foreach ($dailyStatistics as $day) {
                            ?>
                            <tr class="alternate">
                                <td class="column-Date                   "><?php echo date_i18n(get_option('date_format'), strtotime($day->Date)); ?></td>
                                <td class="column-Views                  "><?php echo number_format_i18n($day->CombinedPrograms->Views + $day->PayPerSaleLead->Views, 0); ?></td>
                                <td class="column-Clicks                 "><?php echo number_format_i18n($day->CombinedPrograms->Clicks + $day->PayPerSaleLead->Clicks, 0); ?></td>
                                <td class="column-OpenSalesLeads         "><?php echo number_format_i18n($day->CombinedPrograms->OpenSales + $day->PayPerSaleLead->OpenSales, 0); ?></td>
                                <td class="column-ConfirmedSalesLeads    "><?php echo number_format_i18n($day->CombinedPrograms->ConfirmedSales + $day->PayPerSaleLead->ConfirmedSales, 0); ?></td>
                                <td class="column-DeclinedSalesLeads     "><?php echo number_format_i18n($day->CombinedPrograms->CancelledSales + $day->PayPerSaleLead->CancelledSales, 0); ?></td>
                                <td class="column-OpenCommision          "><?php echo number_format_i18n($day->TotalOpenCommission, 2); ?> <?php echo Affilinet_Helper::getCurrencyForPlatformId(get_option('affilinet_platform')); ?></td>
                                <td class="column-ConfirmedCommission     "><?php echo number_format_i18n($day->TotalCommission, 2); ?> <?php echo Affilinet_Helper::getCurrencyForPlatformId(get_option('affilinet_platform')); ?></td>

                            </tr>
                            <?php
                            $totalViews += $day->CombinedPrograms->Views + $day->PayPerSaleLead->Views;
                            $totalClicks += $day->CombinedPrograms->Clicks + $day->PayPerSaleLead->Clicks;
                            $totalOpenSales += $day->CombinedPrograms->OpenSales+ $day->PayPerSaleLead->OpenSales;
                            $totalConfirmedSales += $day->CombinedPrograms->ConfirmedSales + $day->PayPerSaleLead->ConfirmedSales;
                            $totalCancelledSales += $day->CombinedPrograms->CancelledSales + $day->PayPerSaleLead->CancelledSales;
                            $totalTotalOpenCommission += $day->TotalOpenCommission;
                            $totalTotalCommission += $day->TotalCommission;

                            $graphData['views'][] = array(strtotime($day->Date) * 1000, $day->CombinedPrograms->Views + $day->PayPerSaleLead->Views ); // multiply with 1000 to trasnsform UNIX timestamp to JS timestamp
                            $graphData['clicks'][] = array(strtotime($day->Date) * 1000, $day->CombinedPrograms->Clicks + $day->PayPerSaleLead->Clicks);
                            $graphData['total'][] = array(strtotime($day->Date) * 1000, $day->TotalCommission);
                        }

                        ?>
                        <script type="application/javascript">
                            var affilinet_report_data_views = <?php echo json_encode($graphData['views']);?>;
                            var affilinet_report_data_clicks = <?php echo json_encode($graphData['clicks']);?>;
                            var affilinet_report_data_total = <?php echo json_encode($graphData['total']);?>;
                        </script><?php


                    } else {
                    ?>
                    <tr class="alternate">
                        <td colspan="8" class="col center"><?php _e('No data for this month', 'affilinet'); ?></td>
                        <?php
                        }

                        ?>
                    </tbody>
                    <tfoot>
                    <tr class="alternate">
                        <th class="manage-column column-Date                   "><?php _e('Total sum', 'affilinet'); ?></th>
                        <th class="manage-column column-Views                  "><?php echo number_format_i18n($totalViews, 0); ?></th>
                        <th class="manage-column column-Clicks                 "><?php echo number_format_i18n($totalClicks, 0); ?></th>
                        <th class="manage-column column-OpenSalesLeads         "><?php echo number_format_i18n($totalOpenSales, 0); ?></th>
                        <th class="manage-column column-ConfirmedSalesLeads    "><?php echo number_format_i18n($totalConfirmedSales, 0); ?></th>
                        <th class="manage-column column-DeclinedSalesLeads     "><?php echo number_format_i18n($totalCancelledSales, 0); ?></th>
                        <th class="manage-column column-OpenCommision          "><?php echo number_format_i18n($totalTotalOpenCommission, 2); ?> <?php echo Affilinet_Helper::getCurrencyForPlatformId(get_option('affilinet_platform')); ?></th>
                        <th class="manage-column column-ConfirmedCommission     "><?php echo number_format_i18n($totalTotalCommission, 2); ?> <?php echo Affilinet_Helper::getCurrencyForPlatformId(get_option('affilinet_platform')); ?></th>
                    </tr>
                    </tfoot>
                </table>

            <?php

            }// ENDIF Daily Statistics is not false
        }

    }
    ?>


</div>
