<aside>
    <div id="contact-aria" class="panel-grid-cell">
        <h2 class="widget-title">Contact</h2>
        
        <form id="contact-form" class="row text-center" method="post">
            
            <div class="form-group" autocomplete="on">
                <label for="name-input">お名前</label>
                <input id="name-input" type="text" class="form-control" name="contact[name]" value="" placeholder="山田　太郎">
            </div>
            
            <div class="form-group" autocomplete="on">
                <label for="email-input">メールアドレス</label>
                <input id="email-input" type="email" class="form-control" name="contact[email]" value="" placeholder="example@clippp.tokyo">
            </div>
            
            <div class="form-group">
                <label far="wedding-date-input">挙式日</label>
                <input id="wedding-date-input" type="date" class="form-control" name="contact[wedding_date]" value="<?php echo date_i18n('Y-m-d'); ?>" min="<?php echo date_i18n('Y-m-d'); ?>" max="<?php echo date_i18n('Y-m-d', $_SERVER['REQUEST_TIME'] + 86400 * 365 * 3); ?>" required>
            </div>
            
            <div class="form-group">
                <label far="contact-content">お問い合わせ内容</label>
                <textarea id="contact-content" class="form-control" name="contact[content]" placeholder="結婚します！" rows="10"></textarea>
            </div>
            
            <input type="hidden" name="action" value="contact_submit">
            
            <button class="roll-button border">送信</button>
            
        </form>

    </div>
</aside>

<script>
window.jQuery(function($) {
    $("#contact-form").on("submit", function(e) {
        var b = $(this).find("button.border"), c = b.html();
        e.preventDefault();
        b.html('<i class="fa fa-spinner fa-pulse"></i>');
        $.ajax("<?php echo admin_url( 'admin-ajax.php'); ?>", {
            type: "POST",
            data: $(this).serialize()
        })
        
        .done(function(data) {
            b.html(c);
            if (data.msg) alert(data.msg);
        })
    })
})
</script>