<?php
$lang->mail->common        = 'Paramétrage Email';
$lang->mail->index         = 'Accueil Email';
$lang->mail->detect        = 'Détecter';
$lang->mail->detectAction  = 'Détecter par Adresse mail';
$lang->mail->edit          = 'Editer Paramétrages';
$lang->mail->save          = 'Sauver';
$lang->mail->saveAction    = 'Saver Paramétrages';
$lang->mail->test          = 'Test Envoi Email';
$lang->mail->reset         = 'Réinitialiser';
$lang->mail->resetAction   = 'Réinitialiser Paramétrages';
$lang->mail->resend        = 'Renvoyer';
$lang->mail->resendAction  = 'Renvoyer mail';
$lang->mail->browse        = 'Liste Email';
$lang->mail->delete        = 'Supprimer mail';
$lang->mail->ztCloud       = 'ZenTao CloudMail';
$lang->mail->gmail         = 'Gmail';
$lang->mail->sendCloud     = 'Notice SendCloud';
$lang->mail->batchDelete   = 'Suppression par Lot';
$lang->mail->sendcloudUser = 'Sync. Contact';
$lang->mail->agreeLicense  = 'Oui';
$lang->mail->disagree      = 'Non';

$lang->mail->turnon      = 'Notification Email';
$lang->mail->async       = 'Envoi Async';
$lang->mail->fromAddress = 'Expéditeur mail';
$lang->mail->fromName    = 'Expéditeur';
$lang->mail->domain      = 'Domaine ZenTao';
$lang->mail->host        = 'Serveur SMTP';
$lang->mail->port        = 'Port SMTP';
$lang->mail->auth        = 'Validation SMTP';
$lang->mail->username    = 'Compte SMTP';
$lang->mail->password    = 'Mot de passe SMTP';
$lang->mail->secure      = 'Cryptage';
$lang->mail->debug       = 'Debug';
$lang->mail->charset     = 'Charset';
$lang->mail->accessKey   = "Clé d'Accès";
$lang->mail->secretKey   = "Clé Secrète";
$lang->mail->license     = 'ZenTao CloudMail Notice';

$lang->mail->selectMTA = 'Sélect Type';
$lang->mail->smtp      = 'SMTP';

$lang->mail->syncedUser = 'Synchronis';
$lang->mail->unsyncUser = 'Non Synchronis';
$lang->mail->sync       = 'Synchronisation';
$lang->mail->remove     = 'Retirer';

$lang->mail->toList      = 'Destinataire';
$lang->mail->ccList      = 'Copie ';
$lang->mail->subject     = 'Sujet';
$lang->mail->createdBy   = 'Expéditeur';
$lang->mail->createdDate = 'Création';
$lang->mail->sendTime    = 'Envoi';
$lang->mail->status      = 'Statut';
$lang->mail->failReason  = 'Raison';

$lang->mail->statusList['wait']   = 'en Attente';
$lang->mail->statusList['sended'] = 'Envoy';
$lang->mail->statusList['fail']   = 'Echec';

$lang->mail->turnonList[1]  = 'On';
$lang->mail->turnonList[0] = 'Off';

$lang->mail->asyncList[1] = 'Oui';
$lang->mail->asyncList[0] = 'Non';

$lang->mail->debugList[0] = 'Off';
$lang->mail->debugList[1] = 'Normal';
$lang->mail->debugList[2] = 'Haute';

$lang->mail->authList[1]  = 'Oui';
$lang->mail->authList[0] = 'Non';

$lang->mail->secureList['']    = 'Plain';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->more           = 'Plus';
$lang->mail->noticeResend   = 'Le mail a été renvoyé !';
$lang->mail->inputFromEmail = 'Expéditeur Email';
$lang->mail->nextStep       = 'Suivant';
$lang->mail->successSaved   = 'Paramétrage Email sauvegardés.';
$lang->mail->setForUser     = 'Could not test mail configure because the users are without mail in system. Please set mail for user first.';
$lang->mail->testSubject    = 'mail de test';
$lang->mail->testContent    = 'Les Paramétrages Email sont ok !';
$lang->mail->successSended  = 'Envoyé !';
$lang->mail->confirmDelete  = 'Voulez-vous le supprimer ?';
$lang->mail->sendmailTips   = "Note : l'expéditeur ne recevra pas ce mail.";
$lang->mail->needConfigure  = "Les paramétrages Email n'ont pas été trouvés. Commencez par paramétrer l'Email.";
$lang->mail->connectFail    = 'Echec de Connexion à ZenTao.';
$lang->mail->centifyFail    = 'Echec de Vérification. La clé secrète peut avoir été changée. Essayez à nouveau !';
$lang->mail->nofsocket      = 'Les fonctions fsocket sont désactivées. Les mails ne peuvent pas être envoyés. Modifiez allow_url_fopen dans php.ini pour ouvrir Onopenssl, et redémarrez Apache.';
$lang->mail->noOpenssl      = 'Activez Onopenssl, et redémarrez Apache.';
$lang->mail->disableSecure  = 'Pas de openssl. ssl et tls sont inactifs.';
$lang->mail->sendCloudFail  = 'Echec. Raison :';
$lang->mail->sendCloudHelp  = <<<EOD



EOD;
$lang->mail->sendCloudSuccess = 'Envoy';
$lang->mail->closeSendCloud   = 'Fermer SendCloud';
$lang->mail->addressWhiteList = "Ajoutez le à la liste blanche de votre serveur de mail pour éviter d'être bloqué.";
$lang->mail->ztCloudNotice    = <<<EOD









EOD;

$lang->mail->placeholder = new stdclass();
$lang->mail->placeholder->password = "Certains serveurs de mails demandent un code d'autorisation, consultez le service d'Emails de votre provider.";
