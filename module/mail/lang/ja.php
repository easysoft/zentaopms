<?php
$lang->mail->common = '発信配置';
$lang->mail->index = 'ホーム';
$lang->mail->detect = '検出';
$lang->mail->detectAction = 'メールアドレスで検出';
$lang->mail->edit = '配置編集';
$lang->mail->save = '保存';
$lang->mail->saveAction = '発信設定を保存';
$lang->mail->test = '発信テスト';
$lang->mail->reset = 'リセット';
$lang->mail->resetAction = '発信設定リセット';
$lang->mail->resend = '再送信';
$lang->mail->resendAction = 'メール再送信';
$lang->mail->browse = 'メールリスト';
$lang->mail->delete = 'メール削除';
$lang->mail->ztCloud = '禅道クラウド発信';
$lang->mail->gmail = 'GMAIL発信';
$lang->mail->sendCloud = 'Notice発信';
$lang->mail->batchDelete = '一括削除';
$lang->mail->sendcloudUser = '連絡先シンクロ';
$lang->mail->agreeLicense = '同意';
$lang->mail->disagree = '同意しない';

$lang->mail->turnon = 'オープンしますか';
$lang->mail->async = '非同期発信';
$lang->mail->fromAddress = '発信メールアドレス';
$lang->mail->fromName = '発信者';
$lang->mail->domain = '禅道ドメイン名';
$lang->mail->host = 'smtpサーバー';
$lang->mail->port = 'smtpポート番号';
$lang->mail->auth = '検証しますか';
$lang->mail->username = 'smtpアカウント';
$lang->mail->password = 'smtpパスワード';
$lang->mail->secure = '暗号化しますか';
$lang->mail->debug = 'デバッグレベル';
$lang->mail->charset = 'エンコーディング';
$lang->mail->accessKey = 'accessKey';
$lang->mail->secretKey = 'secretKey';
$lang->mail->license = '禅道クラウド発信の注意事項';

$lang->mail->selectMTA = '発信方式を選んでください：';
$lang->mail->smtp = 'SMTP発信';

$lang->mail->syncedUser = 'シンクロ済';
$lang->mail->unsyncUser = '未シンクロ';
$lang->mail->sync = 'シンクロ';
$lang->mail->remove = '除去';

$lang->mail->toList = '宛先';
$lang->mail->ccList = 'CC';
$lang->mail->subject = 'テーマ';
$lang->mail->createdBy = '発信者';
$lang->mail->createdDate = '作成時間';
$lang->mail->sendTime = '発信時間';
$lang->mail->status = 'ステータス';
$lang->mail->failReason = '失敗原因';

$lang->mail->statusList['wait']   = '待发送';
$lang->mail->statusList['sended'] = '成功';
$lang->mail->statusList['fail'] = '失敗';

$lang->mail->turnonList[1] = 'オープン';
$lang->mail->turnonList[0] = 'クローズ';

$lang->mail->asyncList[1] = 'はい';
$lang->mail->asyncList[0] = 'いいえ';

$lang->mail->debugList[0] = 'クローズ';
$lang->mail->debugList[1] = '普通';
$lang->mail->debugList[2] = '高目';

$lang->mail->authList[1] = '必要';
$lang->mail->authList[0] = '不要';

$lang->mail->secureList[''] = '暗号化無し';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more = 'もっと多く…';
$lang->mail->noticeResend = 'すでに再発信しました！';
$lang->mail->inputFromEmail = '発信メールアドレスを入力してください：';
$lang->mail->nextStep = '次へ';
$lang->mail->successSaved = '配置情報を保存しました。';
$lang->mail->setForUser     = '系统内用户都没有维护可用邮箱，无法测试发信，请先为用户维护邮箱。';
$lang->mail->testSubject = 'テストメール';
$lang->mail->testContent = 'メールアドレス設定成功';
$lang->mail->successSended = '発信成功！';
$lang->mail->confirmDelete = 'メールを削除してもよろしいですか？';
$lang->mail->sendmailTips = 'ヒント：システムは現在発信しません。';
$lang->mail->needConfigure = 'メールの配置情報が見つかりません、メールの発信パラメータを配置してください。';
$lang->mail->connectFail = '禅道サイトにアクセスできません。';
$lang->mail->centifyFail = '検証に失敗しました、パスワードが更新されたかもしれません。再度バインドしてください！';
$lang->mail->nofsocket = 'fsocketの関連関数が無効されました、発信できません！php.iniのallow_url_fopenでOnを設定し、openssl拡張をオープンし、 保存してapacheを再起動してください。';
$lang->mail->noOpenssl = 'sslとtlsが暗号化されました。openssl拡張をオープンし、保存してapacheを再起動してください。';
$lang->mail->disableSecure = 'openssl拡張がありません、sslとtlsの暗号化が無効になります';
$lang->mail->sendCloudFail = '操作失敗、原因は：';
$lang->mail->sendCloudHelp  = <<<EOD
<p>1、Notice SendCloud是SendCloud的团队通知服务。具体可以到<a href="http://notice.sendcloud.net/" target="_blank">notice.sendcloud.net</a>查看</p>
<p>2、accessKey和secretKey可以到登陆后的"设置"页面查看。发信人地址和名称也在"设置"页面设置。</p>
<p>3、发信时，Notice SendCloud联系人里面的昵称要跟邮箱一致，否则无法成功发信。可以到[<a href='%s'>同步联系人</a>]页面，将禅道用户同步到SendCloud联系人中</p>
EOD;
$lang->mail->sendCloudSuccess = '操作成功';
$lang->mail->closeSendCloud = 'SendCloudクローズ';
$lang->mail->addressWhiteList = 'メールがブロックされないように、メールサーバーで発信メールアドレスをホワイトリストに追加してください';
$lang->mail->ztCloudNotice    = <<<EOD
<p>禅道云发信是由禅道开发团队和<a href='http://sendcloud.sohu.com/' target='_blank'>SendCloud</a>联合推出的一个免费发信服务。</p>
<p>您只需要在禅道官网注册帐号，并完成手机和邮箱的验证，即可享受免费的发信服务。</p>
<p style='color:red'>您的认证信息我们会帮您提交到SendCloud的团队进行认证，以获得每天200封邮件的免费额度。</p>
<ul>
<li>您在禅道官网提交认证之后，即可享受每天<strong style='color:red'>50</strong>封的发信额度，为期<strong style='color:red'>3</strong>天。</li>
<li>您的信息经由禅道官网审核之后，即可享受每天<strong style='color:red'>200</strong>封的发信额度，为期<strong style='color:red'>7</strong>天。</li>
<li>您的信息经由SendCloud最终审核之后，即可长期享受每天<strong style='color:red'>200</strong>封的发信额度。</li>
</ul>
<p>如果不同意以上条款，就不能该服务。</p>
EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'パースワードを入力してください。';
