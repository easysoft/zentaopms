<?php
$lang->mail->common = '發信配置';
$lang->mail->index  = '首頁';
$lang->mail->detect = '檢測';
$lang->mail->edit   = '編輯配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '測試發信';
$lang->mail->reset  = '重置';
$lang->mail->browse = '郵件列表';
$lang->mail->delete = '刪除郵件';
$lang->mail->batchDelete = '批量刪除';

$lang->mail->turnon      = '是否打開';
$lang->mail->async       = '非同步發送';
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

$lang->mail->toList      = '收信人';
$lang->mail->subjectName = '主題';
$lang->mail->addedBy     = '發送者';
$lang->mail->addedDate   = '創建時間';
$lang->mail->sendTime    = '發送時間';
$lang->mail->status      = '狀態';
$lang->mail->ccList      = '抄送給';
$lang->mail->failReason  = '失敗原因';

$lang->mail->statusList['send'] = '成功';
$lang->mail->statusList['fail'] = '失敗';

$lang->mail->turnonList[1]  = '打開';
$lang->mail->turnonList[0] = '關閉';

$lang->mail->asyncList[1] = '是';
$lang->mail->asyncList[0] = '否';

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
$lang->mail->confirmDelete  = '是否刪除郵件？';
$lang->mail->sendmailTips   = '提示：系統不會為當前操作者發信。';
$lang->mail->needConfigure  = '無法找到郵件配置信息，請先配置郵件發送參數。';
$lang->mail->nofsocket      = 'fsocket相關函數被禁用，不能發信！請在php.ini中修改allow_url_fopen為On，打開openssl擴展。 保存並重新啟動apache。';
$lang->mail->noOpenssl      = 'ssl和tls加密，請打開openssl擴展。 保存並重新啟動apache。';
