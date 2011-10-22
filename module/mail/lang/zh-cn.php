<?php
$lang->mail->setParam = '请设置下面的配置参数';

$lang->mail->turnon       = '是否打开发信功能';
$lang->mail->fromAddress  = '发信人邮箱';
$lang->mail->fromName     = '发信人名称';
$lang->mail->mta          = '请选择发信方式';
$lang->mail->debugExample = '0表示关闭调试信息,1和2表示打开调试信息，但2比1调试信息显示更详细';

$lang->mail->mtaList['']         = '';
$lang->mail->mtaList['gmail']    = 'Gmail服务器方式';
$lang->mail->mtaList['smtp']     = 'SMTP服务器方式';
$lang->mail->mtaList['phpmail']  = 'PHP内置mail函数';
$lang->mail->mtaList['sendmail'] = '本机sendmail';

/* Trun on email feature or not */
$lang->mail->turnonList['true']  = '打开';
$lang->mail->turnonList['false'] = '关闭';

$lang->mail->debugList[0] = '0';
$lang->mail->debugList[1] = '1';
$lang->mail->debugList[2] = '2';

$lang->mail->smtp->authList['true']  = '是';
$lang->mail->smtp->authList['false'] = '否';

$lang->mail->smtp->secureList['']    = '不加密';
$lang->mail->smtp->secureList['ssl'] = 'ssl';
$lang->mail->smtp->secureList['tls'] = 'tls';

/* Set SMTP */
$lang->mail->smtp->fromName    = '发信人姓名';
$lang->mail->smtp->auth        = '是否需要验证';
$lang->mail->smtp->debug       = '请选择调试等级';
$lang->mail->smtp->secure      = '请选择SMTP加密方式';
$lang->mail->smtp->host        = '请输入HOST';
$lang->mail->smtp->hostInfo    = '如不是特殊HOST，可不填写，系统会自动生成';
$lang->mail->smtp->username    = '发信邮箱用户名';
$lang->mail->smtp->password    = '请输入密码';
$lang->mail->smtp->port        = '请输入端口号';
$lang->mail->smtp->portInfo    = 'ssl加密方式默认端口号为465，tls加密方式默认端口号为587，不加密端口号一般为空';
/* Set gmail */
$lang->mail->gmail->username = '请输入发信邮箱用户名';
$lang->mail->gmail->password = '请输入发信邮箱密码';
$lang->mail->gmail->debug    = '请选择调试等级';

$lang->mail->confirmSave = '保存成功, 请到您的邮箱中查看测试邮件是否发送成功。';
$lang->mail->subject     = '测试邮件';
$lang->mail->content     = '邮箱设置成功';

/* Save config information */
$lang->mail->configInfo  = '配置信息';
$lang->mail->saveConfig  = '请将该配置信息保存到： ';
$lang->mail->createFile  = '如果zzzemail.php文件不存在，请手动创建该文件，将以上配置保存即可。';
