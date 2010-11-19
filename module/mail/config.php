<?php
$config->mail->turnon         = false;   // trun on email feature or not.
$config->mail->fromAddress    = '';      // The from address.
$config->mail->fromName       = '';      // The from name.
$config->mail->mta            = 'gmail'; // phpmail|sendmail|smtp|gmail

/* SMTP settings. */
if($config->mail->mta == 'smtp')
{
    $config->mail->smtp->debug    = 0;          // Debug level, 0,1,2.
    $config->mail->smtp->auth     = true;       // Need auth or not.
    $config->mail->smtp->host     = '';         // The smtp server host address.
    $config->mail->smtp->port     = '';         // The smtp server host port.
    $config->mail->smtp->username = '';         // The smtp user, may be a full email adress.
    $config->mail->smtp->password = '';         // The smtp user's password.
}
/* Gmail setting. */
elseif($config->mail->mta == 'gmail')
{
    $config->mail->gmail->debug      = 0;       // Debug level, 0,1,2.
    $config->mail->gmail->username   = "";      // GMAIL username
    $config->mail->gmail->password   = "";      // GMAIL password
}
