<?php
/**
 * The user module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->user->common           = 'Utilisateur';
$lang->user->id               = 'ID';
$lang->user->company          = 'Entreprise';
$lang->user->dept             = 'Compartiment';
$lang->user->account          = 'Compte';
$lang->user->password         = 'Mot de Passe';
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
$lang->user->visits           = 'Nb Visites';
$lang->user->ip               = 'Dernière IP';
$lang->user->last             = 'Dernier Login';
$lang->user->ranzhi           = 'Compte Zdoo';
$lang->user->ditto            = 'Ditto';
$lang->user->originalPassword = 'Ancien Password';
$lang->user->newPassword      = 'Nouveau Password';
$lang->user->verifyPassword   = 'Mot de Passe';
$lang->user->resetPassword    = 'Password oublié ?';
$lang->user->score            = 'Score';

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

$lang->user->saveTemplate          = 'Sauver comme Modèle';
$lang->user->setPublic             = 'Définir comme Modèle Public';
$lang->user->deleteTemplate        = 'Supprimer ce Modèle';
$lang->user->setTemplateTitle      = 'Entrez le titre du Modèle.';
$lang->user->applyTemplate         = 'Modèles';
$lang->user->confirmDeleteTemplate = 'Voulez-vous vraiment supprimer ce modèle ?';
$lang->user->setPublicTemplate     = 'Définir comme Modèle Public';
$lang->user->tplContentNotEmpty    = 'Le contenu du modèle ne peut pas être vide !';

$lang->user->profile     = 'Profil';
$lang->user->project     = $lang->projectCommon . 's';
$lang->user->task        = 'Tâches';
$lang->user->bug         = 'Bugs';
$lang->user->test        = 'Test';
$lang->user->testTask    = 'Recettes';
$lang->user->testCase    = 'CasTests';
$lang->user->schedule    = 'Agenda';
$lang->user->todo        = 'Todo List';
$lang->user->story       = 'Stories';
$lang->user->dynamic     = 'Historique';

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

$lang->user->errorDeny    = "Désolé, votre accès à <b>%s</b> of <b>%s</b> est refusé. Veuillez contacter votre administrateur pour obtenir des privilèges. Cliquez sur Retour pour revenir.";
$lang->user->errorView    = "Désolé, votre accès en consultation <b>%s</b> est refusé. Veuillez contacter votre administrateur pour obtenir des privilèges. Cliquez sur Retour pour revenir.";
$lang->user->loginFailed  = "Echec de connexion. Vérifiez votre login et mot de passe.";
$lang->user->lockWarning  = "Vous avez %s essais.";
$lang->user->loginLocked  = "Votre compte est bloqué. Contactez l'administrateur pour débloquer votre compte ou attendez %s minutes pour une nouvelle tentative.";
$lang->user->weakPassword = "Votre mot de passe ne respecte pas les règles de sécurité.";
$lang->user->errorWeak    = "Les mots de passe ne peuvent pas utiliser ces [%s] mots de passe faibles couramment utilisés.";

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

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Faible</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Bon</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Fort</span>";

$lang->user->statusList['active'] = 'Actif';
$lang->user->statusList['delete'] = 'Supprimé';

$lang->user->personalData['createdTodo']  = 'Entrées Agenda Créées';
$lang->user->personalData['createdStory'] = 'Stories Créées';
$lang->user->personalData['finishedTask'] = 'Tâches Terminées';
$lang->user->personalData['resolvedBug']  = 'Bugs Résolus';
$lang->user->personalData['createdCase']  = 'CasTests Créés';

$lang->user->keepLogin['on']      = 'Rester connecté';
$lang->user->loginWithDemoUser    = "Se connecté en tant qu'invité :";

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

$lang->user->placeholder->passwordStrength[1] = '>= 6 lettres et chiffres';
$lang->user->placeholder->passwordStrength[2] = '>= 10 lettres, chiffres et caractères spéciaux';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，compte doit être >= 3 lettres, souligné ou chiffres";
$lang->user->error->accountDupl   = "ID %s，compte déjà utilisé par quelqu'un d'autre.";
$lang->user->error->realname      = "ID %s，doit être votre véritable nom";
$lang->user->error->password      = "ID %s，Mot de passe doit être >= 6 caractères.";
$lang->user->error->mail          = "ID %s，entrez une adresse mail valide";
$lang->user->error->reserved      = "ID %s，compte réservé. Vous ne pouvez pas l'utiliser.";
$lang->user->error->weakPassword   = "ID %s，la force du mot de passe est inférieure au paramètrage système.";
$lang->user->error->dangerPassword = "ID %s，Les mots de passe ne peuvent pas utiliser ces [%s] mots de passe faibles couramment utilisés.";

$lang->user->error->verifyPassword   = "Vérification en échec. Entrez votre Mot de Passe de Connexion.";
$lang->user->error->originalPassword = "Ancien Mot de Passe incorrect.";

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contacts';
$lang->user->contacts->listName = 'Nom de la Liste';
$lang->user->contacts->userList = 'Liste Utilisateurs';

$lang->user->contacts->manage        = 'Gérer la Liste';
$lang->user->contacts->contactsList  = 'Liste de Contacts';
$lang->user->contacts->selectedUsers = 'Utilisateurs';
$lang->user->contacts->selectList    = 'Liste';
$lang->user->contacts->createList    = 'Créer Liste';
$lang->user->contacts->noListYet     = 'Aucune liste de contacts existante. Créez-en une première.';
$lang->user->contacts->confirmDelete = 'Voulez-vous supprimer cette liste de contacts ?';
$lang->user->contacts->or            = ' ou ';

$lang->user->resetFail       = "ECHEC. Vérifiez votre compte.";
$lang->user->resetSuccess    = "Reset ! Utilisez votre nouveau mot de passe pour vous connecter.";
$lang->user->noticeResetFile = "<h5>Contactez l'administrateur pour réinitialiser votre mot de passe.</h5>
    <h5>Si vous êtres l'administrateur, connectez-vous au serveur Zentao et créez un fichier vide nommé <span> '%s' </span>.</h5>
    <p>Note :</p>
    <ol>
    <li>Gardez ce fichier vide.</li>
    <li>Si le fichier existe déjà, supprimez le et créez le à nouveau.</li><li>Bonne chance.</li>
    </ol>";
