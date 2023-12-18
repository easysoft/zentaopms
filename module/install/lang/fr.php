<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common = 'Installer';
$lang->install->next   = 'Suivant';
$lang->install->pre    = 'Retour';
$lang->install->reload = 'Rafraichir';
$lang->install->error  = 'Erreur ';

$lang->install->officeDomain = 'https://www.zentao.pm';

$lang->install->start            = 'Commencez';
$lang->install->keepInstalling   = "Continuez l'installation de cette version";
$lang->install->seeLatestRelease = 'Voir dernière version';
$lang->install->welcome          = "Merci d'avoir choisi ZenTao !";
$lang->install->license          = 'ZenTao est sous Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->devopsDesc       = 'The underlying foundation of the DevOps platform is built upon cloud-native technologies such as Docker and Kubernetes (K8s). It incorporates an integrated application marketplace, allowing seamless installation of essential tools like code repositories, pipelines, and artifact libraries.';
$lang->install->desc             = <<<EOT
ZenTao ALM is an open source software released under <a href='http://zpl.pub/page/zplv12.html' target='_blank'>ZPL</a> or <a href='https://www.gnu.org/licenses/agpl-3.0.en.html' target='_blank'>AGPL</a> License. It integrates with Product Management, Project Management, Test Management, Document Management, CI Management, etc. ZenTao is a perfect choice for managing software development projects.

ZenTao ALM is built on PHP + MySQL + zentaoPHP which is an independent framework developed by ZenTao Software. Third-party developers/organizations can develop extensions or customize ZenTao accordingly.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM is developed by <strong><a href='https://en.easysoft.ltd' target='_blank' class='text-danger'>ZenTao Software</a></strong>.
Official Website: <a href='https://www.zentao.pm' target='_blank'>https://www.zentao.pm</a>
Technical Support: <a href='https://www.zentao.pm/forum/' target='_blank'>https://www.zentao.pm/forum/</a>
LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>ZenTao Software</a>
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>ZenTao Software</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

You are installing <strong class='text-danger'>ZenTao %s</strong>.
EOT;

$lang->install->selectMode          = "Select mode";
$lang->install->introduction        = "ZenTao 15.0+ Feature Introduction";
$lang->install->howToUse            = "How do you like to use ZenTao?";
$lang->install->guideVideo          = 'https://dl.cnezsoft.com/vedio/zentaoconcepteng0716.mp4';
$lang->install->introductionContent = <<<EOT
<div>
  <h4>Dear users,</h4>
  <p>Welcome to ZenTao project management system. ZenTao has two managment modes in version 15.0 and up. One is the classic management mode, providing two core features, Product and Project; the other is a new project management mode, with Program and Execution added. The following is an introduction to the new mode:</p>
  <div class='block-content'>
    <div class='block-details'><p class='block-title'><i class='icon icon-program'></i> <strong>Program</strong></p><p>Program is used to manage a group of products and projects, and the company executives or PMO can use it for strategic planning.</p></div>
    <div class='block-details block-right'>
      <p class='block-title'><i class='icon icon-product'></i> <strong>Product</strong></p>
      <p>Product is used to subdivide the company's strategy into requirements that can be developed, and the product manager can use it to make release plans.<p>
    </div>
    <div class='block-details'>
      <p class='block-title'><i class='icon icon-project'></i> <strong>Project</strong></p>
      <p>Project is used to organize the manpower for development, track and manage the project process, and complete the project in a faster, better, and less costly way.</p>
    </div>
    <div class='block-details block-right'>
      <p class='block-title'><i class='icon icon-run'></i> <strong>Execution</strong></p>
      <p>Execution is used to decompose, assign, and track tasks to ensure that project goals can be implemented by manpower.<p>
    </div>
  </div>
  <div class='text-center introduction-link'>
    <a href='https://dl.cnezsoft.com/zentao/zentaoconcept.pdf' target='_blank' class='btn btn-wide btn-info'><i class='icon icon-p-square'></i> Introduction Document</a>
    <a href='javascript:showVideo()' class='btn btn-wide btn-info'><i class='icon icon-video-play'></i> Introduction Video</a>
  </div>
</div>
EOT;

