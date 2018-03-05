<?php

class Affilinet_Widget extends \WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => __NAMESPACE__ . '\\' . __CLASS__,
            'description' => 'affilinet Performance Ads'
        );
        parent::__construct('Affilinet_Performance_Ad_Widget', 'Affilinet Performance Ads', $widget_ops);

    }

    /**
     * Display the widget edit form
     *
     * @param array $instance
     *
     * @return void
     */
    public function form($instance)
    {
        $defaults = array(
            'size' => '728x90'
        );
        $instance = wp_parse_args((array)$instance, $defaults);
        $size = $instance['size'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Banner size', 'affilinet-performance-module'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>"
                    name="<?php echo $this->get_field_name('size'); ?>">
                <?php


                foreach ($this->allowedSizes() as $optGroup) {
                    ?>
                    <optgroup label="<?php echo $optGroup['name'] ?>">
                        <?php

                        foreach ($optGroup['values'] as $allowed_size) {
                            ?>
                            <option
                                value="<?php echo $allowed_size['value']; ?>"
                                <?php selected($size, $allowed_size['value']); ?>><?php echo $allowed_size['name']; ?></option>
                            <?php
                        }
                        ?>
                    </optgroup>
                    <?php
                }
                ?>
            </select>

        </p>
        <?php
    }


    public static function getAllowedSizesJsonForTinyMce()
    {
        $sizes = self::allowedSizes();
        $return = array();

        foreach ($sizes as $category) {
            $return[] = array('text' => $category['name'], 'disabled' => true);
            foreach ($category['values'] as $entry) {
                $return[] = array('text' => $entry['name'], 'value' => $entry['value'], 'disabled' => false);
            }
        }
        return json_encode($return, JSON_PRETTY_PRINT);
    }

    /**
     * Return a list of allowed banner sizes
     * @return array
     */
    private static function allowedSizes()
    {
        $allowedPlatFormSizes = array(

            // DE
            1 => array(
                array('name' => '----- DESKTOP -----', 'values' =>
                    array(
                        array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                        array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                        array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                        array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                        array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                        array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)')
                    )
                ),
                array('name' => '----- MOBILE -----', 'values' =>
                    array(
                        array('value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'),
                        array('value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'),
                        array('value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'),
                        array('value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)')
                    )
                )

            ),
            // AT
            7 => array(
                    array('name' => '----- DESKTOP -----', 'values' =>
                        array(
                            array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                            array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                            array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                            array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                            array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                            array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)')
                        )
                    ),

                    /**
                     *  mobile sizes not yet available in AT
                     **/

                    /*
                    ['name' => '----- MOBILE -----', 'values' =>
                        [
                            ['value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'],
                            ['value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'],
                            ['value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'],
                            ['value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)']
                        ]
                    ]*/
            ),
            // CH
            6 => array(
                array('name' => '----- DESKTOP -----', 'values' =>
                    array(
                        array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                        array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                        array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                        array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                        array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                        array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)')
                    )
                ),
                /**
                 *  mobile sizes not yet available in CH
                 **/
                /*
                ['name' => '----- MOBILE -----', 'values' =>
                    [
                        ['value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'],
                        ['value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'],
                        ['value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'],
                        ['value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)']
                    ]
                ]
                */

            ),
            // UK
            2 => array(
                array('name' => '----- DESKTOP -----', 'values' =>
                    array(
                        array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                        array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                        array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                        array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                        array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                        array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)')
                    )
                ),
                /**
                 *  mobile sizes not yet available in UK
                 **/
                /*
                ['name' => '----- MOBILE -----', 'values' =>
                    [
                        ['value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'],
                        ['value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'],
                        ['value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'],
                        ['value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)']
                    ]
                ]*/
            ),
            // FR
            3 => array(
                array('name' => '----- DESKTOP -----', 'values' =>
                    array(
                        array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                        array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                        array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                        array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                        array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                        array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)'),
                        array('value' => '300x600', 'name' => 'Half Page (300px x 600px)')
                    )
                ),
                array('name' => '----- MOBILE -----', 'values' =>
                    array(
                        array('value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'),
                        array('value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'),
                        array('value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'),
                        array('value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)')
                    )
                )

            ),
            // NL - currently not implemented
            4 => array(
                array('name' => '----- DESKTOP -----', 'values' =>
                    array(
                        array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
                        array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
                        array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
                        array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
                        array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
                        array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)'),
                        array('value' => '300x600', 'name' => 'Half Page (300px x 600px)')
                    )
                ),
                /**
                 *  mobile sizes not yet available in NL
                 **/
                /*
                ['name' => '----- MOBILE -----', 'values' =>
                    [
                        ['value' => '168x28', 'name' => 'Feature Phone Medium Banner (168px x 28px)'],
                        ['value' => '216x36', 'name' => 'Feature Phone Large Banner (216px x 36px)'],
                        ['value' => '300x50', 'name' => 'Smartphone Banner (300px x 50px)'],
                        ['value' => '320x50', 'name' => 'Smartphone Wide Banner (320px x 50px)']
                    ]
                ]
                */
            )
        );


        return $allowedPlatFormSizes[(int)get_option('affilinet_platform', 1)];

    }

    /**
     * Handle widget update process
     *
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['size'] = $new_instance['size'];

        return $instance;

    }

    /**
     * Display the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        extract($args);
        /** @var String $before_widget */
        echo $before_widget;

        echo Affilinet_PerformanceAds::getAdCode($instance['size']);

        /** @var String $after_widget */
        echo $after_widget;
    }
}
