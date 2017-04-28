<?php
$lang->mail->common = '发信配置';
$lang->mail->index  = '首页';
$lang->mail->detect = '检测';
$lang->mail->edit   = '编辑配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '测试发信';
$lang->mail->reset  = '重置';
$lang->mail->resend = '重发';
$lang->mail->browse = '邮件列表';
$lang->mail->delete = '删除邮件';
$lang->mail->ztCloud       = '禅道云发信';
$lang->mail->sendCloud     = 'Notice发信';
$lang->mail->batchDelete   = '批量删除';
$lang->mail->sendcloudUser = '同步联系人';
$lang->mail->agreeLicense  = '同意';
$lang->mail->disagree      = '不同意';

$lang->mail->turnon      = '是否打开';
$lang->mail->async       = '异步发送';
$lang->mail->fromAddress = '发信邮箱';
$lang->mail->fromName    = '发信人';
$lang->mail->domain      = '禅道域名';
$lang->mail->host        = 'smtp服务器';
$lang->mail->port        = 'smtp端口号';
$lang->mail->auth        = '是否需要验证';
$lang->mail->username    = 'smtp帐号';
$lang->mail->password    = 'smtp密码';
$lang->mail->secure      = '是否加密';
$lang->mail->debug       = '调试级别';
$lang->mail->charset     = '编码';
$lang->mail->accessKey   = 'accessKey';
$lang->mail->secretKey   = 'secretKey';
$lang->mail->license     = '禅道云发信使用须知';

$lang->mail->selectMTA = '请选择发信方式：';
$lang->mail->smtp      = 'SMTP发信';

$lang->mail->syncedUser = '已经同步';
$lang->mail->unsyncUser = '未同步';
$lang->mail->sync       = '同步';
$lang->mail->remove     = '移除';

$lang->mail->toList      = '收信人';
$lang->mail->subjectName = '主题';
$lang->mail->addedBy     = '发送者';
$lang->mail->addedDate   = '创建时间';
$lang->mail->sendTime    = '发送时间';
$lang->mail->status      = '状态';
$lang->mail->failReason  = '失败原因';

$lang->mail->statusList['send'] = '成功';
$lang->mail->statusList['fail'] = '失败';

$lang->mail->turnonList[1]  = '打开';
$lang->mail->turnonList[0] = '关闭';

$lang->mail->asyncList[1] = '是';
$lang->mail->asyncList[0] = '否';

$lang->mail->debugList[0] = '关闭';
$lang->mail->debugList[1] = '一般';
$lang->mail->debugList[2] = '较高';

$lang->mail->authList[1]  = '需要';
$lang->mail->authList[0] = '不需要';

$lang->mail->secureList['']    = '不加密';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = '更多...';
$lang->mail->noticeResend   = '已经重新发信！';
$lang->mail->inputFromEmail = '请输入发信邮箱：';
$lang->mail->nextStep       = '下一步';
$lang->mail->successSaved   = '配置信息已经成功保存。';
$lang->mail->subject        = '测试邮件';
$lang->mail->content        = '邮箱设置成功';
$lang->mail->successSended  = '成功发送！';
$lang->mail->confirmDelete  = '是否删除邮件？';
$lang->mail->sendmailTips   = '提示：系统不会为当前操作者发信。';
$lang->mail->needConfigure  = '无法找到邮件配置信息，请先配置邮件发送参数。';
$lang->mail->connectFail    = '无法连接禅道网站。';
$lang->mail->centifyFail    = '验证失败，可能密钥已经修改。请重新绑定！';
$lang->mail->nofsocket      = 'fsocket相关函数被禁用，不能发信！请在php.ini中修改allow_url_fopen为On，打开openssl扩展。 保存并重新启动apache。';
$lang->mail->noOpenssl      = 'ssl和tls加密，请打开openssl扩展。 保存并重新启动apache。';
$lang->mail->disableSecure  = '没有openssl扩展，禁用ssl和tls加密';
$lang->mail->sendCloudFail  = '操作失败，原因：';
$lang->mail->sendCloudHelp  = <<<EOD
<p>1、Notice SendCloud是SendCloud的团队通知服务。具体可以到<a href="http://notice.sendcloud.net/" target="_blank">notice.sendcloud.net</a>查看</p>
<p>2、accessKey和secretKey可以到登陆后的"设置"页面查看。发信人地址和名称也在"设置"页面设置。</p>
<p>3、发信时，Notice SendCloud联系人里面的昵称要跟邮箱一致，否则无法成功发信。可以到[<a href='%s'>同步联系人</a>]页面，将禅道用户同步到SendCloud联系人中</p>
EOD;
$lang->mail->sendCloudSuccess = '操作成功';
$lang->mail->closeSendCloud   = '关闭SendCloud';
$lang->mail->addressWhiteList = '为防止邮件被屏蔽，请在邮件服务器里面将发信邮箱设为白名单';
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
$lang->mail->placeholder->password = '有些邮箱需要填写单独申请的授权码，具体请到邮箱相关设置查询。';
