<aside class="page-wrap">
    <div id="primary-aria" class="panel-grid-cell">
        <h2 class="widget-title">Posts</h2>
    	<div id="primary" class="content-area <?php echo sydney_blog_layout(); ?>">
    		<main id="main" class="like-clippp-thumbnail-wrapper"  class="post-wrap" role="main">
                
                <?php
                
                $r = new WP_Query( array(
            		'no_found_rows'       => true,
            		'post_status'         => 'publish',
            		'posts_per_page'	  => -1,
            	) );
            	
            	$cat_count = 0;
                $cats = [];
                $boxes = '';

            	while ($r->have_posts()) : $r->the_post();
            	
            	$cat = array_map(function($v) { return $v->name; }, get_the_category());
            	if (empty($cat)) $cat = $GLOBALS['clippp_thumbnail']::__('Other');
            	foreach($cat as $c) {
            	    if (empty($cats[$c])) $cats[$c] = 0;
            	    ++$cats[$c];
            	}
            	++$cat_count;
            	$cat = join(',', $cat);
            	
            	
            	
            	$boxes .= '<' . join(' ', [
                    'li',
                    'class="' . join(' ', explode(',', strpos($cat, ':') === false ? $cat : explode(':', $cat)[0])) . '"',
                    'tabindex="0"',
                    'role="checkbox"',
                    'aria-label="' . get_the_title() . '"',
                    'aria-checked="false"',
                    'data-content="' . get_the_excerpt() . '"',
                    'data-href="' . get_the_permalink() . '"',
                ]) . '><div>';
                
                if (has_post_thumbnail()) {
                    $boxes .= get_the_post_thumbnail();
                } else {
                    $boxes .= '<img src="http://www.oradoko.jp/gourmet/images/noimage.jpg" alt="準備中">';
                }
                
                $boxes .= '</div></li>';
                
                ?>
                
                <?php endwhile; ?>
                
                <header>
                    <input type="radio" name="clippp-post-cat" id="clippp-posts-cat-all" value="all">
                    <label for="clippp-posts-cat-all" data-count="<?php echo $cat_count; ?>">All</label>
                    <?php foreach ($cats as $cat => $count) : ?>
                    <?php $id_base = strpos($cat, ':') === false ? $cat : explode(':', $cat)[0]; ?>
                    <input type="radio" name="clippp-post-cat" id="clippp-posts-cat-<?php echo $id_base; ?>">
                    <label for="clippp-posts-cat-<?php echo $id_base ; ?>" data-count="<?php echo $count ?>"><?php echo strpos($cat, ':') === false ? $cat : call_user_func(function($arr) {
                        $arr = array_map(function($v) { return '<span>' . $v . '</span>'; }, $arr);
                        for ($i = count($arr) - 1; $i > 0; --$i) {
                            $arr[$i-1] = preg_replace('/(<span>[^<]*)(<\/span>)/', '$1' . $arr[$i] . '$2', $arr[$i-1]);
                        }
                        echo $arr[0];
                    }, explode(':', $cat)); ?></label>
                    <?php endforeach; ?>
                </header>
                
                <ul class="like-clippp-thumbnail" data-per-row="4">
                    
                    <?php echo $boxes; ?>
                    
                </ul>

    		</main><!-- #main -->
    	</div><!-- #primary -->
    </div>
</aside>