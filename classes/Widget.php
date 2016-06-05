<?php

class Affilinet_Widget extends \WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => __NAMESPACE__ . '\\' . __CLASS__,
            'description' => 'Affilinet Performance Ads'
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
        $instance = wp_parse_args((array) $instance, $defaults);
        $size = $instance['size'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Banner size', 'affilinet');?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>"
                    name="<?php echo $this->get_field_name('size'); ?>">
                <?php
                foreach ($this->allowedSizes() as $allowed_size) {
                    ?>
                    <option
                        value="<?php echo $allowed_size['value']; ?>"
                        <?php selected($size, $allowed_size['value']); ?>><?php echo $allowed_size['name']; ?></option>
                <?php
                }
                ?>
            </select>

        </p>
    <?php
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

    /**
     * Return a list of allowed banner sizes
     * @return array
     */
    private function allowedSizes()
    {
        return array(
            array('value' => '728x90', 'name' => 'Super Banner (728px x 90px)'),
            array('value' => '300x250', 'name' => 'Medium Rectangle (300px x 250px)'),
            array('value' => '250x250', 'name' => 'Square Button (250px x 250px)'),
            array('value' => '468x60', 'name' => 'Fullsize Banner (468px x 60px)'),
            array('value' => '160x600', 'name' => 'Wide Scyscraper (160px x 600px)'),
            array('value' => '120x600', 'name' => 'Scyscraper (120px x 600px)')
        );
    }
}
