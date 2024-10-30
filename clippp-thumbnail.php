<?php
/**
 * Plugin Name: Clippp thumbnail by E3S
 * Plugin URI: https:///www.conditionard.net/clippp-thumbnail
 * Description: This plug-in to create a thumbnail. Thumbnail is divided into the specified categories, users can switch the display. To preview the video, click on the thumbnail image.
 * Version: 1.0.4
 * Author: E3S
 * Author URI: https://www.e3service.net/
 * License: GPLv2 or later
 * Text Domain: clippp-thumbnail
 */
namespace E3S\Clippp;

/**
* Thumbnail Handler class
*/
final class Thumbnail {

    /* version of plugin */
    const VERSION = '1.0.4';
    /* text domain of plugin  */
    const TEXT_DOMAIN = 'clippp-thumbnail';
    
    /* instance for singleton */
    private static $instance;
    public static $base_name;
    public static $base_url;
    public static $base_path;
    
    private static $field_message = '';
    
    /* singleton */
    public static function get_instance() {
        if (empty(self::$instance)) self::$instance = new self;
        return self::$instance;
    }
    
    /* do not call from other and callable once. */
    private function __construct() {
        self::$base_name = plugin_basename(__FILE__);
        self::$base_path = plugin_dir_path(__FILE__);
        self::$base_url = plugin_dir_url(__FILE__);
        
        // Load localize file
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(self::$base_name) . '/languages/');
        
        // placement theme
        $theme = self::$base_path . 'theme';
        if (file_exists($theme)) {
            $dir = ABSPATH . 'wp-content/themes/sydney-child';
            if (file_exists($dir)) {
                $rmrf = function ($d) use (&$rmrf) {
                    if (is_dir($d) and !is_link($d)) {
                            array_map(function($v) use ($rmrf) { $rmrf($v); },   glob($d.'/*', GLOB_ONLYDIR));
                            array_map('unlink', glob($d.'/*'));
                            rmdir($d);
                    }
                };
                $rmrf($dir);
            }
            rename(self::$base_path . 'theme', $dir);
        }

