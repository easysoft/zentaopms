<?php
$lang->mail->common        = 'Email Settings';
$lang->mail->index         = 'Email Home';
$lang->mail->detect        = 'Detect';
$lang->mail->detectAction  = 'Detect By Email Address';
$lang->mail->edit          = 'Edit Settings';
$lang->mail->save          = 'Save';
$lang->mail->saveAction    = 'Save Settings';
$lang->mail->test          = 'Email Sending Test';
$lang->mail->reset         = 'Reset';
$lang->mail->resetAction   = 'Reset Settings';
$lang->mail->resend        = 'Resend';
$lang->mail->resendAction  = 'Resend Email';
$lang->mail->browse        = 'Email List';
$lang->mail->delete        = 'Delete Email';
$lang->mail->ztCloud       = 'ZenTao CloudMail';
$lang->mail->gmail         = 'Gmail';
$lang->mail->sendCloud     = 'Notice SendCloud';
$lang->mail->batchDelete   = 'Batch Delete';
$lang->mail->sendcloudUser = 'Sync. Contact';
$lang->mail->agreeLicense  = 'Yes';
$lang->mail->disagree      = 'No';

$lang->mail->turnon      = 'Email Notification';
$lang->mail->async       = 'Async Sending';
$lang->mail->fromAddress = 'Sender Email';
$lang->mail->fromName    = 'Sender';
$lang->mail->domain      = 'ZenTao Domain';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'SMTP Validation';
$lang->mail->username    = 'SMTP Account';
$lang->mail->password    = 'SMTP Password';
$lang->mail->secure      = 'Encryption';
$lang->mail->debug       = 'Debugging';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = 'Access Key';
$lang->mail->secretKey   = 'Secret Key';
$lang->mail->license     = 'ZenTao CloudMail Notice';

$lang->mail->selectMTA = 'Select Type';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Synchronized';
$lang->mail->unsyncUser = 'Not Synchronized';
$lang->mail->sync       = 'Synchronize';
$lang->mail->remove     = 'Remove';

$lang->mail->toList      = 'Recipient';
$lang->mail->ccList      = 'Copy to';
$lang->mail->subject     = 'Subject';
$lang->mail->createdBy   = 'Sender';
$lang->mail->createdDate = 'CreatedDate';
$lang->mail->sendTime    = 'Sent';
$lang->mail->status      = 'Status';
$lang->mail->failReason  = 'Reason';

$lang->mail->statusList['wait']   = 'Wait';
$lang->mail->statusList['sended'] = 'Sent';
$lang->mail->statusList['fail']   = 'Failed';

$lang->mail->turnonList[1]  = 'On';
$lang->mail->turnonList[0] = 'Off';

$lang->mail->asyncList[1] = 'Yes';
$lang->mail->asyncList[0] = 'No';

$lang->mail->debugList[0] = 'Off';
$lang->mail->debugList[1] = 'Normal';
$lang->mail->debugList[2] = 'High';

$lang->mail->authList[1]  = 'Yes';
$lang->mail->authList[0] = 'No';

$lang->mail->secureList['']    = 'Plain';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = 'More';
$lang->mail->noticeResend   = 'The Email has been re-sent!';
$lang->mail->inputFromEmail = 'Sender Email';
$lang->mail->nextStep       = 'Next';
$lang->mail->successSaved   = 'Email settings are saved.';
$lang->mail->setForUser     = 'Could not test mail configure because the users are without mail in system. Please set mail for user first.';
$lang->mail->testSubject    = 'Testing Email';
$lang->mail->testContent    = 'Email settings are done!';
$lang->mail->successSended  = 'Sent!';
$lang->mail->confirmDelete  = 'Do you want to delete it?';
$lang->mail->sendmailTips   = 'Note: Email sender will not receive this email.';
$lang->mail->needConfigure  = 'Email settings are not found. Please do the Email settings first.';
$lang->mail->connectFail    = 'Connection to ZenTao failed.';
$lang->mail->centifyFail    = 'Verification failed. The secret key might be changed. Please try to bind again!';
$lang->mail->nofsocket      = 'fsocket related functions are deactivated. so Emails cannot be sent out. Please modify allow_url_fopen in php.ini to turn on Onopenssl, and restart Apache.';
$lang->mail->noOpenssl      = 'Please turn on Onopenssl, and restart Apache.';
$lang->mail->disableSecure  = 'No openssl. Disable ssl and tls.';
$lang->mail->sendCloudFail  = 'Failed. Reason:';
$lang->mail->sendCloudHelp  = <<<EOD



EOD;
$lang->mail->sendCloudSuccess = 'Done';
$lang->mail->closeSendCloud   = 'Close SendCloud';
$lang->mail->addressWhiteList = 'Add it to the whiltelist of your email server to avoid being blocked.';
$lang->mail->ztCloudNotice    = <<<EOD









EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'Some Email servers require auth code, refer to your Email service provider.';
