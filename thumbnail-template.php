<?php
$attachments = get_posts([
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'attachment',
    'post_status' => 'any',
    'post_parent' => 'null',
    'meta_query' => [
        [
            'key' => 'use_as_thumbnail',
            'value' => true,
        ],
    ],
]);
$cat_count = 0;
$cats = [];
$boxes = '';
foreach($attachments as $attachment) {
    $cat = get_post_meta($attachment->ID, 'video_cat', true);
    if (empty($cat)) $cat = $GLOBALS['clippp_thumbnail']::__('Other');
    foreach(explode(',', $cat) as $c) {
        if(empty($cats[$c])) $cats[$c] = 0;
        ++$cats[$c];
    }
    ++$cat_count;

    $boxes .= '<' . join(' ', [
        'li',
        'class="' . join(' ', explode(',', strpos($cat, ':') === false ? $cat : explode(':', $cat)[0])) . '"',
        'tabindex="0"',
        'role="checkbox"',
        'aria-label="' . $attachment->post_name . '"',
        'aria-checked="false"',
        'data-video-url="' . get_post_meta($attachment->ID, 'video_for_thumbnail_preview', true) . '"',
    ]) . '>';
    if (substr($attachment->post_mime_type, 0, 5) === 'image') {
        $image = $attachment->guid;
        if ($slider = get_post_meta($attachment->ID, 'thumbnail-slider', true)) $image = $image . ',' . join(',', $slider);
        $boxes .= '<div data-image-url="' . $image . '" class="loading"></div>';
    } else {
        $boxes .= '<div data-m-image-url="' . $attachment->guid . '" class="loading"></div>';
    }
    $boxes .= '</li>';
}
unset($cat, $box);
if (!empty($cats[$GLOBALS['clippp_thumbnail']::__('Other')])) {
    $p = $cats[$GLOBALS['clippp_thumbnail']::__('Other')];
    unset($cats[$GLOBALS['clippp_thumbnail']::__('Other')]);
    $cats[$GLOBALS['clippp_thumbnail']::__('Other')] = $p;
    unset($p);
}
?>
<section id="clippp-thumbnail-wrapper" data-style-src="<?php echo $GLOBALS['clippp_thumbnail']::$base_url . 'css/thumbnail.css' ?>"<?php if ($class = apply_filters('clippp-thumbnail-header-class', '')) echo ' class="' . join(' ', (array)$class) . '"'; ?> style="<?php echo apply_filters('clippp_thumbnail_style', ''); ?>">
    <?php if(empty($cats)) : ?>
    <div class="alert"><p><?php $GLOBALS['clippp_thumbnail']::_e('Clippp thumbnail has nothing to show.'); ?></p></div>
    <?php else : ?>
    <header>
        <input type="radio" name="clippp-thumbnail-cat" id="clippp-thumbnail-cat-all" value="all">
        <label for="clippp-thumbnail-cat-all" data-count="<?php echo $cat_count; ?>"><span>All</span></label>
        <?php foreach ($cats as $cat => $count) : ?>
        <?php $id_base = strpos($cat, ':') === false ? $cat : explode(':', $cat)[0]; ?>
        <input type="radio" name="clippp-thumbnail-cat" id="clippp-thumbnail-cat-<?php echo $id_base; ?>">
        <label for="clippp-thumbnail-cat-<?php echo $id_base ; ?>" data-count="<?php echo $count ?>"><?php echo strpos($cat, ':') === false ? $cat : call_user_func(function($arr) {
            $arr = array_map(function($v) { return '<span>' . $v . '</span>'; }, $arr);
            for ($i = count($arr) - 1; $i > 0; --$i) {
                $arr[$i-1] = preg_replace('/(<span>[^<]*)(<\/span>)/', '$1' . $arr[$i] . '$2', $arr[$i-1]);
            }
            echo $arr[0];
        }, explode(':', $cat)); ?></label>
        <?php endforeach; ?>
    </header>
    <ul id="clippp-thumbnail" data-per-row="4">
        <?php echo $boxes; ?>
    </ul>
    <?php endif; ?>
    <script src="<?php echo $GLOBALS['clippp_thumbnail']::$base_url . 'js/thumbnail.js'; ?>"></script>
</section>