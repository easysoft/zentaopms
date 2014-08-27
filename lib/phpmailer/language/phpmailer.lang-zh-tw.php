<?php
/**
* PHPMailer language file: refer to English translation for definitive list
* Simplified Chinese Version
* @author liqwei <liqwei@liqwei.com>
*/

$PHPMAILER_LANG['authenticate'] = 'SMTP 錯誤：登錄失敗。\n請檢查您設置的用戶名密碼是否正確。\n有的系統登錄用戶名是完整的郵箱地址。';
$PHPMAILER_LANG['connect_host'] = 'SMTP 錯誤：無法連接到 SMTP 主機，請確認禪道機器：\n1. 能ping通smtp伺服器。如果不能ping通，請查看網絡狀態，或查看域名解析是否正確，或聯繫網管；\n2. 使用telnet 命令能夠連接到smtp的發信連接埠;\n3. 如果上述步驟都是通的，windows請檢查防火牆和殺毒軟件設置，linux請關閉selnux或者執行"setsebool httpd_can_sendmail true"允許apache可以發信。';
$PHPMAILER_LANG['data_not_accepted'] = 'SMTP 錯誤：數據不被接受。';
//$P$PHPMAILER_LANG['empty_message']        = 'Message body empty';
$PHPMAILER_LANG['encoding'] = '未知編碼: ';
$PHPMAILER_LANG['execute'] = '無法執行：';
$PHPMAILER_LANG['file_access'] = '無法訪問檔案：';
$PHPMAILER_LANG['file_open'] = '檔案錯誤：無法打開檔案：';
$PHPMAILER_LANG['from_failed'] = '發送地址錯誤：';
$PHPMAILER_LANG['instantiate'] = '未知函數調用。';
//$PHPMAILER_LANG['invalid_email']        = 'Not sending, email address is invalid: ';
$PHPMAILER_LANG['mailer_not_supported'] = '發信客戶端不被支持。';
$PHPMAILER_LANG['provide_address'] = '收件人沒有設置郵箱，請到組織視圖中檢查下相應用戶的email設置。';
$PHPMAILER_LANG['recipients_failed'] = 'SMTP 錯誤：收件人地址錯誤，或檢查SMTP服務設置，是否允許發送該郵件地址：';
//$PHPMAILER_LANG['signing']              = 'Signing Error: ';
//$PHPMAILER_LANG['smtp_connect_failed']  = 'SMTP Connect() failed.';
//$PHPMAILER_LANG['smtp_error']           = 'SMTP server error: ';
//$PHPMAILER_LANG['variable_set']         = 'Cannot set or reset variable: ';
?>
