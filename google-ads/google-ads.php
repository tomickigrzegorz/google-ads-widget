<?php
/*
Plugin Name: Google Ads
Description: This is an simple adsense insertion
Author: Grzegorz Tomicki
Version: 1.0
Author URI: https://github.com/tomik23
*/


class Google_Ads extends WP_Widget
{
  public function __construct()
  {
    $widget_ops = array(
      'classname' => 'googleAds_widget',
      'description' => 'A plugin for Google Ads',
    );
    parent::__construct('googleAds_widget', 'Google Ads Widget', $widget_ops);
  }


  // The widget form (for the backend )
  public function form($instance)
  {

    // Set widget defaults
    $defaults = array(
      'title'    => '',
      'slot'     => '',
      'text'     => '',
      'textarea' => '',
      'checkbox' => '',
      'select'   => '',
    );

    // Parse current settings with defaults
    extract(wp_parse_args(( array )$instance, $defaults)); ?>

  <?php
  ?>
  <p>
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Pub code', 'googleadsWG'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </p>
  <p>
    <label for="<?php echo esc_attr($this->get_field_id('slot')); ?>"><?php _e('Slot code', 'googleadsWG'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('slot')); ?>" name="<?php echo esc_attr($this->get_field_name('slot')); ?>" type="text" value="<?php echo esc_attr($slot); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Select Ads Size', 'googleadsWG'); ?></label>
    <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">
      <?php
      // Your options array
      $options = array(
        '' => '-- select option --',
        'auto' => 'auto',
        '120x240' => '120 x 240',
        '120x600' => '120 x 600',
        '125x125' => '125 x 125',
        '160x600' => '160 x 600',
        '180x150' => '180 x 150',
        '200x200' => '200 x 200',
        '234x60' => '234 x 60',
        '240x400' => '240 x 400',
        '250x250' => '250 x 250',
        '250x360' => '250 x 360',
        '300x1050' => '300 x 1050',
        '300x250' => '300 x 250',
        '300x600' => '300 x 600',
        '320x100' => '320 x 100',
        '320x50' => '320 x 50',
        '336x280' => '336 x 280',
        '468x60' => '468 x 60',
        '580x400' => '580 x 400',
        '728x90' => '728 x 90',
        '750x100' => '750 x 100',
        '750x200' => '750 x 200',
        '750x300' => '750 x 300',
        '930x180' => '930 x 180',
        '970x250' => '970 x 250',
        '970x90' => '970 x 90',
        '980x120' => '980 x 120'
      );

      // Loop through options and add each one to the select dropdown
      foreach ($options as $key => $name) {
        echo '<option value="' . esc_attr($key) . '" id="' . esc_attr($key) . '" ' . selected($select, $key, false) . '>' . $name . '</option>';
      } ?>
    </select>
  </p>
  <p>
    <label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Style for Ads e.g "<small>width: 250px; height:250px;</small>"', 'googleadsWG'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>" type="text" value="<?php echo esc_attr($text); ?>" />
  </p>
  <p>
    <input id="<?php echo esc_attr($this->get_field_id('checkbox')); ?>" name="<?php echo esc_attr($this->get_field_name('checkbox')); ?>" type="checkbox" value="1" <?php checked('1', $checkbox); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('checkbox')); ?>"><?php _e('Responsive Ads', 'googleadsWG'); ?></label>
  </p>

<?php }

// Update widget settings
public function update($new_instance, $old_instance)
{
  $instance = $old_instance;
  $instance['title']    = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
  $instance['slot']    = isset($new_instance['slot']) ? wp_strip_all_tags($new_instance['slot']) : '';
  $instance['text']     = isset($new_instance['text']) ? wp_strip_all_tags($new_instance['text']) : '';
  $instance['select']   = isset($new_instance['select']) ? wp_strip_all_tags($new_instance['select']) : '';
  $instance['checkbox'] = isset($new_instance['checkbox']) ? 1 : false;
  return $instance;
}

// Display the widget
public function widget($args, $instance)
{

  extract($args);

  // Check the widget options
  $title  = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
  $slot   = isset($instance['slot']) ? apply_filters('widget_title', $instance['slot']) : '';
  $text   = isset($instance['text']) ? $instance['text'] : '';
  $select = isset($instance['select']) ? $instance['select'] : '';
  $checkbox = !empty($instance['checkbox']) ? $instance['checkbox'] : false;

  // Display the widget

  $format_auto = '';
  $data_ad_width = '';
  $data_ad_height = '';
  $code_style = '';
  $data_full_width_responsive = '';

  if ($text) {
    $code_style = $text;
  }
  if ($checkbox) {
    $data_full_width_responsive = 'data-full-width-responsive="true"';
  }

  if ($select) {
    if ($select == 'auto') {
      $format_auto = 'data-ad-format="auto"';
    } else {
      $select = explode('x', $select);
      $data_ad_width = 'data-ad-width="' . $select[0] . '"';
      $data_ad_height = 'data-ad-height="' . $select[1] . '"';
    }
  }


  if ($title) {
    echo '
        <ins class="adsbygoogle"
            style="display:block;' . $code_style . '"
            data-ad-client="' . $title . '"
            data-ad-slot="' . $slot . '"
            ' . $format_auto . '
            ' . $data_ad_width . '
            ' . $data_ad_height . '
            ' . $data_full_width_responsive . '></ins>
        ';
  }
}
}

// Register the widget
function googleadsWG_register_custom_widget()
{
  register_widget('Google_Ads');
}
add_action('widgets_init', 'googleadsWG_register_custom_widget');


function googleadsWG_register_custom_enqueue_scripts()
{
  $script = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script>[].forEach.call(document.querySelectorAll(".adsbygoogle"), function(){(adsbygoogle = window.adsbygoogle || []).push({});  });</script>';
  echo $script;
}
add_action('wp_footer', 'googleadsWG_register_custom_enqueue_scripts');
