<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->install = new stdclass();

$lang->install->common  = 'Installer';
$lang->install->next    = 'Suivant';
$lang->install->pre     = 'Retour';
$lang->install->reload  = 'Rafraichir';
$lang->install->error   = 'Erreur ';

$lang->install->officeDomain     = 'https://www.zentao.pm';

$lang->install->start            = 'Commencez';
$lang->install->keepInstalling   = "Continuez l'installation de cette version";
$lang->install->seeLatestRelease = 'Voir dernière version';
$lang->install->welcome          = "Merci d'avoir choisi ZenTao !";
$lang->install->license          = 'ZenTao est sous Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->desc             = <<<EOT
ZenTao ALM est un logiciel Open Source publié sous <a href='http://zpl.pub/page/zplv12.html' target='_blank'>Z Public License</a>. Il intègre la gestion de produits, la gestion de projets, la gestion de l'assurance qualité, la gestion de documents, la gestion des agendas, la gestion de sociétés, etc. ZenTao est le meilleur choix pour la gestion de projets logiciels..

ZenTao ALM est développé en PHP + MySQL et basé sur le framework ZentaoPHP, un framework independant développé par notre équipe. Les développeurs / organisations tiers peuvent développer des extensions ou personnaliser selon leurs besoins.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM est développé par <strong><a href='http://easysoft.ltd' target='_blank' class='text-danger'>Nature Easy Soft Co., LTD</a></strong>.
Site Officiel : <a href='https://www.zentao.pm' target='_blank'>https://www.zentao.pm</a>
Support Technique: <a href='https://www.zentao.pm/forum/' target='_blank'>https://www.zentao.pm/forum/</a>
LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>Nature Easy Soft</a> 
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>Nature Easy Soft</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

Vous installez ZenTao <strong class='text-danger'>%s</strong>.
EOT;

$lang->install->newReleased= "<strong class='text-danger'>Notice</strong>：Le site officiel a la dernière version <strong class='text-danger'>%s</strong>, sortie le %s.";
$lang->install->or         = 'Ou';
$lang->install->checking   = 'System Checkup';
$lang->install->ok         = 'Passed(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not Loaded';
$lang->install->exists     = 'Trouvé ';
$lang->install->notExists  = 'Non trouvé ';
$lang->install->writable   = 'Inscriptible ';
$lang->install->notWritable= 'Non inscriptible ';
$lang->install->phpINI     = 'PHP ini File';
$lang->install->checkItem  = 'Item';
$lang->install->current    = 'Param. courants';
$lang->install->result     = 'Résultat';
$lang->install->action     = 'Action';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version devrait être 5.2.0 ou plus';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Editez php.ini pour charger PDO extension.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Editez php.ini pour charger PDO_MySQL extension.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Editez php.ini pour charger JSON extension.';
$lang->install->openssl      = 'OpenSSL Extension';
$lang->install->opensslFail  = 'Editez php.ini pour charger openssl extension.';
$lang->install->mbstring     = 'Mbstring Extension';
$lang->install->mbstringFail = 'Editez php.ini pour charger mbstring extension.';
$lang->install->zlib         = 'Zlib Extension';
$lang->install->zlibFail     = 'Editez php.ini pour charger zlib extension.';
$lang->install->curl         = 'Curl Extension';
$lang->install->curlFail     = 'Editez php.ini pour charger curl extension.';
$lang->install->filter       = 'Filter Extension';
$lang->install->filterFail   = 'Editez le fichier php.ini pour charger filter extension.';
$lang->install->gd           = 'GD Extension';
$lang->install->gdFail       = 'Editez le fichier php.ini pour charger gd extension.';
$lang->install->iconv        = 'Iconv Extension';
$lang->install->iconvFail    = 'Editez le fichier php.ini pour charger iconv extension.';
$lang->install->tmpRoot      = 'Répertoire Temporaire';
$lang->install->dataRoot     = "Répertoire d'Upload";
$lang->install->session      = "Chemin d'enregistrement des sessions";
$lang->install->sessionFail  = 'Editez le fichier php.ini pour définier session.save_path.';
$lang->install->mkdirWin     = '<p>Le répertoire %s doit être créé.<br /> Exécutez <code>mkdir %s</code> pour le créer.</p>';
$lang->install->chmodWin     = ' "%s" privilege has to be changed.';
$lang->install->mkdirLinux   = '<p>Le répertoire %s doit être créé.<br /> Exécutez <code>mkdir -p %s</code> pour le créer.</p>';
$lang->install->chmodLinux   = 'Les permission de "%s" doivent être modifiées.<br /> Exécutez <code>chmod o=rwx -R %s</code> pour les changer.';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Langue par défaut';
$lang->install->dbHost         = 'Database Serveur';
$lang->install->dbHostNote     = "If 127.0.0.1 n'est pas accessible, essayez localhost.";
$lang->install->dbPort         = 'Port Serveur';
$lang->install->dbEncoding     = 'Database Charset';
$lang->install->dbUser         = 'Database Username';
$lang->install->dbPassword     = 'Database Password';
$lang->install->dbName         = 'Database Name';
$lang->install->dbPrefix       = 'Table Prefix';
$lang->install->clearDB        = 'Nettoyer les données existantes';
$lang->install->importDemoData = 'Importer données de Démo';
$lang->install->working        = 'Operation Mode';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Application Lifecycle Management';
$lang->install->workingList['onlyTest']  = 'Seulement Test Management';
$lang->install->workingList['onlyStory'] = 'Seulement Story Management';
$lang->install->workingList['onlyTask']  = 'Seulement Task Management';

