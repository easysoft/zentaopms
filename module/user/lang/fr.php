<?php
/**
 * The user module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->user->common           = 'Utilisateur';
$lang->user->id               = 'ID';
$lang->user->inside           = 'Inside Members';
$lang->user->outside          = 'Outside Members';
$lang->user->company          = 'Entreprise';
$lang->user->dept             = 'Compartiment';
$lang->user->account          = 'Compte';
$lang->user->password         = 'Mot de Passe';
$lang->user->password1        = 'Mot de Passe';
$lang->user->password2        = 'Répétez Password';
$lang->user->role             = 'Rôle';
$lang->user->group            = 'Groupe de Privilèges';
$lang->user->realname         = 'Nom';
$lang->user->nickname         = 'Pseudo';
$lang->user->commiter         = 'Compte SVN/GIT';
$lang->user->birthyear        = 'DOB';
$lang->user->gender           = 'Sexe';
$lang->user->email            = 'Email';
$lang->user->basicInfo        = 'Info. de base';
$lang->user->accountInfo      = 'Info. Compte';
$lang->user->verify           = 'Vérification';
$lang->user->contactInfo      = 'Contact';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = 'Mobile';
$lang->user->phone            = 'Tél.Fixe';
$lang->user->weixin           = 'WeChat';
$lang->user->dingding         = 'DingDing';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = 'Adresse';
$lang->user->zipcode          = 'Code Postal';
$lang->user->join             = 'Ajouté le';
$lang->user->priv             = 'Autorité';
$lang->user->visits           = 'Nb Visites';
$lang->user->visions          = 'Version Type';
$lang->user->ip               = 'Dernière IP';
$lang->user->last             = 'Dernier Login';
$lang->user->ranzhi           = 'Compte Zdoo';
$lang->user->ditto            = 'Ditto';
$lang->user->originalPassword = 'Ancien Password';
$lang->user->newPassword      = 'Nouveau Password';
$lang->user->verifyPassword   = 'Mot de Passe';
$lang->user->forgetPassword   = 'Password oublié ?';
$lang->user->score            = 'Score';
$lang->user->name             = 'Name';
$lang->user->type             = 'User Type';
$lang->user->cropAvatar       = 'Crop Avatar';
$lang->user->cropAvatarTip    = 'Drag and drop the box to select the image clipping range.';
$lang->user->cropImageTip     = 'The image used is too small, the recommended image size is at least 48x48, the current image size is %s';
$lang->user->captcha          = 'Captcha';
$lang->user->avatar           = 'Avatar';
$lang->user->birthday         = 'Birthday';
$lang->user->nature           = 'Nature';
$lang->user->analysis         = 'Analysis';
$lang->user->strategy         = 'Strategy';
$lang->user->fails            = 'number of failures';
$lang->user->locked           = 'Lock Time';
$lang->user->scoreLevel       = 'Score Level';
$lang->user->clientStatus     = 'Client Status';
$lang->user->clientLang       = 'Client Language';
$lang->user->programs         = 'Program';
$lang->user->products         = $lang->productCommon;
$lang->user->projects         = $lang->projectCommon;
$lang->user->sprints          = $lang->execution->common;
$lang->user->identity         = 'Identity';
$lang->user->switchVision     = 'Switch to %s';
$lang->user->submit           = 'Submit';
$lang->user->resetPWD         = 'Reset Password';
$lang->user->resetPwdByAdmin  = 'Reset password via admin';
$lang->user->resetPwdByMail   = 'Reset password via email';

$lang->user->abbr = new stdclass();
$lang->user->abbr->id          = 'ID';
$lang->user->abbr->password2AB = 'Répétez Password';
$lang->user->abbr->addressAB   = 'Adresse';
$lang->user->abbr->joinAB      = 'Ajouté le';

$lang->user->legendBasic        = 'Informations de Base';
$lang->user->legendContribution = 'Contribution';

$lang->user->index         = "Accueil";
$lang->user->view          = "Détail utilisateur";
$lang->user->create        = "Ajout Utilisateur";
$lang->user->batchCreate   = "Ajout par lot";
$lang->user->edit          = "Modification Utilisateur";
$lang->user->batchEdit     = "Modification par lot";
$lang->user->unlock        = "Débloquer Utilisateur";
$lang->user->delete        = "Supprimer Utilisateur";
$lang->user->unbind        = "Dissocier de Zdoo";
$lang->user->login         = "Login";
$lang->user->bind          = "Bind User";
$lang->user->oauthRegister = "Register a new account";
$lang->user->mobileLogin   = "Mobile";
$lang->user->editProfile   = "Editer Profil";
$lang->user->deny          = "Your access is denied.";
$lang->user->confirmDelete = "Voulez-vous supprimer cet utilisateur ?";
$lang->user->confirmUnlock = "Voulez-vous débloquer cet utilisateur ?";
$lang->user->confirmUnbind = "Voulez-vous dissocier cet utilisateur de Zdoo ?";
$lang->user->relogin       = "Reconnexion";
$lang->user->asGuest       = "Invité";
$lang->user->goback        = "Retour";
$lang->user->deleted       = '(Supprimé)';
$lang->user->search        = 'Recherche';
$lang->user->else          = 'Else';

$lang->user->saveTemplate          = 'Sauver comme Modèle';
$lang->user->setPublic             = 'Définir comme Modèle Public';
$lang->user->deleteTemplate        = 'Supprimer ce Modèle';
$lang->user->setTemplateTitle      = 'Entrez le titre du Modèle.';
$lang->user->applyTemplate         = 'Modèles';
$lang->user->confirmDeleteTemplate = 'Voulez-vous vraiment supprimer ce modèle ?';
$lang->user->setPublicTemplate     = 'Définir comme Modèle Public';
$lang->user->tplContentNotEmpty    = 'Le contenu du modèle ne peut pas être vide !';
$lang->user->sendEmailSuccess      = 'An email has been sent to your mailbox. Please check it.';
$lang->user->linkExpired           = 'The link has expired, please apply again.';

$lang->user->profile   = 'Profil';
$lang->user->project   = $lang->executionCommon . 's';
$lang->user->execution = 'Execution';
$lang->user->task      = 'Tâches';
$lang->user->bug       = 'Bugs';
$lang->user->test      = 'Test';
$lang->user->testTask  = 'Recettes';
$lang->user->testCase  = 'CasTests';
$lang->user->issue     = 'Issue';
$lang->user->risk      = 'Risk';
$lang->user->schedule  = 'Agenda';
$lang->user->todo      = 'Todo List';
$lang->user->story     = 'Stories';
$lang->user->dynamic   = 'Historique';

$lang->user->openedBy    = 'Créé par %s';
$lang->user->assignedTo  = 'Assigné à %s';
$lang->user->finishedBy  = 'Terminé par %s';
$lang->user->resolvedBy  = 'Résolu par %s';
$lang->user->closedBy    = 'Fermé par %s';
$lang->user->reviewedBy  = 'Validé par %s';
$lang->user->canceledBy  = 'Annulé par %s';

$lang->user->testTask2Him = 'Recette assignée à %s';
$lang->user->case2Him     = 'CasTest assigné à %s';
$lang->user->caseByHim    = 'CasTest créé par %s';

$lang->user->errorDeny    = "Sorry, your access to <b>%2\$s</b> of <b>%1\$s</b> is denied. Please contact your Admin to get privileges. Return to home page or login again.";
$lang->user->errorView    = "Sorry, your access view <b>%s</b> is denied. Please contact your Admin to get privileges. Return to home page or login again.";
$lang->user->loginFailed  = "Echec de connexion. Vérifiez votre login et mot de passe.";
$lang->user->lockWarning  = "Vous avez %s essais.";
$lang->user->loginLocked  = "Votre compte est bloqué. Contactez l'administrateur pour débloquer votre compte ou attendez %s minutes pour une nouvelle tentative.";
$lang->user->weakPassword = "Votre mot de passe ne respecte pas les règles de sécurité.";
$lang->user->errorWeak    = "Les mots de passe ne peuvent pas utiliser ces [%s] mots de passe faibles couramment utilisés.";
$lang->user->errorCaptcha = "Captcha Error";
$lang->user->loginExpired = 'System login has expired, please log in again :)';

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Développeur';
$lang->user->roleList['qa']     = 'Testeur';
$lang->user->roleList['pm']     = 'Scrum Master';
$lang->user->roleList['po']     = 'Product Owner';
$lang->user->roleList['td']     = 'Technical Manager';
$lang->user->roleList['pd']     = 'Product Manager';
$lang->user->roleList['qd']     = 'Tests Manager';
$lang->user->roleList['top']    = 'Senior Manager';
$lang->user->roleList['others'] = 'Autres';

$lang->user->genderList['m'] = 'Homme';
$lang->user->genderList['f'] = 'Femme';

$lang->user->thirdPerson['m'] = 'lui';
$lang->user->thirdPerson['f'] = 'lui';

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Faible</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Bon</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Fort</span>";

$lang->user->statusList['active'] = 'Actif';
$lang->user->statusList['delete'] = 'Supprimé';

$lang->user->personalData['createdTodos']        = 'Todos Created';
$lang->user->personalData['createdRequirements'] = "Requirements Created";
$lang->user->personalData['createdStories']      = "Stories Created";
$lang->user->personalData['finishedTasks']       = 'Tasks Finished';
$lang->user->personalData['createdBugs']         = 'Bugs Created';
$lang->user->personalData['resolvedBugs']        = 'Bugs Resolved';
$lang->user->personalData['createdCases']        = 'Cases Created';
$lang->user->personalData['createdRisks']        = 'Risks Created';
$lang->user->personalData['resolvedRisks']       = 'Risks Resolved';
$lang->user->personalData['createdIssues']       = 'Issues Created';
$lang->user->personalData['resolvedIssues']      = 'Issues Resolved';
$lang->user->personalData['createdDocs']         = 'Docs Created';

$lang->user->keepLogin['on']   = 'Rester connecté';
$lang->user->loginWithDemoUser = "Se connecté en tant qu'invité :";
$lang->user->scanToLogin       = 'Scan QR Code';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = 'Type';
$lang->user->tpl->title   = 'TPL Titre';
$lang->user->tpl->content = 'Contenu';
$lang->user->tpl->public  = 'Public';

$lang->usertpl = new stdclass();
$lang->usertpl->title = 'Template Name';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '>= 3 lettres, souligné et chiffres';
$lang->user->placeholder->password1 = '>= 6 caractères';
$lang->user->placeholder->role      = "Rôle affecte le contenu et l'ordre dans la liste des utilisateurs.";
$lang->user->placeholder->group     = "Groupe correspond aux privilèges utilisateurs.";
$lang->user->placeholder->commiter  = 'Compte SVN/Git';
$lang->user->placeholder->verify    = 'Entrez votre Mot de passe.';

$lang->user->placeholder->loginPassword = 'Enter your password';
$lang->user->placeholder->loginAccount  = 'Enter your account';
$lang->user->placeholder->loginUrl      = 'Enter your ZenTao address';
$lang->user->placeholder->email         = 'Enter your email';

$lang->user->placeholder->passwordStrength[0] = 'Le mot de passe doit ≥  6 caractè res.';
$lang->user->placeholder->passwordStrength[1] = '>= 6 lettres et chiffres';
$lang->user->placeholder->passwordStrength[2] = '>= 10 lettres, chiffres et caractères spéciaux';

$lang->user->placeholder->passwordStrengthCheck[0] = 'Le mot de passe doit ≥ 6 caractères.';
$lang->user->placeholder->passwordStrengthCheck[1] = 'Le mot de passe doit ≥ 6 caractères, combinaison de lettres majuscules, minuscules et de chiffres.';
$lang->user->placeholder->passwordStrengthCheck[2] = 'Le mot de passe doit ≥ 10 caractères, combinaison de lettres majuscules, minuscules, de chiffres et de symboles spéciaux.';

$lang->user->error = new stdclass();
$lang->user->error->account        = 'compte doit être >= 3 lettres, souligné ou chiffres';
$lang->user->error->accountDupl    = 'compte déjà utilisé par quelqu\'un d\'autre.';
$lang->user->error->realname       = 'doit être votre véritable nom';
$lang->user->error->visions        = 'must be version type';
$lang->user->error->password       = 'Mot de passe doit être >= 6 caractères.';
$lang->user->error->mail           = 'entrez une adresse mail valide';
$lang->user->error->reserved       = 'compte réservé. Vous ne pouvez pas l\'utiliser.';
$lang->user->error->weakPassword   = 'la force du mot de passe est inférieure au paramètrage système.';
$lang->user->error->dangerPassword = "Les mots de passe ne peuvent pas utiliser ces [%s] mots de passe faibles couramment utilisés.";

$lang->user->error->url              = "Invalid address. Please contact your ZenTao Admin.";
$lang->user->error->verify           = "Wrong account or password.";
$lang->user->error->verifyPassword   = "Vérification en échec. Entrez votre Mot de Passe de Connexion.";
$lang->user->error->originalPassword = "Ancien Mot de Passe incorrect.";
$lang->user->error->companyEmpty     = "Company name must be not empty.";
$lang->user->error->noAccess         = "This user is not from your department. You have no access to this user information.";
$lang->user->error->accountEmpty     = 'Account must be not empty !';
$lang->user->error->emailEmpty       = 'Email must be not empty !';
$lang->user->error->noUser           = 'Invalid account.';
$lang->user->error->noEmail          = 'The user does not register email. Please get in touch with the administrator to reset the password.';
$lang->user->error->errorEmail       = 'The account does not match the email. Please enter a new one.';
$lang->user->error->emailSetting     = 'No email is configured in the system. Contact the admin to reset the email.';
$lang->user->error->sendMailFail     = 'Message sending failed, please try again!';
$lang->user->error->loginTimeoutTip  = 'Échec de la connexion, veuillez vérifier si le service proxy est activé.';

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->executionTypeList['stage']  = 'Stage';
$lang->user->executionTypeList['sprint'] = $lang->iterationCommon;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contacts';
$lang->user->contacts->listName = 'Nom de la Liste';
$lang->user->contacts->userList = 'Liste Utilisateurs';

$lang->usercontact = new stdclass;
$lang->usercontact->listName = 'List Name';
$lang->usercontact->userList = 'Liste Utilisateurs';

$lang->user->contacts->manage        = 'Gérer la Liste';
$lang->user->contacts->contactsList  = 'Liste de Contacts';
$lang->user->contacts->selectedUsers = 'Utilisateurs';
$lang->user->contacts->selectList    = 'Liste';
$lang->user->contacts->createList    = 'Créer Liste';
$lang->user->contacts->noListYet     = 'Aucune liste de contacts existante. Créez-en une première.';
$lang->user->contacts->confirmDelete = 'Voulez-vous supprimer cette liste de contacts ?';
$lang->user->contacts->or            = ' ou ';

$lang->user->resetFail        = "ECHEC. Vérifiez votre compte.";
$lang->user->resetSuccess     = "Reset ! Utilisez votre nouveau mot de passe pour vous connecter.";
$lang->user->noticeDelete     = 'Do you want to delete "%s" from ZenTao?';
$lang->user->noticeHasDeleted = "This user has been deleted. If you want to view it, please go to the Admin-System-Data-Recycle to restore it.";
$lang->user->noticeResetFile  = "<h5>Contact the Administrator to reset your password.</h5>
    <h5>If you are, please login your Zentao host and create a file named <span> '%s' </span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create it again.</li>
    </ol>";
$lang->user->notice4Safe = "Warning: Weak password of one click package detected";
$lang->user->process4DIR = "It is detected that you may be using the one click installation package environment. Other sites in the environment are still using simple passwords. For security reasons, if you do not use other sites, please handle them in time. Delete or rename the %s directory. Visit: <a href='https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html' target='_blank'>https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html</a>";
$lang->user->process4DB  = "It is detected that you may be using the one click installation package environment. Other sites in the environment are still using simple passwords. For security reasons, if you do not use other sites, please handle them in time. Please login database and modify password field of zt_user table of %s database. Visit: <a href='https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html' target='_blank'>https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html</a>";
$lang->user->mkdirWin = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div>A tmp directory cannot be created. Make sure the directory <strong style='color:#ed980f'>%s</strong> exists and you have the right permission.</div>
    </td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div style='margin-bottom:8px;'>命令为：<strong style='color:#ed980f'>chmod 777 -R %s</strong>。</div>
    <div>A tmp directory cannot be created. Make sure the directory <strong style='color:#ed980f'>%s</strong> exists and you have the right permission.</div>
    <div style='margin-bottom:8px;'>Command: <strong style='color:#ed980f'>chmod 777 -R %s</strong>.</div>
    </td></tr></table></body></html>
EOT;

$lang->user->jumping = "This page will redirect to the previous page in <span id='time'>10</span> seconds. <a href='%s' id='redirect' class='btn primary'>Redirect Now</a>";

$lang->user->zentaoapp = new stdclass();
$lang->user->zentaoapp->logout = 'Logout';

$lang->user->featureBar['todo']['all']             = 'Assigned To Yourself';
$lang->user->featureBar['todo']['before']          = 'Unfinished';
$lang->user->featureBar['todo']['future']          = 'TBD';
$lang->user->featureBar['todo']['thisWeek']        = 'This Week';
$lang->user->featureBar['todo']['thisMonth']       = 'This Month';
$lang->user->featureBar['todo']['thisYear']        = 'This Year';
$lang->user->featureBar['todo']['assignedToOther'] = 'Assigned To Other';
$lang->user->featureBar['todo']['cycle']           = 'Recurrence';

$lang->user->featureBar['dynamic']['all']       = 'All';
$lang->user->featureBar['dynamic']['today']     = 'Today';
$lang->user->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->user->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->user->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->user->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->user->featureBar['dynamic']['lastMonth'] = 'Last Month';