$lang->install->newReleased = "<strong class='text-danger'>Notice</strong>：Le site officiel a la dernière version <strong class='text-danger'>%s</strong>, sortie le %s.";
$lang->install->or          = 'Ou';
$lang->install->checking    = 'System Checkup';
$lang->install->ok          = 'Passed(√)';
$lang->install->fail        = 'Failed(×)';
$lang->install->loaded      = 'Loaded';
$lang->install->unloaded    = 'Not Loaded';
$lang->install->exists      = 'Trouvé ';
$lang->install->notExists   = 'Non trouvé ';
$lang->install->writable    = 'Inscriptible ';
$lang->install->notWritable = 'Non inscriptible ';
$lang->install->phpINI      = 'PHP ini File';
$lang->install->checkItem   = 'Item';
$lang->install->current     = 'Param. courants';
$lang->install->result      = 'Résultat';
$lang->install->action      = 'Action';

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
$lang->install->chmodLinux   = 'Les permission de "%s" doivent être modifiées.<br /> Exécutez <code>chmod 777 -R %s</code> pour les changer.';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Langue par défaut';
$lang->install->dbDriver       = 'Database Driver';
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

$lang->install->dbDriverList = array();
$lang->install->dbDriverList['mysql'] = 'MySQL';
$lang->install->dbDriverList['dm']    = 'DM8';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Application Lifecycle Management';

$lang->install->errorConnectDB      = 'Echec de connexion à la base. ';
$lang->install->errorDBName         = 'Le nom de la base ne doit pas contenir de “.” ';
$lang->install->errorCreateDB       = 'Echec de création de la base.';
$lang->install->errorTableExists    = "La base existe. Si ZenTao a été installé précédemment, revenez à l'étape précédente et supprimez les données. Ensuite continuez l'installation.";
$lang->install->errorCreateTable    = 'Echec en création de la base.';
$lang->install->errorEngineInnodb   = 'Your MySQL does not support InnoDB data table engine. Please modify it to MyISAM and try again.';
$lang->install->errorImportDemoData = "Echec de l'importation des données de démo.";

$lang->install->setConfig          = 'Créer fichier de configuration';
$lang->install->key                = 'Objet';
$lang->install->value              = 'Valeur';
$lang->install->saveConfig         = 'Sauver config';
$lang->install->save2File          = '<div class="text-warning">Copiez le contenu dans la zone de texte ci-dessus et enregistrez-le dans "<strong> %s </strong>". Vous pourrez modifier ce fichier de configuration ultérieurement.</div>';
$lang->install->saved2File         = 'Le fichier de configuration a été enregistré dans " <strong>%s</strong> ". Vous pouvez modifier ce fichier ultérieurement.';
$lang->install->errorNotSaveConfig = "Le fichier de configuration n'est pas enregistré.";
$lang->install->errorNotInitConfig = 'The configuration has not been created.';

global $app;
$lang->install->CSRFNotice = "CSRF defense has been enabled in the system. If you don't need it, contact the administrator to disable it manually in the {$app->basePath}config/config.php file.";

$lang->install->getPriv  = 'Paramétrage Admin';
$lang->install->company  = 'Nom Entreprise';
$lang->install->account  = 'Compte Admin';
$lang->install->password = 'Mot de Passe Admin';

$lang->install->placeholder = new stdclass();
$lang->install->placeholder->password = 'Le mot de passe doit ≥ 6 caractères, combinaison de lettres majuscules, minuscules et de chiffres.';

$lang->install->errorEmpty['company']  = "{$lang->install->company} should not be blank.";
$lang->install->errorEmpty['account']  = "{$lang->install->account} should not be blank.";
$lang->install->errorEmpty['password'] = "{$lang->install->password} should not be blank.";

$lang->install->langList['1'] = array('module' => 'process', 'key' => 'support', 'value' => 'Support Process');
$lang->install->langList['2'] = array('module' => 'process', 'key' => 'engineering', 'value' => 'Project Management');
$lang->install->langList['3'] = array('module' => 'process', 'key' => 'project', 'value' => 'Engineering Process');