$lang->install->errorConnectDB      = 'Echec de connexion à la base. ';
$lang->install->errorDBName         = 'Le nom de la base ne doit pas contenir de “.” ';
$lang->install->errorCreateDB       = 'Echec de création de la base.';
$lang->install->errorTableExists    = "La base existe. Si ZenTao a été installé précédemment, revenez à l'étape précédente et supprimez les données. Ensuite continuez l'installation.";
$lang->install->errorCreateTable    = 'Echec en création de la base.';
$lang->install->errorImportDemoData = "Echec de l'importation des données de démo.";

$lang->install->setConfig  = 'Créer fichier de configuration';
$lang->install->key        = 'Objet';
$lang->install->value      = 'Valeur';
$lang->install->saveConfig = 'Sauver config';
$lang->install->save2File  = '<div class="alert alert-warning">Copiez le contenu dans la zone de texte ci-dessus et enregistrez-le dans "<strong> %s </strong>". Vous pourrez modifier ce fichier de configuration ultérieurement.</div>';
$lang->install->saved2File = 'Le fichier de configuration a été enregistré dans " <strong>%s</strong> ". Vous pouvez modifier ce fichier ultérieurement.';
$lang->install->errorNotSaveConfig = "Le fichier de configuration n'est pas enregistré.";

$lang->install->getPriv  = 'Paramétrage Admin';
$lang->install->company  = 'Nom Entreprise';
$lang->install->account  = 'Compte Admin';
$lang->install->password = 'Mot de Passe Admin';
$lang->install->errorEmptyPassword = 'Password ne doit pas être vide.';

$lang->install->groupList['ADMIN']['name']   = 'Admin';
$lang->install->groupList['ADMIN']['desc']   = 'Administrateur';
$lang->install->groupList['DEV']['name']     = 'Dev.';
$lang->install->groupList['DEV']['desc']     = 'Développeur';
$lang->install->groupList['QA']['name']      = 'QA';
$lang->install->groupList['QA']['desc']      = 'Testeur';
$lang->install->groupList['PM']['name']      = 'PM';
$lang->install->groupList['PM']['desc']      = 'Project Manager';
$lang->install->groupList['PO']['name']      = 'PO';
$lang->install->groupList['PO']['desc']      = 'Product Owner';
$lang->install->groupList['TD']['name']      = 'Dev. Manager';
$lang->install->groupList['TD']['desc']      = 'Développement Manager';
$lang->install->groupList['PD']['name']      = 'PD';
$lang->install->groupList['PD']['desc']      = 'Product Director';
$lang->install->groupList['QD']['name']      = 'QD';
$lang->install->groupList['QD']['desc']      = 'Test Manager';
$lang->install->groupList['TOP']['name']     = 'Senior';
$lang->install->groupList['TOP']['desc']     = 'Senior Manager';
$lang->install->groupList['OTHERS']['name']  = 'Autres';
$lang->install->groupList['OTHERS']['desc']  = 'Autres utilisateurs';
$lang->install->groupList['LIMITED']['name'] = 'Utilisateur restreint';
$lang->install->groupList['LIMITED']['desc'] = 'Les utilisateurs peuvent seulement éditer ce qui les concernent.';

$lang->install->cronList[''] = 'Moniteur Cron';
$lang->install->cronList['moduleName=project&methodName=computeburn']   = 'Mise à jour Graphe Burndown';
$lang->install->cronList['moduleName=report&methodName=remind']         = 'Rappel Tâches quotidiennes';
$lang->install->cronList['moduleName=svn&methodName=run']               = 'Synchroniser SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = 'Synchroniser GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = 'Sauvegardes';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = 'Désynchroniser envoi Messages';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = 'Désynchroniser envoi Webhooks';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = 'Suppression Logs échus';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = 'Créer tâches récurrentes';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Créer des Jenkins récurrents';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Synchroniser le statut Jenkins';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Executer Jenkins';

$lang->install->success  = "Installé !";
$lang->install->login    = 'Login ZenTao';
$lang->install->register = "S'enregistrer sur la Communauté ZenTao";

$lang->install->joinZentao = <<<EOT
<p>Vous avez installé ZenTao %s.<strong class='text-danger'> Supprimez install.php asap</strong>.</p><p>Note : Afin d'obtenir les dernières infos de ZenTao, enregistrz-vous chez ZenTao(<a href='https://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo');

$lang->install->promotion     = "Autres produits de l'équipe Nature Easy Soft :";
$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'http://www.zsite.net';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Article, Blog, Manuel, Membre, Boutique, Forum, Commentaires……</li>
  <li>Personnalisez la page librement par thème, effet, widget, css, js et mise en page</li>
  <li>Prise en charge ordinateurs de bureau et mobile dans un seul système</li>
  <li>Entièrement optimisé pour les moteurs de recherche</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'http://www.zdoo.com';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>CRM: gestion des clients et suivi des commandes</li>
  <li>OA: approuver, annoncer, voyager, partir, etc.. </li>
  <li>Projet management Gestion des tâches et des documents</li>
  <li>Argent: revenu, dépenses, transfert, investissement et dette</li>
</ul>
EOD;
