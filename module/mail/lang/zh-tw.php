<?php
$lang->mail->common = '發信配置';
$lang->mail->index  = '首頁';
$lang->mail->detect = '檢測';
$lang->mail->edit   = '編輯配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '測試發信';
$lang->mail->reset  = '重置';
$lang->mail->resend = '重發';
$lang->mail->browse = '郵件列表';
$lang->mail->delete = '刪除郵件';
$lang->mail->ztCloud       = '禪道雲發信';
$lang->mail->sendCloud     = 'Notice發信';
$lang->mail->batchDelete   = '批量刪除';
$lang->mail->sendcloudUser = '同步聯繫人';
$lang->mail->agreeLicense  = '同意';
$lang->mail->disagree      = '不同意';

$lang->mail->turnon      = '是否打開';
$lang->mail->async       = '非同步發送';
$lang->mail->fromAddress = '發信郵箱';
$lang->mail->fromName    = '發信人';
$lang->mail->domain      = '禪道域名';
$lang->mail->host        = 'smtp伺服器';
$lang->mail->port        = 'smtp連接埠號';
$lang->mail->auth        = '是否需要驗證';
$lang->mail->username    = 'smtp帳號';
$lang->mail->password    = 'smtp密碼';
$lang->mail->secure      = '是否加密';
$lang->mail->debug       = '調試級別';
$lang->mail->charset     = '編碼';
$lang->mail->accessKey   = 'accessKey';
$lang->mail->secretKey   = 'secretKey';
$lang->mail->license     = '禪道雲發信使用須知';

$lang->mail->selectMTA = '請選擇發信方式：';
$lang->mail->smtp      = 'SMTP發信';

$lang->mail->syncedUser = '已經同步';
$lang->mail->unsyncUser = '未同步';
$lang->mail->sync       = '同步';
$lang->mail->remove     = '移除';

$lang->mail->toList      = '收信人';
$lang->mail->subjectName = '主題';
$lang->mail->addedBy     = '發送者';
$lang->mail->addedDate   = '創建時間';
$lang->mail->sendTime    = '發送時間';
$lang->mail->status      = '狀態';
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

$lang->mail->more           = '更多...';
$lang->mail->noticeResend   = '已經重新發信！';
$lang->mail->inputFromEmail = '請輸入發信郵箱：';
$lang->mail->nextStep       = '下一步';
$lang->mail->successSaved   = '配置信息已經成功保存。';
$lang->mail->subject        = '測試郵件';
$lang->mail->content        = '郵箱設置成功';
$lang->mail->successSended  = '成功發送！';
$lang->mail->confirmDelete  = '是否刪除郵件？';
$lang->mail->sendmailTips   = '提示：系統不會為當前操作者發信。';
$lang->mail->needConfigure  = '無法找到郵件配置信息，請先配置郵件發送參數。';
$lang->mail->connectFail    = '無法連接禪道網站。';
$lang->mail->centifyFail    = '驗證失敗，可能密鑰已經修改。請重新綁定！';
$lang->mail->nofsocket      = 'fsocket相關函數被禁用，不能發信！請在php.ini中修改allow_url_fopen為On，打開openssl擴展。 保存並重新啟動apache。';
$lang->mail->noOpenssl      = 'ssl和tls加密，請打開openssl擴展。 保存並重新啟動apache。';
$lang->mail->disableSecure  = '沒有openssl擴展，禁用ssl和tls加密';
$lang->mail->sendCloudFail  = '操作失敗，原因：';
$lang->mail->sendCloudHelp  = <<<EOD
<p>1、Notice SendCloud是SendCloud的團隊通知服務。具體可以到<a href="http://notice.sendcloud.net/" target="_blank">notice.sendcloud.net</a>查看</p>
<p>2、accessKey和secretKey可以到登陸後的"設置"頁面查看。發信人地址和名稱也在"設置"頁面設置。</p>
<p>3、發信時，Notice SendCloud聯繫人裡面的暱稱要跟郵箱一致，否則無法成功發信。可以到[<a href='%s'>同步聯繫人</a>]頁面，將禪道用戶同步到SendCloud聯繫人中</p>
EOD;
$lang->mail->sendCloudSuccess = '操作成功';
$lang->mail->closeSendCloud   = '關閉SendCloud';
$lang->mail->addressWhiteList = '為防止郵件被屏蔽，請在郵件伺服器裡面將發信郵箱設為白名單';
$lang->mail->ztCloudNotice    = <<<EOD
<p>禪道雲發信是由禪道開發團隊和<a href='http://sendcloud.sohu.com/' target='_blank'>SendCloud</a>聯合推出的一個免費發信服務。</p>
<p>您只需要在禪道官網註冊帳號，並完成手機和郵箱的驗證，即可享受免費的發信服務。</p>
<p style='color:red'>您的認證信息我們會幫您提交到SendCloud的團隊進行認證，以獲得每天200封郵件的免費額度。</p>
<ul>
<li>您在禪道官網提交認證之後，即可享受每天<strong style='color:red'>50</strong>封的發信額度，為期<strong style='color:red'>3</strong>天。</li>
<li>您的信息經由禪道官網審核之後，即可享受每天<strong style='color:red'>200</strong>封的發信額度，為期<strong style='color:red'>7</strong>天。</li>
<li>您的信息經由SendCloud最終審核之後，即可長期享受每天<strong style='color:red'>200</strong>封的發信額度。</li>
</ul>
<p>如果不同意以上條款，就不能該服務。</p>
EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = '有些郵箱需要填寫單獨申請的授權碼，具體請到郵箱相關設置查詢。';
