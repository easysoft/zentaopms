<?php
$lang->mail->common = 'Email Settings';
$lang->mail->index  = 'Home';
$lang->mail->detect = 'Testing';
$lang->mail->edit   = 'Edit Settings';
$lang->mail->save   = 'Saved';
$lang->mail->test   = 'Email Testing';
$lang->mail->reset  = 'Reset';
$lang->mail->resend = 'Resend';
$lang->mail->browse = 'Email List';
$lang->mail->delete = 'Delete Email';
$lang->mail->ztCloud       = 'ZenTao CloudMail';
$lang->mail->sendCloud     = 'Notice SendCloud';
$lang->mail->batchDelete   = 'Batch Delete';
$lang->mail->sendcloudUser = 'Sync Contact';
$lang->mail->agreeLicense  = 'Yes';
$lang->mail->disagree      = 'No';

$lang->mail->turnon      = 'Turn On';
$lang->mail->async       = 'Asynchronous';
$lang->mail->fromAddress = 'Email Address';
$lang->mail->fromName    = 'Sender';
$lang->mail->domain      = 'Domain';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'Acceptance Required';
$lang->mail->username    = 'SMTP Account';
$lang->mail->password    = 'SMTP Password';
$lang->mail->secure      = 'Secure';
$lang->mail->debug       = 'Debug';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = 'Access Key';
$lang->mail->secretKey   = 'Secret Key';
$lang->mail->license     = 'ZenTao Cloud Notice';

$lang->mail->selectMTA = 'Select MTA';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Synchronized';
$lang->mail->unsyncUser = 'Not Synchronized';
$lang->mail->sync       = 'Synchronize';
$lang->mail->remove     = 'Remove';

$lang->mail->toList      = 'Addressee';
$lang->mail->subjectName = 'Subject';
$lang->mail->addedBy     = 'Sender';
$lang->mail->addedDate   = 'Added Date';
$lang->mail->sendTime    = 'Send Date';
$lang->mail->status      = 'Status';
$lang->mail->failReason  = 'Fail Reason';

$lang->mail->statusList['send'] = 'Sent';
$lang->mail->statusList['fail'] = 'Failed';

$lang->mail->turnonList[1]  = 'On';
$lang->mail->turnonList[0] = 'Off';

$lang->mail->asyncList[1] = 'Yes';
$lang->mail->asyncList[0] = 'No';

$lang->mail->debugList[0] = 'Off';
$lang->mail->debugList[1] = 'Normal';
$lang->mail->debugList[2] = 'High';

$lang->mail->authList[1]  = 'Required';
$lang->mail->authList[0] = 'Not Required';

$lang->mail->secureList['']    = 'Plain';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = 'More';
$lang->mail->noticeResend   = 'Sent it again!';
$lang->mail->inputFromEmail = 'Enter Email Address';
$lang->mail->nextStep       = 'Next';
$lang->mail->successSaved   = 'Configuration has been saved.';
$lang->mail->subject        = 'Testing Email';
$lang->mail->content        = 'Email configured!';
$lang->mail->successSended  = 'Sent!';
$lang->mail->confirmDelete  = 'Do you want to delete it?';
$lang->mail->sendmailTips   = 'Note: Email author will not receive this email.';
$lang->mail->needConfigure  = 'Email configuration is not found. Please cinfigure the Email first.';
$lang->mail->connectFail    = 'Connection to ZenTao failed.';
$lang->mail->centifyFail    = 'Verification failed. Please try to bind again!';
$lang->mail->nofsocket      = 'fsocket related function has been deactivated. Mails cannot send out. Please modify allow_url_fopen in php.ini to turn on Onopenssl, and restart Apache.';
$lang->mail->noOpenssl      = 'Please turn on Onopenssl, and restart Apache.';
$lang->mail->disableSecure  = 'No openssl. Disable ssl and tls.';
$lang->mail->sendCloudFail  = 'Failed. Reason:';
$lang->mail->sendCloudHelp  = <<<EOD



EOD;
$lang->mail->sendCloudSuccess = 'Done';
$lang->mail->closeSendCloud   = 'Close';
$lang->mail->addressWhiteList = 'Please add the email to the whilte list on email server to avoid be treated as spam';
$lang->mail->ztCloudNotice    = <<<EOD









EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'Some mail needs to fill in the auth code, specific to the mailbox related settings query.';