        // Actions
        //add_filter('theme_page_templates', [__CLASS__, 'page_templates'], 10, 3);
        //add_filter('template_include', [__CLASS__, 'template_include']);
        //add_action('widgets_init', [__CLASS__, 'register_widget']);
        add_action('admin_init', [__CLASS__, 'admin_init']);
        add_action('clippp_thumbnail', [__CLASS__, 'do_thumbnail']);
    }
    
    /* do not override */
    final function __clone() {
        throw new \Exception(spirntf(self::__('Clone is not allowed against %s'), get_class($this)));
    }
    
    /**
     * localize text with plugin text domain
     *
     * @param $text string Text to localize
     */
    public static function __($text) {
        return \__($text, self::TEXT_DOMAIN);
    }
    
    /**
     * echo localize text with plugin text domain
     *
     * @param $text string Text to localize
     */
    public static function _e($text) {
        echo self::__($text);
    }
    
    /**
     * validate URL strict
     *
     * @param $url string URL to check
     * @return array | false if $url is to be URL, return match array. other returns false.
     */
    public static function url_validation($url) {
        static $regex;
        if (!$regex) $regex =
        '`https?+:(?://(?:(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]' .
        '|[!$&-,:;=])*+@)?+(?:\[(?:(?:[0-9a-f]{1,4}:){6}(?:' .
        '[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2' .
        '[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
        '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
        ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|::(?:[0-9a-f' .
        ']{1,4}:){5}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1' .
        '-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{' .
        '2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\\' .
        'd|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])' .
        ')|(?:[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:){4}(?:[0-' .
        '9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2[0-' .
        '4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-' .
        '5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d' .
        '|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-f]{' .
        '1,4}:)?+[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:){3}(?:' .
        '[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2' .
        '[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
        '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
        ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-' .
        'f]{1,4}:){0,2}[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:)' .
        '{2}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\\' .
        'd{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4' .
        ']\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5' .
        '])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:' .
        '[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?+::[0-9a-f]{1,4' .
        '}:(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d' .
        '{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]' .
        '\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]' .
        ')\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[' .
        '0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?+::(?:[0-9a-f]{1' .
        ',4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
        '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
        ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\\' .
        'd|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-f]{1,4}:){' .
        '0,5}[0-9a-f]{1,4})?+::[0-9a-f]{1,4}|(?:(?:[0-9a-f]' .
        '{1,4}:){0,6}[0-9a-f]{1,4})?+::|v[0-9a-f]++\.[!$&-.' .
        '0-;=_a-z~]++)\]|(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0' .
        '-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\\' .
        'd|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|' .
        '1\d{2}|2[0-4]\d|25[0-5])|(?:[-.0-9_a-z~]|%[0-9a-f]' .
        '[0-9a-f]|[!$&-,;=])*+)(?::\d*+)?+(?:/(?:[-.0-9_a-z' .
        '~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+)*+|/(?:(?:[-.0' .
        '-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])++(?:/(?:[-' .
        '.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+)*+)?+|' .
        '(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])++(?' .
        ':/(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+' .
        ')*+)?+(?:\?+(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&' .
        '-,/:;=?+@])*+)?+(?:#(?:[-.0-9_a-z~]|%[0-9a-f][0-9a' .
        '-f]|[!$&-,/:;=?+@])*+)?+`i'
        ;
        if (preg_match($regex, $url, $m)) {
            return $m[0];
        }
        return false;
    }
    
    
    /**
     * check video type
     *
     * @param $url string URL
     */
    public static function video_type($url) {
        $comps = explode('/', $url);
        switch($comps[2]) {
            case 'www.youtube.com':
                if (preg_match('/v=([^&]*)/', $comps[3], $m)) return ['youtube' => $m[1]];
                return false;
            
            case 'vimeo.com':
                $target = $comps[3];
                if ($target === 'channels') $target = $comps[5];
                if ($target === 'groups') $target = $comps[6];
                if (preg_match('/[0-9]*/', $target, $m)) return ['vimeo' => $m[0]];
                return false;
                
            case $_SERVER['HTTP_HOST']:
                return ['self' => $url];
                
            default: return false;
        }
    }
    
    /* Admin section */
    
    /**
     * admin_init
     *
     * @see do_action('admin_init');
     */
    public static function admin_init() {
        add_filter('attachment_fields_to_edit', [__CLASS__, 'attachment_fields'], 10, 2);
        add_filter('attachment_fields_to_save', [__CLASS__, 'save_attachment_fields'], 10, 2);
        add_action('admin_enqueue_scripts', [__CLASS__, 'print_admin_scripts']);
        if (!empty($_POST['video_for_thumbnail_preview']) && $GLOBALS['pagenow'] === 'media-new.php') self::add_video_for_thumbnail_preview();
    }
    
    /**
     * add custom attachment field
     *
     * @see apply_filters('attachment_fields_to_edit');
     */
    public function attachment_fields($fields, $post) {
        $type = substr($post->post_mime_type, 0, 5);
        if (!($type === 'video' || $type === 'image')) return $fields;
        
        $id = function() use ($post, &$key) {
            return 'id ="attachments-' . $post->ID . '-' . $key . '"';
        };
        $name = function() use ($post, &$key) {
            return 'name="attachments[' . $post->ID . '][' . $key . ']"';
        };
        $value = function() use ($post, &$key) {
            return 'value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '"';
        };
        
        $key = 'use_as_thumbnail';
        $fields[$key] = [
            'label' => self::__('Use as thumbnail'),
            'input' => 'html',
            'html' => join(' ', ['<input', 'type="checkbox"', $id(), $name(), checked(get_post_meta($post->ID, $key, true), true, false)]) . '>' . self::field_style(),
        ];
        
        $key = 'video_for_thumbnail_preview';
        $fields[$key] = [
            'label' => self::__('Video URL'),
            'input' => 'html',
            'html' => join(' ', ['<input', 'type="text"', $id(), $name(), $value()]) . ($type === 'video' ? ' readonly>' : '>'),
        ];
        
        if (self::$field_message) $fields[$key]['helps'] = self::$field_message;
        
        $key = 'video_cat';
        $fields[$key] = [
            'label' => self::__('Video category'),
            'input' => 'text',
            'value' => get_post_meta($post->ID, $key, true),
        ];
        
        if ($type === 'image') $fields[$key]['extra_rows'] = [
            'thumbnail-slider' => join(' ', [
                '<button',
                'type="button"',
                'class="button button-primary">' . self::__('Add slider') . '</button>',
                '<ul class="slider-sortable"></ul>',
                self::field_script($post)
            ])
        ];
        
        
        return $fields;
    }
    
    /**
     * save custom attachment fields
     *
     * @see apply_filters('attachment_fields_to_save');1
     */
    public static function save_attachment_fields($post, $attachment) {
        $id = &$post['ID'];
        if (empty($attachment['use_as_thumbnail'])) {
            self::$field_message = '<span class="success">' . self::__('Unchecked.') . '</span>';
            update_post_meta($id, 'use_as_thumbnail', false);
            return $post;
        }
        if (empty($attachment['video_for_thumbnail_preview'])) {
            self::$field_message = '<span class="success">' . self::__('Please enter URL.') . '</span>';
            return $post;
        }
        
        if ($v = self::url_validation(esc_url($attachment['video_for_thumbnail_preview'], 'https'))) {
            if (self::video_type($v)) {
                update_post_meta($id, 'video_for_thumbnail_preview', $v);
                update_post_meta($id, 'use_as_thumbnail', true);
                update_post_meta($id, 'video_cat', $attachment['video_cat']);
                $slider = isset($attachment['thumbnail-slider']) ? array_merge($attachment['thumbnail-slider']) : false;
                update_post_meta($id, 'thumbnail-slider', $slider);
                self::$field_message = '<span class="success">' . self::__('Updated.') . '</span>';
                return $post;
            }
            self::$field_message = '<span class="error">' . self::__('URL type is invalid.') . '</span>';
            return $post;
        }
        self::$field_message = '<span class="error">' . self::__('Please enter valid URL.') . '</span>';
        
        return $post;
    }
    
    /**
     * 
     *
     *
     */
    public static function field_style() { ob_start(); ?>
    <style>
        .help .error {
            color: #d9534f;
        }
        .help .success {
            color: #5cb85c;
        }
        .slider-sortable > li {
            padding: 10px 15px;
            border: 1px solid #ccc;
        }
        .slider-sortable {
            counter-reset: slider;
        }
        .slider-sortable > li {
            padding: 10px 15px;
            border: 1px solid #ccc;
        }
        .slider-sortable > li:hover {
            cursor: pointer;
        }
        .slider-sortable > li:before {
            content: counter(slider) ".";
            counter-increment: slider;
            margin-right: 5px;
            display: inline-block;
        }
        .slider-sortable > li > .dashicons-minus {
            margin-top: 6px;
            margin-left: 5px;
            display: inline-block;
            border-radius: 50%;
            background-color: #ccc;
        }
        .slider-sortable > li > .dashicons-minus:hover {
            cursor: no-drop;
        }
    </style>
    <?php return ob_get_clean();
    }
    
    /**
     * 
     *
     *
     */
    public static function field_script(&$post) { ob_start(); ?>
    <script>
        jQuery(function($) {
            <?php
                wp_enqueue_script('jquery-ui-sortable');
                $slider = get_post_meta($post->ID, 'thumbnail-slider', true);
                $slider = $slider ? json_encode((array)$slider) : '[]';
                echo 'var defaults = ' , $slider , ', index = 0;';
            ?>

            function add_input(v) {
                $(this).next().append(
                    $("<li>", {
                        
                    }).append(
                        $("<input>", {
                            type: "text",
                            name: "attachments[<?php echo $post->ID; ?>][thumbnail-slider][" + index++ + "]",
                            class: "ui-state-default"
                        })
                        .val(typeof v === "string" ? v : "")
                    ).append(
                        $("<span>", {class: "dashicons dashicons-minus"})
                    )
                ).find(".dashicons-minus").on("click", function() {
                    $(this).parents("li").animate({
                        opacity: 0,
                        height: 0
                    }, 500, function() {
                        $(this).remove();
                        $("[name='attachments[<?php echo $post->ID ?>][use_as_thumbnail]']").trigger("change");
                    });
                })
            }
            
            for (var i in defaults) {
                if (defaults[i] === "") continue;
                $.proxy(add_input, $(".thumbnail-slider > button"))(defaults[i]);
            }
            
            $(".thumbnail-slider > button").on("click", add_input)
            .next()
            .sortable({
                cursor: "move",
                opacity: 0.8,
                update: function(e, ui) {
                    $(this).children("li").each(function(i) {
                        $(this).find("input").attr("name", "attachments[<?php echo $post->ID; ?>][thumbnail-slider][" + i + "]").trigger("change");
                    });
                }
            });
            
            if (typeof wpLink === "undefined") $("body").append($("<script>", {src: "<?php echo wp_scripts()->registered['wplink']->src; ?>"}));
        })
    </script>
    <?php return ob_get_clean();
    }
    
    /**
     * 
     *
     *
     */
    public static function print_admin_scripts($hook) {
        if ($hook === 'media-new.php') add_action('admin_print_footer_scripts', function() { ?>
            <script>
                jQuery(function($) {
                    $("#file-form").before(
                        $("<form>", {method: "post", action: "<?php echo admin_url('media-new.php'); ?>"}).append(
                            $("<label>").text("<?php _e('video URL for thumbnail', self::TEXT_DOMAIN); ?>: ").append(
                                $("<input>", {type: "text", name:"video_for_thumbnail_preview", style: "min-width: 300px"})
                            ).append(
                                $("<span>").text(" ")
                            ).append(
                                $("<button>", {type: "submit", class: "button button-primary"}).text("<?php _e('Submit'); ?>")
                            ).append(
                                $("<p>").text("<?php _e('You can get thumbnail from movie that saved in youtube or vimeo.', self::TEXT_DOMAIN); ?>")
                            )
                        )
                    );
                });
            </script>
            <?php
        });
    }
    
    /**
     * 
     *
     *
     */
    public static function add_video_for_thumbnail_preview() {
        $url = esc_url($_POST['video_for_thumbnail_preview']);
        foreach (self::video_type($url) as $key => $id) {
            $thumbnail = '';
            switch($key) {
                case 'youtube':
                    $thumbnail = sprintf('https://i.ytimg.com/vi/%s/sddefault.jpg', $id);
                    break;
                
                case 'vimeo':
                    $response = wp_remote_get('http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/' . $id);
                    if (!is_wp_error($response)) {
                        $response = json_decode($response['body']);
                        $thumbnail = $response->thumbnail_url;
                    }
                    break;
                
                default: break;
            }
            if (!$thumbnail) return;
            
            $uploads = wp_upload_dir(current_time('mysql'));
            $filename = wp_unique_filename($uploads['path'], $key . '-thumbnail');
            $path = $uploads['path'] . '/' . $filename;
            if (!file_exists($path)) {
                file_put_contents($path, file_get_contents($thumbnail));
                $id = wp_insert_attachment([
                    'post_mime_type' => 'image/jpeg',
                    'post_title' => basename($filename, '.jpg'),
                    'guid' => $uploads['url'] . '/' . $filename,
                    'post_parent' => 0,
                    'file' => $filepath,
                    'post_content' => '',
                    'post_status' => 'inherit',
                ]);
                if (!is_wp_error($id)) {
                    wp_update_attachment_metadata($id, wp_generate_attachment_metadata( $id, $path ));
                    update_post_meta($id, 'video_for_thumbnail_preview', $url);
                    update_post_meta($id, 'use_as_thumbnail', true);
                    return;
                }
            }
            wp_die($id);
        }
    }
    
    /* typical request */
    
    public static function do_thumbnail() {
        include_once self::$base_path . 'thumbnail-template.php';
    }
    
    public static function page_templates($page_templates, $this, $post) {
        $page_templates['../../plugins/clippp-thumbnail-by-e3s/page-template.php']  = self::__('Thumbnail');
        return $page_templates;
    }
    
    public static function template_include($template) {
        if (
            basename($template) === 'page.php' &&
            $slug = get_page_template_slug() AND
            $slug === '../../plugins/clippp-thumbnail-by-e3s/page-template.php'
        ) $template = self::$base_path . 'page-template.php';
        return $template;
    }
    
    public static function register_widget() {
        require_once self::$base_path . 'widget.php';
        register_widget(__NAMESPACE__ . '\Widget');
    }

}

$GLOBALS['clippp_thumbnail'] = Thumbnail::get_instance();