$lang->install->processList['11'] = 'Project management';
$lang->install->processList['12'] = 'Project planning';
$lang->install->processList['13'] = 'Project monitoring';
$lang->install->processList['14'] = 'Risk management';
$lang->install->processList['15'] = 'Closing management';
$lang->install->processList['16'] = 'Quantitative Project management';
$lang->install->processList['17'] = 'Requirements development';
$lang->install->processList['18'] = 'Design and development';
$lang->install->processList['19'] = 'Implementation and testing';
$lang->install->processList['20'] = 'System test';
$lang->install->processList['21'] = 'Customer acceptance';
$lang->install->processList['22'] = 'Quality assurance';
$lang->install->processList['23'] = 'Configuration management';
$lang->install->processList['24'] = 'Metric analysis';
$lang->install->processList['25'] = 'Cause analysis and resolution';
$lang->install->processList['26'] = 'Decision analysis';

$lang->install->basicmeasList['2'] = array('name' => 'Initial size of project user requirements', 'unit' => 'Story points or function points', 'definition' => 'Sum of the size of the baseline version of the first CUSTOMER requirements specification for each product of the project');
$lang->install->basicmeasList['3'] = array('name' => 'Initial scale of project software requirements', 'unit' => 'Story points or function points', 'definition' => 'Sum of the size of the first software requirements specification baseline release for each product of the project');
$lang->install->basicmeasList['4'] = array('name' => 'Real-time scale of project user requirements', 'unit' => 'Story points or function points', 'definition' => 'The actual size of the project user requirements');
$lang->install->basicmeasList['5'] = array('name' => 'Real-time scale of project software requirements', 'unit' => 'Story points or function points', 'definition' => 'The actual scale of the project software requirements');
$lang->install->basicmeasList['6'] = array('name' => 'Estimated project size', 'unit' => 'Story points or function points', 'definition' => 'The estimated size of the project when it was originally estimated');
$lang->install->basicmeasList['8'] = array('name' => 'Project requirements phase planning days', 'unit' => 'Day', 'definition' => 'The sum of planned days for all requirements phases under the project');
$lang->install->basicmeasList['9'] = array('name' => 'Number of days planned during project design phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all design phases under the project');
$lang->install->basicmeasList['10'] = array('name' => 'Planned number of days during project development phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all development phases under the project');
$lang->install->basicmeasList['11'] = array('name' => 'Number of days planned for project test phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all test phases under the project');
$lang->install->basicmeasList['12'] = array('name' => 'Actual days of project requirements phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all requirements phases under the project');
$lang->install->basicmeasList['13'] = array('name' => 'Actual days of project design phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all design phases under the project');
$lang->install->basicmeasList['14'] = array('name' => 'Actual number of days during the project development phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all r&d phases under the project');
$lang->install->basicmeasList['15'] = array('name' => 'Actual number of days during the project test phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all test phases under the project');
$lang->install->basicmeasList['26'] = array('name' => 'Plan days by product demand phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all requirements phases under the product');
$lang->install->basicmeasList['27'] = array('name' => 'Plan days by product design stage', 'unit' => 'Day', 'definition' => 'The sum of planned days for all design phases under the product');
$lang->install->basicmeasList['28'] = array('name' => 'Planned days by product development phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all development phases under the product');
$lang->install->basicmeasList['29'] = array('name' => 'Plan days by product test phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all test phases under the product');
$lang->install->basicmeasList['30'] = array('name' => 'Actual days of product demand stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all requirement phases under the product');
$lang->install->basicmeasList['31'] = array('name' => 'Actual days of product design stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all design phases under the product');
$lang->install->basicmeasList['32'] = array('name' => 'By actual days of product development stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all development phases under the product');
$lang->install->basicmeasList['33'] = array('name' => 'By actual days of product test phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all testing phases under the product');
$lang->install->basicmeasList['34'] = array('name' => 'Real-time estimated working hours of project tasks', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all tasks under the project');
$lang->install->basicmeasList['35'] = array('name' => 'Total estimated real-time working hours of project requirements', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all requirements related tasks of the project');
$lang->install->basicmeasList['36'] = array('name' => 'Total estimated time of project design work in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all design-related tasks of the project');
$lang->install->basicmeasList['37'] = array('name' => 'Total estimated time of project development in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all development related tasks of the project');
$lang->install->basicmeasList['38'] = array('name' => 'Total estimated time of project test work in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all test related tasks of the project');
$lang->install->basicmeasList['39'] = array('name' => 'Actual man-hours consumed by project tasks', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours consumed for all tasks under the project');
$lang->install->basicmeasList['40'] = array('name' => 'The actual number of man-hours consumed by project demand work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours consumed by all demand-related tasks of the project');
$lang->install->basicmeasList['41'] = array('name' => 'The actual number of man-hours consumed by project design work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours consumed by all design-related tasks of the project');
$lang->install->basicmeasList['42'] = array('name' => 'The actual number of man-hours consumed by project development work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours consumed by all development related tasks of the project');
$lang->install->basicmeasList['43'] = array('name' => 'The actual number of man-hours consumed by the project testing work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours consumed by all test related tasks of the project');
$lang->install->basicmeasList['44'] = array('name' => 'Total estimated initial hours of project development work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all development related work in the first baseline release of the project plan');
$lang->install->basicmeasList['45'] = array('name' => 'Total estimated initial hours of project design work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all design-related work in the first baseline version of the project plan');
$lang->install->basicmeasList['46'] = array('name' => 'Total estimated initial work hours for project testing', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all test-related work in the first baseline release of the project plan');
$lang->install->basicmeasList['47'] = array('name' => 'Total estimated initial hours of work required for the project', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all requirements related work in the first baseline version of the project plan');
$lang->install->basicmeasList['48'] = array('name' => 'Total estimated initial work hours for project tasks', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all tasks in the first baseline version of the project plan');
$lang->install->basicmeasList['49'] = array('name' => 'The final estimated number of project development hours', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all development-related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['50'] = array('name' => 'Total estimated final work hours of project requirements', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all requirements related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['51'] = array('name' => 'The final estimated number of project testing hours', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all test related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['52'] = array('name' => 'The final estimated total working hours of project design work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all design-related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['53'] = array('name' => 'Total estimated final work hours of project tasks', 'unit' => 'Hour', 'definition' => 'The sum of the initial estimated man-hours for all tasks in the last baseline release of the project plan');

$lang->install->selectedMode     = 'Selection mode';
$lang->install->selectedModeTips = 'You can go to the Admin - Custom - Mode to set it later.';

$lang->install->groupList['ADMIN']['name']        = 'Admin';
$lang->install->groupList['ADMIN']['desc']        = 'Administrateur';
$lang->install->groupList['DEV']['name']          = 'Dev.';
$lang->install->groupList['DEV']['desc']          = 'Développeur';
$lang->install->groupList['QA']['name']           = 'QA';
$lang->install->groupList['QA']['desc']           = 'Testeur';
$lang->install->groupList['PM']['name']           = 'PM';
$lang->install->groupList['PM']['desc']           = 'Project Manager';
$lang->install->groupList['PO']['name']           = 'PO';
$lang->install->groupList['PO']['desc']           = 'Product Owner';
$lang->install->groupList['TD']['name']           = 'Dev. Manager';
$lang->install->groupList['TD']['desc']           = 'Développement Manager';
$lang->install->groupList['PD']['name']           = 'PD';
$lang->install->groupList['PD']['desc']           = 'Product Director';
$lang->install->groupList['QD']['name']           = 'QD';
$lang->install->groupList['QD']['desc']           = 'Test Manager';
$lang->install->groupList['TOP']['name']          = 'Senior';
$lang->install->groupList['TOP']['desc']          = 'Senior Manager';
$lang->install->groupList['OTHERS']['name']       = 'Autres';
$lang->install->groupList['OTHERS']['desc']       = 'Autres utilisateurs';
$lang->install->groupList['LIMITED']['name']      = 'Utilisateur restreint';
$lang->install->groupList['LIMITED']['desc']      = 'Les utilisateurs peuvent seulement éditer ce qui les concernent.';
$lang->install->groupList['PROJECTADMIN']['name'] = 'Project Admin';
$lang->install->groupList['PROJECTADMIN']['desc'] = 'Project Admins manage project privileges';
$lang->install->groupList['LITEADMIN']['name']    = 'LITEADMIN';
$lang->install->groupList['LITEADMIN']['desc']    = 'Operation Management Interface Admin';
$lang->install->groupList['LITEPROJECT']['name']  = 'LITEPROJECT';
$lang->install->groupList['LITEPROJECT']['desc']  = 'Operation Management Interface Project';
$lang->install->groupList['LITETEAM']['name']     = 'LITETEAM';
$lang->install->groupList['LITETEAM']['desc']     = 'Operation Management Interface Team';

$lang->install->groupList['IPDPRODUCTPLAN']['name'] = 'PRODUCT PLANING';
$lang->install->groupList['IPDDEMAND']['name']      = 'DEMAND ANALYSIS';
$lang->install->groupList['IPDPMT']['name']         = 'IPDPMT';
$lang->install->groupList['IPDADMIN']['name']       = 'IPDADMIN';

$lang->install->cronList[''] = 'Moniteur Cron';
$lang->install->cronList['moduleName=execution&methodName=computeBurn'] = 'Mise à jour Graphe Burndown';
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
$lang->install->cronList['moduleName=mr&methodName=syncMR']             = 'Synchronize GitLab merge request';

$lang->install->dbProgress = "Installing Database Table";
$lang->install->dbFinish   = "Table install finished";
$lang->install->success    = "Installé !";
$lang->install->login      = 'Login ZenTao';
$lang->install->register   = "S'enregistrer sur la Communauté ZenTao";

$lang->install->successLabel       = "<p>Vous avez installé ZenTao %s.</p>";
$lang->install->successNoticeLabel = "<p>Vous avez installé ZenTao %s.<strong class='text-danger'> Supprimez install.php asap</strong>.</p>";
$lang->install->joinZentao         = <<<EOT
<p>Note: In order to get the latest news of ZenTao, please sign up on ZenTao Community(<a href='https://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo');

$lang->install->promotion = "Autres produits de l'équipe Nature ZenTao Software :";

$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'https://www.zsite.net';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Article, Blog, Manual, Member, Shop, Forum, Feedback</li>
  <li>Customize page at will by Theme, Effect, Widget, CSS, JS and layout</li>
  <li>Support both desktop and mobile in one system</li>
  <li>Highly optimized for search engines</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'https://www.zdoo.co/';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>CRM: Customer Management and Order Tracking</li>
  <li>OA: Approve, Announce, Trip, Leave and more </li>
  <li>Project, Task and Document management </li>
  <li>Cash: Income, Expense, Transfer, Invest and Debt</li>
</ul>
EOD;














$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name = 'YDisk';
$lang->install->ydisk->logo = 'images/main/ydisk.ico';
$lang->install->ydisk->url  = 'http://www.ydisk.cn';
$lang->install->ydisk->desc = <<<EOD
<ul>
  <li>Self-Hosted: deploy on your own machine</li>
  <li>Unlimited Storage: depend on your hard drive size</li>
  <li>Fast Transmission: as fast as your bandwidth allows</li>
  <li>Secure: 12 permissions for any strict settings</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name = 'MeshIoT';
$lang->install->meshiot->logo = 'images/main/meshiot.ico';
$lang->install->meshiot->url  = 'https://www.meshiot.com';
$lang->install->meshiot->desc = <<<EOD
<ul>
  <li>Performance: one gateway can monitor 65,536 equipments</li>
  <li>Accessibility: unique radio communication protocol covers 2,500m radius</li>
  <li>Dimming System: 200+ sensors and monitors</li>
  <li>Battery Available: no changes required to any equipment on your site</li>
</ul>
EOD;

$lang->install->solution = new stdclass();
$lang->install->solution->skip        = 'Skip';
$lang->install->solution->skipInstall = 'Skip';
$lang->install->solution->log         = 'Log';
$lang->install->solution->title       = 'DevOps platform application settings';
$lang->install->solution->progress    = 'Installing of DevOps platform';
$lang->install->solution->desc        = 'Welcome to the DevOps platform. We will install the following applications simultaneously when you install the platform to help you get started quickly!';
$lang->install->solution->overMemory  = 'Insufficient memory prevents simultaneous installation. It is recommended to install applications manually after the platform is started.';
