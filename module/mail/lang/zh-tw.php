<?php
$lang->mail->common = '發信配置';
$lang->mail->index  = '首頁';
$lang->mail->detect = '檢測';
$lang->mail->edit   = '編輯配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '測試發信';
$lang->mail->reset  = '重置';

$lang->mail->turnon      = '是否打開';
$lang->mail->fromAddress = '發信郵箱';
$lang->mail->fromName    = '發信人';
$lang->mail->mta         = '發信方式';
$lang->mail->host        = 'smtp伺服器';
$lang->mail->port        = 'smtp連接埠號';
$lang->mail->auth        = '是否需要驗證';
$lang->mail->username    = 'smtp帳號';
$lang->mail->password    = 'smtp密碼';
$lang->mail->secure      = '是否加密';
$lang->mail->debug       = '調試級別';
$lang->mail->charset     = '編碼';

$lang->mail->turnonList[1]  = '打開';
$lang->mail->turnonList[0] = '關閉';

$lang->mail->debugList[0] = '關閉';
$lang->mail->debugList[1] = '一般';
$lang->mail->debugList[2] = '較高';

$lang->mail->authList[1]  = '需要';
$lang->mail->authList[0] = '不需要';

$lang->mail->secureList['']    = '不加密';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->inputFromEmail = '請輸入發信郵箱：';
$lang->mail->nextStep       = '下一步';
$lang->mail->successSaved   = '配置信息已經成功保存。';
$lang->mail->subject        = '測試郵件';
$lang->mail->content        = '郵箱設置成功';
$lang->mail->successSended  = '成功發送！';
$lang->mail->sendmailTips   = '提示：系統不會為當前操作者發信。';
$lang->mail->needConfigure  = '無法找到郵件配置信息，請先配置郵件發送參數。';
$lang->mail->nofsocket      = 'fsocket相關函數被禁用，不能發信！請在php.ini中修改allow_url_fopen為On，打開openssl擴展。 保存並重新啟動apache。';
$lang->mail->noOpenssl      = 'ssl和tls加密，請打開openssl擴展。 保存並重新啟動apache。';
