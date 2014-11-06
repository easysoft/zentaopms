<?php
/**
* PHPMailer language file: refer to English translation for definitive list
* Simplified Chinese Version
* @author liqwei <liqwei@liqwei.com>
*/

$PHPMAILER_LANG['authenticate'] = "SMTP 错误：登录失败。\n请检查您设置的用户名密码是否正确。\n有的系统登录用户名是完整的邮箱地址。";
$PHPMAILER_LANG['connect_host'] = "SMTP 错误：无法连接到 SMTP 主机，请确认禅道机器：\n1. 能ping通smtp服务器。如果不能ping通，请查看网络状态，或查看域名解析是否正确，或联系网管；\n2. 使用telnet 命令能够连接到smtp的发信端口;\n3. 如果上述步骤都是通的，windows请检查防火墙和杀毒软件设置，linux请关闭selnux或者执行\"setsebool httpd_can_sendmail true\"允许apache可以发信。";
$PHPMAILER_LANG['data_not_accepted'] = 'SMTP 错误：数据不被接受。';
//$P$PHPMAILER_LANG['empty_message']        = 'Message body empty';
$PHPMAILER_LANG['encoding'] = '未知编码: ';
$PHPMAILER_LANG['execute'] = '无法执行：';
$PHPMAILER_LANG['file_access'] = '无法访问文件：';
$PHPMAILER_LANG['file_open'] = '文件错误：无法打开文件：';
$PHPMAILER_LANG['from_failed'] = '发送地址错误：';
$PHPMAILER_LANG['instantiate'] = '未知函数调用。';
//$PHPMAILER_LANG['invalid_email']        = 'Not sending, email address is invalid: ';
$PHPMAILER_LANG['mailer_not_supported'] = '发信客户端不被支持。';
$PHPMAILER_LANG['provide_address'] = '收件人没有设置邮箱，请到组织视图中检查下相应用户的email设置。';
$PHPMAILER_LANG['recipients_failed'] = 'SMTP 错误：收件人地址错误，或检查SMTP服务设置，是否允许发送该邮件地址：';
//$PHPMAILER_LANG['signing']              = 'Signing Error: ';
//$PHPMAILER_LANG['smtp_connect_failed']  = 'SMTP Connect() failed.';
//$PHPMAILER_LANG['smtp_error']           = 'SMTP server error: ';
//$PHPMAILER_LANG['variable_set']         = 'Cannot set or reset variable: ';
?>
