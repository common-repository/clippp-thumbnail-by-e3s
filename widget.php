<?php
namespace E3S\Clippp;

class Widget extends \WP_Widget {
    
    public function __construct() {
        global $clippp_thumbnail;
        parent::__construct('clippp-thumbnail', 'Clippp thumbnail', [
            'classname' => 'widget_clippp-thumbnail',
            'description' => $clippp_thumbnail::__('Insert thumbnail.'),
            'customize_selective_refresh' => true,
        ]);
    }
    
    public function widget($args, $instance) {
        
        $instance = wp_parse_args((array)$instance, [
            'padding_top' => '0',
            'padding_left' => '0',
            'padding_right' => '0',
            'padding_bottom' => '0',
        ]);
        $padding='padding: ' . $instance['padding_top'] . 'px ' .$instance['padding_right'] . 'px ' . $instance['padding_bottom'] . 'px ' . $instance['padding_left'] . 'px;';
        
        add_filter('clippp_thumbnail_style', function($style) use ($padding) {
            return $padding . $style;
        });
        do_action('clippp_thumbnail');

    }
    
    public function form($instance) {
        global $clippp_thumbnail;
        $options = [
            'padding_top' => empty($instance['padding_top']) ? '0' : $instance['padding_top'],
            'padding_left' => empty($instance['padding_left']) ? '0' : $instance['padding_left'],
            'padding_right' => empty($instance['padding_right']) ? '0' : $instance['padding_right'],
            'padding_bottom' => empty($instance['padding_bottom']) ? '0' : $instance['padding_bottom'],
        ];
        foreach ($options as $name => $value) {
            echo
            '<p><label>',
            $clippp_thumbnail::__($name),
            ' <input ',
            'type="number" min="0" max="300" ',
            'name="', $this->get_field_name($name), '"',
            'id="', $this->get_field_id($name), '"',
            'value="', $value, '"> px',
            '</label></p>';
        }
    }
    
    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
    
}