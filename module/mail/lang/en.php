<?php
$lang->mail->index         = 'Email Homepage';
$lang->mail->detect        = 'Detect';
$lang->mail->detectAction  = 'Detect via Email Address';
$lang->mail->edit          = 'Edit Settings';
$lang->mail->save          = 'Save';
$lang->mail->saveAction    = 'Save Settings';
$lang->mail->test          = 'Send Test Email';
$lang->mail->reset         = 'Reset';
$lang->mail->resetAction   = 'Reset Settings';
$lang->mail->resend        = 'Resend';
$lang->mail->resendAction  = 'Resend Email';
$lang->mail->browse        = 'Email List';
$lang->mail->delete        = 'Delete Email';
$lang->mail->ztCloud       = 'ZenTao Cloud Mail';
$lang->mail->gmail         = 'Gmail';
$lang->mail->sendCloud     = 'Notice SendCloud';
$lang->mail->batchDelete   = 'Batch Delete';
$lang->mail->sendcloudUser = 'Sync Contacts';
$lang->mail->agreeLicense  = 'Yes';
$lang->mail->disagree      = 'No';

$lang->mail->turnon      = 'Email Notification';
$lang->mail->async       = 'Async Sending';
$lang->mail->fromAddress = 'Sender Email';
$lang->mail->fromName    = 'Sender Name';
$lang->mail->domain      = 'ZenTao Domain';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'Authentication';
$lang->mail->username    = 'SMTP Account';
$lang->mail->password    = 'SMTP Password';
$lang->mail->secure      = 'Encryption';
$lang->mail->debug       = 'Debug Level';
$lang->mail->charset     = 'Encoding';
$lang->mail->accessKey   = 'Access Key';
$lang->mail->secretKey   = 'Secret Key';
$lang->mail->license     = 'Important Notes for ZenTao Cloud Mail';

$lang->mail->selectMTA = 'Select outgoing mail method: ';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Synced';
$lang->mail->unsyncUser = 'Not Synced';
$lang->mail->sync       = 'Sync';
$lang->mail->remove     = 'Remove';

$lang->mail->toList      = 'Recipient';
$lang->mail->ccList      = 'CC';
$lang->mail->subject     = 'Subject';
$lang->mail->createdBy   = 'Sender';
$lang->mail->createdDate = 'Created at';
$lang->mail->sendTime    = 'Sent at';
$lang->mail->status      = 'Status';
$lang->mail->failReason  = 'Failure reason';

$lang->mail->statusList['wait']    = 'Pending';
$lang->mail->statusList['sending'] = 'Sending';
$lang->mail->statusList['sended']  = 'Sent';
$lang->mail->statusList['fail']    = 'Failed';

$lang->mail->turnonList[1]  = 'On';
$lang->mail->turnonList[0] = 'Off';

$lang->mail->asyncList[1] = 'Yes';
$lang->mail->asyncList[0] = 'No';

$lang->mail->debugList[0] = 'Off';
$lang->mail->debugList[1] = 'Medium';
$lang->mail->debugList[2] = 'High';

$lang->mail->authList[1]  = 'Yes';
$lang->mail->authList[0] = 'No';

$lang->mail->secureList['0']   = 'Off';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = 'More';
$lang->mail->noticeResend   = 'Email sent successfully.';
$lang->mail->inputFromEmail = 'Enter sender email: ';
$lang->mail->nextStep       = 'Next';
$lang->mail->successSaved   = 'Email settings saved successfully.';
$lang->mail->setForUser     = 'No valid user email addresses found. Cannot send test email. Please configure user email addresses first.';
$lang->mail->testSubject    = 'Test email';
$lang->mail->testContent    = 'Email settings saved.';
$lang->mail->successSended  = 'Sent successfully!';
$lang->mail->confirmDelete  = 'Are you sure you want to delete the email?';
$lang->mail->sendmailTips   = 'Email sender will not receive this email.';
$lang->mail->needConfigure  = 'Email settings not found. Please configure outgoing mail settings first.';
$lang->mail->connectFail    = 'Cannot connect to the ZenTao website.';
$lang->mail->centifyFail    = 'Verification failed. The key may have changed. Please reconnect.';
$lang->mail->nofsocket      = 'fsocket functions are disabled; email cannot be sent. Please set allow_url_fopen to On in php.ini, enable the OpenSSL extension, and restart Apache.';
$lang->mail->noOpenssl      = 'To use SSL or TLS encryption, please enable the OpenSSL extension. Save changes and restart Apache.';
$lang->mail->disableSecure  = 'OpenSSL extension missing. SSL/TLS encryption disabled.';
$lang->mail->sendCloudFail  = 'Failed. Reason:';
$lang->mail->sendCloudHelp  = <<<EOD
<p>. Notice SendCloud is a team notification service provided by SendCloud. For details, please visit <a href="http://notice.sendcloud.net/" target="_blank">notice.sendcloud.net</a></p>
<p>2. You can view your accessKey and secretKey on the "Settings" page after logging in. The sender address and name are also configured in "Settings".</p>
<p>3. To send emails successfully, the nickname in Notice SendCloud contacts must match the email address. Please visit the [<a href='%s'>Sync contacts</a>] page to sync ZenTao users to SendCloud.</p>
EOD;
$lang->mail->sendCloudSuccess = 'Done!';
$lang->mail->closeSendCloud   = 'Close SendCloud';
$lang->mail->addressWhiteList = 'To prevent emails from being blocked, please whitelist the sender address on your mail server.';
$lang->mail->ztCloudNotice    = <<<EOD
<p>ZenTao Cloud Mail is a free email service launched jointly by the ZenTao team and <a href='http://sendcloud.sohu.com/' target='_blank'>SendCloud</a>.</p>
<p>To access this free service, simply register an account on the official ZenTao website and verify your mobile number and email address.</p>
<p style='color:red'>We will submit your verification details to the SendCloud team for approval, granting you a free daily quota of 200 emails.</p>
<ul>
<li>Upon submitting verification on the ZenTao website, you will receive a daily quota of  <strong style='color:red'>50</strong> emails for <strong style='color:red'>3</strong> days.</li>
<li>nce your information is reviewed by ZenTao, you will receive a daily quota of <strong style='color:red'>200</strong> emails for <strong style='color:red'>7</strong>days.</li>
<li>After final approval by SendCloud, you will receive a permanent daily quota of  <strong style='color:red'>200</strong>emails.</li>
</ul>
<p>You cannot use this service if you do not agree to the terms above.</p>
EOD;

$lang->mail->forgetPassword = <<<EOT
<p>Hi there,</p>
<p>You requested a password reset for ZenTao. This link is valid for 3 minutes. If it expires, please request a new one.</p>
<p><a href="%s" target="_blank">Click here to reset</a></p>
EOT;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'Some email providers require a generated authorization code (or App Password) instead of a login password. Please check your email provider\'s settings for details.';
