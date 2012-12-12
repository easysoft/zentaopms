<?php
$config->mail = new stdclass();
$config->mail->turnon      = 0;        // trun on email feature or not.
$config->mail->fromAddress = '';       // The from address.
$config->mail->fromName    = 'zentao'; // The from name.
$config->mail->mta         = 'smtp';   // smtp|phpmail.

/* SMTP settings. */
if($config->mail->mta == 'smtp')
{
    $config->mail->smtp = new stdclass();
    $config->mail->smtp->debug    = 0;          // Debug level, 0,1,2.
    $config->mail->smtp->auth     = true;       // Need auth or not.
    $config->mail->smtp->host     = 'localhost';// The smtp server host address.
    $config->mail->smtp->port     = '25';       // The smtp server host port.
    $config->mail->smtp->secure   = '';         // The type to encode datas, 'ssl' or 'tls' allowed
    $config->mail->smtp->username = '';         // The smtp user, may be a full email adress.
    $config->mail->smtp->password = '';         // The smtp user's password.
}

$config->mail->provider['163.com']['host']      = 'smtp.163.com';
$config->mail->provider['yeah.net']['host']     = 'smtp.yeah.net';
$config->mail->provider['netease.com']['host']  = 'smtp.netease.com';
$config->mail->provider['126.com']['host']      = 'smtp.126.com';
$config->mail->provider['qiye.163.com']['host'] = 'smtp.qiye.163.com';

$config->mail->provider['sina.com']['host']     = 'smtp.sina.com';
$config->mail->provider['sina.cn']['host']      = 'smtp.sina.cn';
$config->mail->provider['vip.sina.com']['host'] = 'smtp.vip.sina.com';
$config->mail->provider['sina.net']['host']     = 'smtp.sina.net';

$config->mail->provider['sohu.com']['host']     = 'smtp.sohu.com';
$config->mail->provider['vip.sohu.com']['host'] = 'smtp.vip.sohu.com';

$config->mail->provider['21cn.com']['host']     = 'smtp.21cn.com';

$config->mail->provider['qq.com']['host']       = 'smtp.qq.com';

$config->mail->provider['gmail.com']['host']    = 'smtp.gmail.com';
$config->mail->provider['gmail.com']['secure']  = 'ssl';
$config->mail->provider['gmail.com']['port']    = '465';
$config->mail->provider['google.com']           = $config->mail->provider['gmail.com'];
$config->mail->provider['googlemail.com']       = $config->mail->provider['gmail.com'];

$config->mail->provider['263.net']['host']      = 'smtp.263.net';
$config->mail->provider['263xmail.com']['host'] = 'smtp.263xmail.com';
