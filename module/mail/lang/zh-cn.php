<?php
$lang->mail->common = '发信配置';
$lang->mail->index  = '首页';
$lang->mail->detect = '检测';
$lang->mail->edit   = '编辑配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '测试发信';
$lang->mail->reset  = '重置';

$lang->mail->turnon      = '是否打开';
$lang->mail->fromAddress = '发信邮箱';
$lang->mail->fromName    = '发信人';
$lang->mail->mta         = '发信方式';
$lang->mail->host        = 'smtp服务器';
$lang->mail->port        = 'smtp端口号';
$lang->mail->auth        = '是否需要验证';
$lang->mail->username    = 'smtp帐号';
$lang->mail->password    = 'smtp密码';
$lang->mail->secure      = '是否加密';
$lang->mail->debug       = '调试级别';
$lang->mail->charset     = '编码';

$lang->mail->turnonList[1]  = '打开';
$lang->mail->turnonList[0] = '关闭';

$lang->mail->debugList[0] = '关闭';
$lang->mail->debugList[1] = '一般';
$lang->mail->debugList[2] = '较高';

$lang->mail->authList[1]  = '需要';
$lang->mail->authList[0] = '不需要';

$lang->mail->secureList['']    = '不加密';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->inputFromEmail = '请输入发信邮箱：';
$lang->mail->nextStep       = '下一步';
$lang->mail->successSaved   = '配置信息已经成功保存。';
$lang->mail->subject        = '测试邮件';
$lang->mail->content        = '邮箱设置成功';
$lang->mail->successSended  = '成功发送！';
$lang->mail->sendmailTips   = '提示：系统不会为当前操作者发信。';
$lang->mail->needConfigure  = '无法找到邮件配置信息，请先配置邮件发送参数。';
$lang->mail->nofsocket      = 'fsocket相关函数被禁用，不能发信！请在php.ini中修改allow_url_fopen为On，打开openssl扩展。 保存并重新启动apache。';
$lang->mail->noOpenssl      = 'ssl和tls加密，请打开openssl扩展。 保存并重新启动apache。';
