<?php
/**
 * Don't change this mail directly, set mail config in admin panel instead.
 */
$config->mail = new stdclass();
$config->mail->smtp = new stdclass();

$config->mail->turnon      = false;         // trun on email feature or not. true|false
$config->mail->fromAddress = '';            // The from address.
$config->mail->fromName    = 'zentao';      // The from name.
$config->mail->mta         = 'smtp';        // The send mail type.
$config->mail->smtp->debug    = 0;          // Debug level, 0,1,2.
$config->mail->smtp->charset  = 'utf-8';    // Charset 
$config->mail->smtp->auth     = true;       // Need auth or not. true|false
$config->mail->smtp->host     = 'localhost';// The smtp server host address.
$config->mail->smtp->port     = '25';       // The smtp server host port.
$config->mail->smtp->secure   = '';         // The type to encode datas, 'ssl' or 'tls' allowed
$config->mail->smtp->username = '';         // The smtp user, may be a full email adress.
$config->mail->smtp->password = '';         // The smtp user's password.

/* Mail service providers setting. */
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
$config->mail->provider['qq.com']['secure']     = 'ssl';
$config->mail->provider['qq.com']['port']       = '465';

$config->mail->provider['gmail.com']['host']    = 'smtp.gmail.com';
$config->mail->provider['gmail.com']['secure']  = 'ssl';
$config->mail->provider['gmail.com']['port']    = '465';
$config->mail->provider['google.com']           = $config->mail->provider['gmail.com'];
$config->mail->provider['googlemail.com']       = $config->mail->provider['gmail.com'];

$config->mail->provider['263.net']['host']      = 'smtp.263.net';
$config->mail->provider['263xmail.com']['host'] = 'smtp.263xmail.com';
