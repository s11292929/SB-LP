<?php
/**
 * SoftBank 光 フォーム送信処理プログラム
 */

// ---------------------------------------------------------
// 設定
// ---------------------------------------------------------

// 通知先メールアドレス
$to = "sakai_tatunori@appdate-hd.co.jp"; // 通知先メールアドレス

// メール件名
$subject = "【SoftBank 光】Webサイトよりお問い合わせがありました";

// ---------------------------------------------------------
// 送信処理
// ---------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // フォームデータの取得
    $current_zip   = isset($_POST['current_zip']) ? $_POST['current_zip'] : "";
    $current_pref  = isset($_POST['current_pref']) ? $_POST['current_pref'] : "";
    $current_addr  = isset($_POST['current_addr']) ? $_POST['current_addr'] : "";
    $move_zip      = isset($_POST['move_zip']) ? $_POST['move_zip'] : "";
    $move_pref     = isset($_POST['move_pref']) ? $_POST['move_pref'] : "";
    $move_addr     = isset($_POST['move_addr']) ? $_POST['move_addr'] : "";
    $move_month    = isset($_POST['move_month']) ? $_POST['move_month'] : "";
    $move_day      = isset($_POST['move_day']) ? $_POST['move_day'] : "";
    $name          = isset($_POST['name']) ? $_POST['name'] : "";
    $kana          = isset($_POST['kana']) ? $_POST['kana'] : "";
    $tel           = isset($_POST['tel']) ? $_POST['tel'] : "";
    $email         = isset($_POST['email']) ? $_POST['email'] : "";
    $contact_day   = isset($_POST['contact_day']) ? $_POST['contact_day'] : "";
    $contact_time  = isset($_POST['contact_time']) ? $_POST['contact_time'] : "";
    $message       = isset($_POST['message']) ? $_POST['message'] : "";

    // メール本文の作成
    $body = "Webサイトのフォームより、以下の内容でお申し込み・お問い合わせがありました。\n\n";
    $body .= "--------------------------------------------------\n";
    $body .= "■ 現住所\n";
    $body .= "郵便番号: " . $current_zip . "\n";
    $body .= "都道府県: " . $current_pref . "\n";
    $body .= "市区町村・番地: " . $current_addr . "\n\n";
    
    $body .= "■ 引越し先住所\n";
    $body .= "郵便番号: " . $move_zip . "\n";
    $body .= "都道府県: " . $move_pref . "\n";
    $body .= "市区町村・番地: " . $move_addr . "\n\n";
    
    $body .= "■ 引越し予定日\n";
    $body .= "予定時期: " . $move_month . "月 " . $move_day . "日頃\n\n";
    
    $body .= "■ お客様情報\n";
    $body .= "お名前: " . $name . "\n";
    $body .= "フリガナ: " . $kana . "\n";
    $body .= "電話番号: " . $tel . "\n";
    $body .= "メールアドレス: " . $email . "\n\n";
    
    $body .= "■ ご連絡希望日時\n";
    $body .= "希望曜日: " . $contact_day . "\n";
    $body .= "希望時間帯: " . $contact_time . "\n\n";
    
    $body .= "■ お問い合わせ内容\n";
    $body .= $message . "\n";
    $body .= "--------------------------------------------------\n\n";
    
    $body .= "送信日時: " . date("Y/m/d H:i:s") . "\n";
    $body .= "送信元IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

    // 言語・エンコード設定
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    // ISO-2022-JP に変換（日本で最も一般的なメール形式）
    $subject_jp = mb_encode_mimeheader($subject, "ISO-2022-JP");
    $body_jp = mb_convert_encoding($body, "ISO-2022-JP", "UTF-8");

    // メールヘッダーの作成
    $headers = "From: " . mb_encode_mimeheader("SoftBank 光 フォーム", "ISO-2022-JP") . " <no-reply@ht-connection.co.jp>\n";
    $headers .= "Reply-To: " . $email . "\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
    $headers .= "Content-Transfer-Encoding: 7bit";

    // メール送信（管理者宛）
    mail($to, $subject_jp, $body_jp, $headers);

    // ---------------------------------------------------------
    // 自動返信メールの送信（ユーザー宛）
    // ---------------------------------------------------------
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $auto_reply_subject = "【SoftBank 光】お問い合わせありがとうございました";
        $auto_reply_body = $name . " 様\n\n";
        $auto_reply_body .= "この度は、サイトよりお問い合わせいただき、誠にありがとうございます。\n";
        $auto_reply_body .= "以下の内容で受付いたしました。\n\n";
        $auto_reply_body .= "内容を確認の上、担当者より改めてご連絡させていただきますので、\n";
        $auto_reply_body .= "恐れ入りますが、今しばらくお待ちくださいませ。\n\n";
        $auto_reply_body .= "--------------------------------------------------\n";
        $auto_reply_body .= "■ お問い合わせ内容の控え\n";
        $auto_reply_body .= "--------------------------------------------------\n";
        $auto_reply_body .= "お名前: " . $name . " 様\n";
        $auto_reply_body .= "電話番号: " . $tel . "\n";
        $auto_reply_body .= "ご連絡希望時間帯: " . $contact_time . "\n";
        $auto_reply_body .= "メッセージ: \n" . $message . "\n";
        $auto_reply_body .= "--------------------------------------------------\n\n";
        $auto_reply_body .= "※本メールはシステムによる自動送信です。\n";
        $auto_reply_body .= "※お心当たりのない場合は、誠に恐縮ですが本メールの破棄をお願いいたします。\n\n";
        $auto_reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $auto_reply_body .= "光引越しナビ\n";
        $auto_reply_body .= "0120-000-000\n";
        $auto_reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $auto_reply_subject_jp = mb_encode_mimeheader($auto_reply_subject, "ISO-2022-JP");
        $auto_reply_body_jp = mb_convert_encoding($auto_reply_body, "ISO-2022-JP", "UTF-8");
        
        $auto_reply_headers = "From: " . mb_encode_mimeheader("光引越しナビ", "ISO-2022-JP") . " <no-reply@ht-connection.co.jp>\n";
        $auto_reply_headers .= "MIME-Version: 1.0\n";
        $auto_reply_headers .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
        $auto_reply_headers .= "Content-Transfer-Encoding: 7bit";

        mail($email, $auto_reply_subject_jp, $auto_reply_body_jp, $auto_reply_headers);
    }

    // サンクスページへリダイレクト
    header("Location: thanks");
    exit;

} else {
    // 直接アクセスされた場合はトップページへ
    header("Location: ./");
    exit;
}
?>
