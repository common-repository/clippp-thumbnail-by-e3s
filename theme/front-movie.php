<div id="front-movie" class="text-center">
    
    <div class="player">
        <video src="<?php echo get_stylesheet_directory_uri() . '/front-movie.mp4'; ?>" autoplay="autoplay" loop="loop" muted="muted"></video>
    </div>
    
    <div class="title"><?php bloginfo('name'); ?></div>
    <a href="#content" class="toc">start</a>
    
</div>

<script>
    
    window.jQuery(function($) {
        "use strict";
        var a = $("#front-movie"), b = $("#masthead"), c, d, e;
        d = b.offset().top;
        e = parseInt(a.find(".player").css("margin-top"));
        $(window).on("scroll", function() {
            c = $(this).scrollTop();
            if (b.hasClass("fixed"))
                a.parent().css("margin-top", "").end()
                .css("height", "")
                .find(".player").css({
                    "margin-top": "",
                    "margin-bottom": "",
                })
                .siblings(".toc").css({
                    "margin-bottom": "",
                })
            else
                a.parent().css("margin-top", c >= d ? "" : c).end()
                .css({
                    height: c >= d ? "" : (((d - c) / d * 100) + "%")
                })
                .find(".player").css({
                    "margin-top": e - (e / d * c),
                    "margin-bottom": e - (e / d * c),
                })
                .siblings(".toc").css({
                    "margin-bottom": e - (e / d * c),
                })
            })
    })
    
</script>
