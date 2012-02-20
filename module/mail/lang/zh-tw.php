<?php
$lang->mail->setParam = '請設置下面的配置參數';

$lang->mail->turnon       = '是否打開發信功能';
$lang->mail->fromAddress  = '發信人郵箱';
$lang->mail->fromName     = '發信人名稱';
$lang->mail->mta          = '請選擇發信方式';
$lang->mail->debugExample = '0表示關閉調試信息,1和2表示打開調試信息，但2比1調試信息顯示更詳細';

$lang->mail->mtaList['gmail']    = 'Gmail伺服器方式';
$lang->mail->mtaList['smtp']     = 'SMTP伺服器方式';
$lang->mail->mtaList['phpmail']  = 'PHP內置mail函數';
$lang->mail->mtaList['sendmail'] = '本機sendmail';

/* Trun on email feature or not */
$lang->mail->turnonList['true']  = '打開';
$lang->mail->turnonList['false'] = '關閉';

$lang->mail->debugList[2] = '2';
$lang->mail->debugList[0] = '0';
$lang->mail->debugList[1] = '1';

$lang->mail->smtp->authList['true']  = '是';
$lang->mail->smtp->authList['false'] = '否';

$lang->mail->smtp->secureList['']    = '不加密';
$lang->mail->smtp->secureList['ssl'] = 'ssl';
$lang->mail->smtp->secureList['tls'] = 'tls';

/* Set SMTP */
$lang->mail->smtp->fromName    = '發信人姓名';
$lang->mail->smtp->auth        = '是否需要驗證';
$lang->mail->smtp->debug       = '請選擇調試等級';
$lang->mail->smtp->secure      = '請選擇SMTP加密方式';
$lang->mail->smtp->host        = '請輸入HOST';
$lang->mail->smtp->hostInfo    = '如不是特殊HOST，可不填寫，系統會自動生成';
$lang->mail->smtp->username    = '發信郵箱用戶名';
$lang->mail->smtp->password    = '請輸入密碼';
$lang->mail->smtp->port        = '請輸入連接埠號';
$lang->mail->smtp->portInfo    = 'ssl加密方式預設連接埠號為465，tls加密方式預設連接埠號為587，不加密連接埠號一般為空';
/* Set gmail */
$lang->mail->gmail->username = '請輸入發信郵箱用戶名';
$lang->mail->gmail->password = '請輸入發信郵箱密碼';
$lang->mail->gmail->debug    = '請選擇調試等級';

$lang->mail->confirmSave = '保存成功, 請到您的郵箱中查看測試郵件是否發送成功。';
$lang->mail->subject     = '測試郵件';
$lang->mail->content     = '郵箱設置成功';

/* Save config information */
$lang->mail->configInfo  = '配置信息';
$lang->mail->saveConfig  = '請將該配置信息保存到： ';
$lang->mail->createFile  = '如果zzzemail.php檔案不存在，請手動創建該檔案，將以上配置保存即可。';
