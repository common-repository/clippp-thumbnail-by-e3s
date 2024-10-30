<?php

add_action('get_footer', function($content) {
    do_action('clippp_thumbnail');
}, 100, 1);

add_filter('widget_text', function($text) {
   var_dump($text);
   return $text;
});

include get_page_template();