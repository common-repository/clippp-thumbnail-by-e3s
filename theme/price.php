<aside class="page-wrap">
    <div id="price-aria" class="panel-grid-cell" role="list">
        <h2 class="widget-title">Price</h2>

        <a href="#" class="price-box roll-button" role="listitem">
            <hr>
            <span>38,800</span>
        </a>
        <a href="#" class="price-box roll-button" role="listitem">
            <hr>
            <span>68,800</span>
        </a>
        <a href="#" class="price-box roll-button" role="listitem">
            <hr>
            <span>100,800</span>
        </a>
        <a href="#" class="special-price-box roll-button" role="listitem">
            <hr>
            <span>お客様にご用意していただいた映像を<br>一部に使用し、作成いたします。</span>
            <span>18,800~</span>
        </a>

    </div>
</aside>

<script>
    window.jQuery(function($) {
        var f = arguments.callee, s = arguments.callee.style_base || "";
        if (typeof arguments.callee.Init === "undefined") {
            arguments.callee.style = $("<style>").attr("id", "price-box-style").appendTo($("body"));
            arguments.callee.price_box_hr = $(".price-box").find("hr");
            arguments.callee.special_price_box = $(".special-price-box");
            arguments.callee.primary_color = "<?php echo get_theme_mod( 'primary_color', '#d65050' ); ?>";
            s += ".price-box, .special-price-box {";
            s += "border-color: " + arguments.callee.primary_color + ";";
            s += "}";
            //s += ".price-box > hr, .special-price-box > hr, .price-box:hover > span:after, .special-price-box > span:first-child:before, .special-price-box:hover > span:last-child:after {";
            //s += "background-color: " + arguments.callee.primary_color + ";";
            //s += "}";
            s += ".price-box > hr, .price-box > span {";
            s += "line-height: PRICE_BOX_LINE_HEIGHTpx;";
            s += "}"
            s += ".special-price-box > span:last-child {";
            s += "line-height: SPECIAL_PRICE_BOX_LINE_HEIGHTpx;";
            s += "}"
            //s += ".price-box > span, .special-price-box > span {";
            //s += "color: " + arguments.callee.primary_color + ";";
            //s += "}"
            s += ".price-box > span:after, .special-price-box > span:nth-of-type(2):after {";
            s += "color: " + arguments.callee.primary_color + ";";
            s += "}"
            arguments.callee.style_base = s;
            $(window).on("resize", function() { f($); });
            arguments.callee.Init = true;
        }
        
        s = s.replace("PRICE_BOX_LINE_HEIGHT", arguments.callee.price_box_hr.height());
        s = s.replace(
            "SPECIAL_PRICE_BOX_LINE_HEIGHT",
            $(window).width() > 768 ?
            arguments.callee.special_price_box.outerHeight() / 2 : 56
        );

        if (s !== arguments.callee.style.text()) arguments.callee.style.text(s);
    })
</script>