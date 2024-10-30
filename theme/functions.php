<?php
add_action('wp_enqueue_scripts', function() {
    $styles = wp_styles();
    $styles->registered['sydney-style']->src = get_template_directory_uri() . '/style.css';
    wp_enqueue_style('sydney-child-style', get_stylesheet_directory_uri() . '/style.css', ['sydney-style'], $GLOBALS['clippp_thumbnail']::VERSION);
}, 100);

function contact_submit() {
    $msg = [];
    extract($_POST['contact']);
    
    if (!$name) $name = '名称未設定';
    $msg[] = $name . ' 様';
    
    $email_validation = is_email($email);
    $msg[] = $email_validation ?
        'お問い合わせありがとうございます。' . $email . '宛に確認メッセージを送信しました。' :
        'メールアドレスの形式が正しくありません。';
    
    if ($email_validation) {
        
        $email_msg = [$name . ' 様'];
        $email_msg[] = 'この度はお問い合わせいただきまして、誠にありがとうございます。';
        $email_msg[] = '以下の内容で受け付けを完了いたしましたので、ご確認ください。';
        $email_msg[] = '';
        $email_msg[] = '誤り、修正等ございましたら、本メールにその旨をご返信頂ければ対応いたしますので、お申し付けください。';
        $email_msg[] = 'お問い合わせ番号: ' . $_SERVER['REQUEST_TIME'];
        $email_msg[] = 'メールアドレス: ' . $email;
        $email_msg[] = '挙式日: ' . mysql2date(get_option('date_format'), $wedding_date);
        $email_msg[] = 'お問い合わせ内容: ' . $content;
        $email_msg[] = '';
        $email_msg[] = '追って、担当者の方から直接ご連絡いたしますのでしばらくお待ちくださいますようお願い申し上げます。';
        $email_msg[] = '';
        $email_msg[] = '------';
        $email_msg[] = get_bloginfo('name');
        $email_msg[] = '桑野 徹';
        $email_msg[] = get_bloginfo('admin_email');
        $email_msg[] = '------';
        
        add_action('phpmailer_init', function(&$mailer) {
            $mailer->From = get_bloginfo('admin_email');
            $mailer->FromName = '桑野 徹';
            $mailer->Bcc = get_bloginfo('admin_email');
        });
        
        add_action('wp_mail_failed', function($code, $e_msg, $data) use ($msg) {
            $msg[] = $e_msg;
            var_dump($data);
        }, 10, 3);

        wp_mail($email, 'お問い合わせを受付いたしました。', join("\n", $email_msg));

    } else {
        $msg[] = 'お手数ですが、メールアドレスを確認し、もう一度やり直してください。';
    }
    
    wp_send_json([
        'msg' => join("\n", $msg),
        '_name' => $name,
        '_email' => $email,
        '_date' => $wedding_date,
        '_content' => $content
    ]);
}
add_action( 'wp_ajax_contact_submit', 'contact_submit' );
add_action( 'wp_ajax_nopriv_contact_submit', 'contact_submit' );