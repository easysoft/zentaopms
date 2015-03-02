<?php
$lang->mail->common = 'Email setting';
$lang->mail->index  = 'Index';
$lang->mail->detect = 'Detect';
$lang->mail->edit   = 'Configure';
$lang->mail->save   = 'Successfully saved';
$lang->mail->test   = 'Testing';
$lang->mail->reset  = 'Reset';
$lang->mail->browse = 'Mail list';
$lang->mail->delete = 'Delete mail';
$lang->mail->batchDelete = 'Batch delete';

$lang->mail->turnon      = 'Turnon';
$lang->mail->async       = 'Asynchronous';
$lang->mail->fromAddress = 'From email';
$lang->mail->fromName    = 'From title';
$lang->mail->mta         = 'MTA';
$lang->mail->host        = 'SMTP host';
$lang->mail->port        = 'SMTP port';
$lang->mail->auth        = 'Authentication';
$lang->mail->username    = 'SMTP account';
$lang->mail->password    = 'SMTP password';
$lang->mail->secure      = 'Secure';
$lang->mail->debug       = 'Debug';
$lang->mail->charset     = 'Charset';

$lang->mail->toList      = 'Addressee';
$lang->mail->subjectName = 'Subject';
$lang->mail->addedBy     = 'Sender';
$lang->mail->addedDate   = 'Added date';
$lang->mail->sendTime    = 'Send time';
$lang->mail->status      = 'Status';
$lang->mail->ccList      = 'Copy to';
$lang->mail->failReason  = 'Fail Reason';

$lang->mail->statusList['send'] = 'Success';
$lang->mail->statusList['fail'] = 'Fail';

$lang->mail->turnonList[1] = 'on';
$lang->mail->turnonList[0] = 'off';

$lang->mail->asyncList[1] = 'Yes';
$lang->mail->asyncList[0] = 'No';

$lang->mail->debugList[0] = 'off';
$lang->mail->debugList[1] = 'normal';
$lang->mail->debugList[2] = 'high';

$lang->mail->authList[1]  = 'necessary';
$lang->mail->authList[0]  = 'unnecessary';

$lang->mail->secureList['']    = 'plain';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->inputFromEmail = 'Please input the from email:';
$lang->mail->nextStep       = 'Next';
$lang->mail->successSaved   = 'The configuration has been successfully saved.';
$lang->mail->subject        = "It's a testing email from zentao.";
$lang->mail->content        = 'If you can see this, the email notification feature can work now!';
$lang->mail->successSended  = 'Successfully sended!';
$lang->mail->confirmDelete  = 'Confirm delete mail?';
$lang->mail->sendmailTips   = 'Tips: system will not send mail to current user.';
$lang->mail->needConfigure  = "I can not find the configuration, please configure it first.";
$lang->mail->nofsocket      = 'The fsocket correlation function is disabled, not letter! Please enlarge the setting of allow_url_fopen to On in php.ini and open the extension of openssl. Restart apache.';
$lang->mail->noOpenssl      = 'Please open the extension of openssl when use tls. Restart apache.';
