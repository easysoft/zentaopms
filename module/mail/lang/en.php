<?php
$lang->mail->setParam = 'Please input param of Email';

$lang->mail->turnon       = 'trun on email feature or not';
$lang->mail->fromAddress  = 'The from address';
$lang->mail->fromName     = 'The from name';
$lang->mail->mta          = 'The style of send email';
$lang->mail->debugExample = '0:close,1 and 2 :open,2 is more infomation than 1';

$lang->mail->mtaList['gmail']    = 'Gmail sever';
$lang->mail->mtaList['smtp']     = 'Smtp server';
$lang->mail->mtaList['phpmail']  = 'Mail function build in php';
$lang->mail->mtaList['sendmail'] = 'Sendmail on localhost';

/* Trun on email feature or not */
$lang->mail->turnonList['true']  = 'open';
$lang->mail->turnonList['false'] = 'close';

$lang->mail->debugList[2] = '2';
$lang->mail->debugList[0] = '0';
$lang->mail->debugList[1] = '1';

$lang->mail->smtp->authList['true']  = 'yes';
$lang->mail->smtp->authList['false'] = 'no';

$lang->mail->smtp->secureList['']    = 'not encode';
$lang->mail->smtp->secureList['ssl'] = 'ssl';
$lang->mail->smtp->secureList['tls'] = 'tls';

/* Set SMTP */
$lang->mail->smtp->fromName    = 'The from name';
$lang->mail->smtp->auth        = 'Need auth or not';
$lang->mail->smtp->debug       = 'Debug level';
$lang->mail->smtp->secure      = 'The type to encode datas';
$lang->mail->smtp->host        = 'The smtp HOST';
$lang->mail->smtp->hostInfo    = 'If the HOST is not special,you can not write it,systom can auto write it.';
$lang->mail->smtp->username    = 'The smtp user';
$lang->mail->smtp->password    = "The smtp user's password";
$lang->mail->smtp->port        = 'The smtp server host port';
$lang->mail->smtp->portInfo    = 'ssl: default port is 465, tls: default port is 587, not encode: default port is empty';
/* Set gmail */
$lang->mail->gmail->username = 'The gmail user';
$lang->mail->gmail->password = "The gmail user'password";
$lang->mail->gmail->debug    = 'Debug level';

$lang->mail->confirmSave = 'Save successful.Please login your email to view the test mail';
$lang->mail->subject     = 'The test mail';
$lang->mail->content     = "The email's param is setted";

/* Save config information */
$lang->mail->configInfo  = 'Config information';
$lang->mail->saveConfig  = 'Please save Config information to:';
$lang->mail->createFile  = 'If the zzzemail.php is not exist, please create it.';
