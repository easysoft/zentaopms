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
ZenTao ALM is an Open Source software released under <a href='http://zpl.pub/page/zplv12.html' target='_blank'>Z Public License</a>. It integrates with Product Management, Project Management, QA Management, Document Management, Todos Management, Company Management etc. ZenTao is the best choice for software project management.

ZenTao ALM is built on PHP + MySQL and based on ZentaoPHP framework, an independent framework developed by our team. Third-party developers/organizations can develop extensions or customize for their requirements.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM is developed by <strong><a href='http://easysoft.ltd' target='_blank' class='text-danger'>Nature Easy Soft Co., LTD</a></strong>.
Official Website: <a href='https://www.zentao.pm' target='_blank'>https://www.zentao.pm</a>
Technical Support: <a href='https://www.zentao.pm/forum/' target='_blank'>https://www.zentao.pm/forum/</a>
LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>Nature Easy Soft</a> 
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>Nature Easy Soft</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

You are installing ZenTao <strong class='text-danger'>%s</strong>.
EOT;

$lang->install->newReleased= "<strong class='text-danger'>Notice</strong>：Official Website has the latest version<strong class='text-danger'>%s</strong>, released on %s.";
$lang->install->or         = 'Or';
$lang->install->checking   = 'System Checkup';
$lang->install->ok         = 'Passed(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not Loaded';
$lang->install->exists     = 'Found ';
$lang->install->notExists  = 'Not found ';
$lang->install->writable   = 'Writable ';
$lang->install->notWritable= 'Not Writable ';
$lang->install->phpINI     = 'PHP ini File';
$lang->install->checkItem  = 'Item';
$lang->install->current    = 'Current Setting';
$lang->install->result     = 'Result';
$lang->install->action     = 'Action';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version should be 5.2.0+';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Edit php.ini to load PDO extension.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Edit php.ini to load PDO_MySQL extension.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Edit php.ini to load JSON extension.';
$lang->install->openssl      = 'OpenSSL Extension';
$lang->install->opensslFail  = 'Edit php.ini to load openssl extension.';
$lang->install->mbstring     = 'Mbstring Extension';
$lang->install->mbstringFail = 'Edit php.ini to load mbstring extension.';
$lang->install->zlib         = 'Zlib Extension';
$lang->install->zlibFail     = 'Edit php.ini to load zlib extension.';
$lang->install->curl         = 'Curl Extension';
$lang->install->curlFail     = 'Edit php.ini to load curl extension.';
$lang->install->filter       = 'Filter Extension';
$lang->install->filterFail   = 'Edit the php.ini file to load filter extension.';
$lang->install->gd           = 'GD Extension';
$lang->install->gdFail       = 'Edit the php.ini file to load gd extension.';
$lang->install->iconv        = 'Iconv Extension';
$lang->install->iconvFail    = 'Edit the php.ini file to load iconv extension.';
$lang->install->tmpRoot      = 'Temp Directory';
$lang->install->dataRoot     = 'Uploaded File Directory';
$lang->install->session      = 'Session Save Path';
$lang->install->sessionFail  = 'Edit the php.ini file to set session.save_path.';
$lang->install->mkdirWin     = '<p>%s directory has to be created.<br /> Run <code>mkdir %s</code> to create it.</p>';
$lang->install->chmodWin     = ' "%s" privilege has to be changed.';
$lang->install->mkdirLinux   = '<p>%s directory has to be created.<br /> Run <code>mkdir -p %s</code> to create it.</p>';
$lang->install->chmodLinux   = ' "%s" permison has to be changed.<br /> Run <code>chmod o=rwx -R %s</code> to change it.';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Default Language';
$lang->install->dbHost         = 'Database Host';
$lang->install->dbHostNote     = 'If 127.0.0.1 is not accessible, try localhost.';
$lang->install->dbPort         = 'Host Port';
$lang->install->dbEncoding     = 'Database Charset';
$lang->install->dbUser         = 'Database Username';
$lang->install->dbPassword     = 'Database Password';
$lang->install->dbName         = 'Database Name';
$lang->install->dbPrefix       = 'Table Prefix';
$lang->install->clearDB        = 'Clean up existing data';
$lang->install->importDemoData = 'Import Demo Data';
$lang->install->working        = 'Operation Mode';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Application Lifecycle Management';
$lang->install->workingList['onlyTest']  = 'Only Test Management';
$lang->install->workingList['onlyStory'] = 'Only Story Management';
$lang->install->workingList['onlyTask']  = 'Only Task Management';

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
$lang->install->save2File  = '<div class="alert alert-warning">Copy the content in the text box above and save it to "<strong> %s </strong>". You can change this configuration file later.</div>';
$lang->install->saved2File = 'The configuration file has been saved to " <strong>%s</strong> ". You can change this file later.';
$lang->install->errorNotSaveConfig = 'The configuration file is not saved.';

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
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Create recurring jenkins';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Synchronize Jenkins Status';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Execute Jenkins';

$lang->install->success  = "Installé !";
$lang->install->login    = 'Login ZenTao';
$lang->install->register = "S'enregistrer sur la Communauté ZenTao";

$lang->install->joinZentao = <<<EOT
<p>Vous avez installé ZenTao %s.<strong class='text-danger'> Supprimez install.php asap</strong>.</p><p>Note : Afin d'obtenir les dernières infos de ZenTao, enregistrz-vous chez ZenTao(<a href='https://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo');

$lang->install->promotion     = "Products also from Nature Easy Soft team:";
$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'http://www.zsite.net';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Article, Blog, Manual, Member, Shop, Forum, Feedback……</li>
  <li>Customize page freely by theme, effect, widget, css, js and layout</li>
  <li>Support desktop and mobile in one system</li>
  <li>Deeply optimized for search engine</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'http://www.zdoo.com';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>CRM: Customer Management and Order Tracking</li>
  <li>OA: Approve, Announce, Trip, Leave and so on. </li>
  <li>Project，Task and Document management </li>
  <li>Money: Income, Expense, Transfer, Invest and Debt</li>
</ul>
EOD;
