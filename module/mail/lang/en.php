<?php
$lang->mail->common = 'Email Settings';
$lang->mail->index  = 'Homepage';
$lang->mail->detect = 'Testing';
$lang->mail->edit   = 'Edit Settings';
$lang->mail->save   = 'Saved';
$lang->mail->test   = 'Email Testing';
$lang->mail->reset  = 'Reset';
$lang->mail->browse = 'Email List';
$lang->mail->delete = 'Delete Email';
$lang->mail->sendCloud     = 'Send Cloud Email';
$lang->mail->batchDelete   = 'Batch Delete';
$lang->mail->sendcloudUser = 'Sync Contact';

$lang->mail->turnon      = 'Turn On';
$lang->mail->async       = 'Asynchronous';
$lang->mail->fromAddress = 'Email Address';
$lang->mail->fromName    = 'Sender';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'Verification Required';
$lang->mail->username    = 'SMTP Account';
$lang->mail->password    = 'SMTP Password';
$lang->mail->secure      = 'Secure';
$lang->mail->debug       = 'Debug';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = 'Access Key';
$lang->mail->secretKey   = 'Secret Key';

$lang->mail->selectMTA = 'Email MTA';
$lang->mail->smtp      = 'SMTP Email';

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

$lang->mail->inputFromEmail = 'Enter Email Address';
$lang->mail->nextStep       = 'Next';
$lang->mail->successSaved   = 'Configuration has been saved.';
$lang->mail->subject        = 'Testing Email';
$lang->mail->content        = 'Email configured!';
$lang->mail->successSended  = 'Sent!';
$lang->mail->confirmDelete  = 'Do you want to delete it?';
$lang->mail->sendmailTips   = 'Note: Email author will not receive this email.';
$lang->mail->needConfigure  = 'Email configuration is not found. Please cinfigure the Email first.';
$lang->mail->nofsocket      = 'fsocket related function has been deactivated. Mails cannot send out. Please modify allow_url_fopen in php.ini to turn on Onopenssl, and restart Apache.';
$lang->mail->noOpenssl      = 'Please turn on Onopenssl, and restart Apache.';
$lang->mail->sendCloudFail  = 'Failed. Reason:';
$lang->mail->sendCloudHelp  = <<<EOD
<p>1、Notice SendCloud是SendCloud的Team通知服务。具体可以到<a href="http://notice.sendcloud.net/" target="_blank">notice.sendcloud.net</a>查看</p>
<p>2、accessKey和secretKey可以到登陆后的"设置"页面查看。Email人地址和名称也在"设置"页面设置。</p>
<p>3、Email时，Notice SendCloudContact里面的昵称要跟Email一致，否则无法成功Email。可以到[<a href='%s'>同步Contact</a>]页面，将ZenTaoUser同步到SendCloudContact中</p>
EOD;
$lang->mail->sendCloudSuccess = '操作成功';
$lang->mail->closeSendCloud   = '关闭发送';
