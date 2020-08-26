<?php
$lang->mail->common        = 'Email Einstellungen';
$lang->mail->index         = 'Home';
$lang->mail->detect        = 'Testen';
$lang->mail->detectAction  = 'Detect By Email Address';
$lang->mail->edit          = 'Bearbeiten';
$lang->mail->save          = 'Speichern';
$lang->mail->saveAction    = 'Save Settings';
$lang->mail->test          = 'Email Test Sendung';
$lang->mail->reset         = 'Zurücksetzen';
$lang->mail->resetAction   = 'Reset Settings';
$lang->mail->resend        = 'Nochmals senden';
$lang->mail->resendAction  = 'Resend Email';
$lang->mail->browse        = 'Email Liste';
$lang->mail->delete        = 'Email löschen';
$lang->mail->ztCloud       = 'ZenTao CloudMail';
$lang->mail->gmail         = 'GMAIL';
$lang->mail->sendCloud     = 'Notice SendCloud';
$lang->mail->batchDelete   = 'Mehrfach Löschung';
$lang->mail->sendcloudUser = 'Kontakte Synchronisieren';
$lang->mail->agreeLicense  = 'Ja';
$lang->mail->disagree      = 'Nein';

$lang->mail->turnon      = 'Mail aktivieren';
$lang->mail->async       = 'Asynchrone Sendung';
$lang->mail->fromAddress = 'Sender Email';
$lang->mail->fromName    = 'Sender Name';
$lang->mail->domain      = 'Domain';
$lang->mail->host        = 'SMTP Server';
$lang->mail->port        = 'SMTP Port';
$lang->mail->auth        = 'Anmeldung erforderlich';
$lang->mail->username    = 'SMTP Benutzer';
$lang->mail->password    = 'SMTP Passwort';
$lang->mail->secure      = 'Verschlüsselung';
$lang->mail->debug       = 'Debug Level';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = 'Access Key';
$lang->mail->secretKey   = 'Secret Key';
$lang->mail->license     = 'ZenTao Cloud Hinweis';

$lang->mail->selectMTA = 'MTA(Mail Transfer Agent)';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Synchronisiert';
$lang->mail->unsyncUser = 'Nicht Synchronisiert';
$lang->mail->sync       = 'Synchronisieren';
$lang->mail->remove     = 'Entfernen';

$lang->mail->toList      = 'Empfänger';
$lang->mail->ccList      = 'Kopie an';
$lang->mail->subject     = 'Betreff';
$lang->mail->createdBy   = 'Sender';
$lang->mail->createdDate = 'Angelegt am';
$lang->mail->sendTime    = 'Gesendet am';
$lang->mail->status      = 'Status';
$lang->mail->failReason  = 'Problem';

$lang->mail->statusList['wait']   = 'Wait';
$lang->mail->statusList['sended'] = 'Senden';
$lang->mail->statusList['fail']   = 'Fehlgeschlagen';

$lang->mail->turnonList[1]  = 'An';
$lang->mail->turnonList[0] = 'Aus';

$lang->mail->asyncList[1] = 'Ja';
$lang->mail->asyncList[0] = 'Nein';

$lang->mail->debugList[0] = 'Aus';
$lang->mail->debugList[1] = 'Normal';
$lang->mail->debugList[2] = 'Hoch';

$lang->mail->authList[1]  = 'Erforderlich';
$lang->mail->authList[0] = 'Optional';

$lang->mail->secureList['']    = 'Ohne';
$lang->mail->secureList['ssl'] = 'SSL';
$lang->mail->secureList['tls'] = 'TLS';

$lang->mail->more           = 'Mehr';
$lang->mail->noticeResend   = 'Nochmals senden!';
$lang->mail->inputFromEmail = 'Sender Email';
$lang->mail->nextStep       = 'Weiter';
$lang->mail->successSaved   = 'Konfiguration wurde gespeichert.';
$lang->mail->setForUser     = 'Could not test mail configure because the users are without mail in system. Please set mail for user first.';
$lang->mail->testSubject    = 'Email testen';
$lang->mail->testContent    = 'Email konfiguriert!';
$lang->mail->successSended  = 'Gesendet!';
$lang->mail->confirmDelete  = 'Möchten Sie das löschen?';
$lang->mail->sendmailTips   = 'Hinweis: Der Emailersteller wird diese Mail nicht erhalten.';
$lang->mail->needConfigure  = 'Email Konfiguration nicht gefunden. Bitte konfigurieren Sie die Emaileinstellungen.';
$lang->mail->connectFail    = 'Verbindung zu ZenTao fehlgeschlagen.';
$lang->mail->centifyFail    = 'Anmeldung fehlgeschlagen. Bitte prüfen Sie die Kontoverknüpfung!';
$lang->mail->nofsocket      = 'fsocket Funktionen wurden deaktiviert. Mails können nicht gesendet werden. Bitte bearbeiten Sie allow_url_fopen in der php.ini um Onopenssl einzuschalten. Anschließend bitte den Webserver neustarten.';
$lang->mail->noOpenssl      = 'Bitte aktivieren Sie Onopenssl und starten Sie den Webserver neu.';
$lang->mail->disableSecure  = 'Kein Openssl. Deaktivieren Sie SSL und TLS.';
$lang->mail->sendCloudFail  = 'Fehlgeschlagen. Fehler:';
$lang->mail->sendCloudHelp  = <<<EOD



EOD;
$lang->mail->sendCloudSuccess = 'Erledigt';
$lang->mail->closeSendCloud   = 'Schließen';
$lang->mail->addressWhiteList = 'Bitte fügen Sie diesen Server zur WhiteList hinzu um nicht blockiert zu werden.';
$lang->mail->ztCloudNotice    = <<<EOD









EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = 'Manche Mail Server benötigen einen AUTH CODE. Bitte prüfen Sie das bei Ihrem Mailprovider.';
