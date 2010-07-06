<?php
/* 是否打开邮件功能。*/
$config->mail->turnon = false;

/* 设置发件人地址和名称。*/
$config->mail->fromAddress    = '';     // 发件人地址。
$config->mail->fromName       = '';     // 发件人名称。 

/* 设置发信方式，目前支持phpmail|sendmail|smtp|gmail。*/
$config->mail->mta            = 'gmail'; 

/* 普通SMTP的配置：*/
if($config->mail->mta == 'smtp')
{
    $config->mail->smtp->debug    = 0;          // smtp debug级别，0，1, 2, 数字越大，级别越高。
    $config->mail->smtp->auth     = true;       // 是否需要验证。 
    $config->mail->smtp->host     = '';         // smtp主机。
    $config->mail->smtp->port     = '';         // 端口号。
    $config->mail->smtp->username = '';         // 登录用户名，有的smtp需要完整的邮箱地址。
    $config->mail->smtp->password = '';         // 密码。
}
/* GMAIL的配置。*/
elseif($config->mail->mta == 'gmail')
{
    $config->mail->gmail->debug      = 0;       // debug级别，0，1, 2, 数字越大，级别越高。
    $config->mail->gmail->username   = "";      // GMAIL username
    $config->mail->gmail->password   = "";      // GMAIL password
}
