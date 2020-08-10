<?php
/**
 * The misc module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.pm
 */
$lang->misc = new stdclass();
$lang->misc->common = 'Misc';
$lang->misc->ping   = 'Ping';
$lang->misc->api    = 'https://api.zentao.pm';
$lang->misc->enApi  = 'http://api.zentao.pm';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'Version %s';
$lang->misc->zentao->labels['about']   = 'A Propos';
$lang->misc->zentao->labels['support'] = 'Support Technique';
$lang->misc->zentao->labels['cowin']   = 'Aider ZenTao';
$lang->misc->zentao->labels['service'] = 'Services';
$lang->misc->zentao->labels['others']  = 'Autres Produits';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = 'ZenTao Pro';
$lang->misc->zentao->about['official']     = "Site Officiel";
$lang->misc->zentao->about['changelog']    = "Change Log";
$lang->misc->zentao->about['license']      = "Licence";



$lang->misc->zentao->support['vip']        = "Support VIP";
$lang->misc->zentao->support['manual']     = "English User Manual";





$lang->misc->zentao->cowin['reportbug']    = "Signaler un Bug";
$lang->misc->zentao->cowin['feedback']     = "Feedback";
$lang->misc->zentao->cowin['translate']    = "Traduire";
$lang->misc->zentao->cowin['recommend']    = "Plus...";

$lang->misc->zentao->service['zentaotrain']= 'Zentao Train';
$lang->misc->zentao->service['idc']        = 'Zentao Cloud';
$lang->misc->zentao->service['custom']     = 'Zentao Custom';
$lang->misc->zentao->service['servicemore']= 'Plus...';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> Zsite";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO";

$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> Y Disk";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> MeshioT";

$lang->misc->mobile      = "Accès Mobile";
$lang->misc->noGDLib     = "SVP visitez <strong>%s</strong> via le navigateur de votre smartphone.";
$lang->misc->copyright   = "&copy; 2009 - 2019 <a href='http://easysoft.ltd' target='_blank'>Nature Easy Soft</a> Email <a href='mailto:Renee@easysoft.ltd'>Renee@easysoft.ltd</a>";
$lang->misc->checkTable  = "Vérifiez les Données de la Table";
$lang->misc->needRepair  = "Réparer la Table";
$lang->misc->repairTable = "La Base de Données est peut-être endommagée. Elle a besoin d'être vérifiée et éventuellement réparée !";
$lang->misc->repairFail  = "La réparation a échoué. Allez dans le répertoire de données de votre table et essayez d'exécuter <code>myisamchk -r -f %s.MYI</code> pour la réparer. Mais on ne promet rien :-(";
$lang->misc->connectFail = "Echec de connexion à la Base de données. Erreur: %s，<br/>Vérifiez la log des erreurs MySQL et des dysfonctionnements.";
$lang->misc->tableName   = "Nom de la Table";
$lang->misc->tableStatus = "Statut";
$lang->misc->novice      = "Nouveau dans ZenTao ? Voulez-vous commencer le Tutoriel ?";
$lang->misc->showAnnual  = 'Ajouter un résumé annuel';
$lang->misc->annualDesc  = 'A partir de la version 12.0, la nouvelle fonction de rapport annuel peut être consultée sur la page 『Report->Annual Summary』. <a href="%s" target="_blank" id="showAnnual" class="btn btn-mini btn-primary">Consulter Maintenant</a>.';
$lang->misc->remind      = 'Rappels des nouvelles fonctionnalités';

$lang->misc->noticeRepair = "<h5>Si vous n'êtes pas l'administrateur, contactez votre administrateur ZenTao pour réparer les tables.</h5>
    <h5>Si vous êtes l'administrateur, connectez-vous à votre serveur ZenTao et créez un fichier vide dont le nom est <span>%s</span>.</h5>
    <p>Note :</p>
    <ol>
    <li>Laissez surtout le fichier vide.</li>
    <li>Si le fichier existe déjà, supprimez-le et créez un nouveau fichier.</li><li>Il y a bon espoir d'y arriver.</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = 'Dernière Version';
$lang->misc->feature->detailed = 'Détail';

$lang->misc->releaseDate['12.4.1']      = '2020-08-10';
$lang->misc->releaseDate['12.4.stable'] = '2020-07-28';
$lang->misc->releaseDate['12.3.3']      = '2020-07-02';
$lang->misc->releaseDate['12.3.2']      = '2020-06-01';
$lang->misc->releaseDate['12.3.1']      = '2020-05-15';
$lang->misc->releaseDate['12.3']        = '2020-04-08';
$lang->misc->releaseDate['12.2']        = '2020-03-25';
$lang->misc->releaseDate['12.1']        = '2020-03-10';
$lang->misc->releaseDate['12.0.1']      = '2020-02-12';
$lang->misc->releaseDate['12.0']        = '2020-01-03';
$lang->misc->releaseDate['11.7']        = '2019-11-28';
$lang->misc->releaseDate['11.6.5']      = '2019-11-08';
$lang->misc->releaseDate['11.6.4']      = '2019-10-17';
$lang->misc->releaseDate['11.6.3']      = '2019-09-24';
$lang->misc->releaseDate['11.6.2']      = '2019-09-06';
$lang->misc->releaseDate['11.6.1']      = '2019-08-23';
$lang->misc->releaseDate['11.6.stable'] = '2019-07-12';
$lang->misc->releaseDate['11.5.2']      = '2019-06-26';
$lang->misc->releaseDate['11.5.1']      = '2019-06-24';
$lang->misc->releaseDate['11.5.stable'] = '2019-05-08';
$lang->misc->releaseDate['11.4.1']      = '2019-04-08';
$lang->misc->releaseDate['11.4.stable'] = '2019-03-25';
$lang->misc->releaseDate['11.3.stable'] = '2019-02-27';
$lang->misc->releaseDate['11.2.stable'] = '2019-01-30';
$lang->misc->releaseDate['11.1.stable'] = '2019-01-04';
$lang->misc->releaseDate['11.0.stable'] = '2018-12-21';
$lang->misc->releaseDate['10.6.stable'] = '2018-11-20';
$lang->misc->releaseDate['10.5.stable'] = '2018-10-25';
$lang->misc->releaseDate['10.4.stable'] = '2018-09-28';
$lang->misc->releaseDate['10.3.stable'] = '2018-08-10';
$lang->misc->releaseDate['10.2.stable'] = '2018-08-02';
$lang->misc->releaseDate['10.0.stable'] = '2018-06-26';
$lang->misc->releaseDate['9.8.stable']  = '2018-01-17';
$lang->misc->releaseDate['9.7.stable']  = '2017-12-22';
$lang->misc->releaseDate['9.6.stable']  = '2017-11-06';
$lang->misc->releaseDate['9.5.1']       = '2017-09-27';
$lang->misc->releaseDate['9.3.beta']    = '2017-06-21';
$lang->misc->releaseDate['9.1.stable']  = '2017-03-23';
$lang->misc->releaseDate['9.0.beta']    = '2017-01-03';
$lang->misc->releaseDate['8.3.stable']  = '2016-11-09';
$lang->misc->releaseDate['8.2.stable']  = '2016-05-17';
$lang->misc->releaseDate['7.4.beta']    = '2015-11-13';
$lang->misc->releaseDate['7.2.stable']  = '2015-05-22';
$lang->misc->releaseDate['7.1.stable']  = '2015-03-07';
$lang->misc->releaseDate['6.3.stable']  = '2014-11-07';

$lang->misc->feature->all['12.4.1'][] = array('title'=>'Fix Bug', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title'=>'Fix Bug', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title'=>'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title'=>'Fix workflow', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title'=>'Fix bugs of high severity.', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title'=>'Integrate unit test, open the continuous integration closed-loop.', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title'=>'Add parent-child story, compatible xuanxuan.', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'Add Integration.', 'desc' => '<p>Add integration, and build in Jenkins</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Add annual summary', 'desc' => 'Show annual summary by role.');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Added choices for users to choose agile or not.</p><p>Added WeChat Enterprise to the types of webhook</p><p>Added the notifier of Dingding personal messages</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Improving the International Edition Interface', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Add translate function', 'desc' => '');

$lang->misc->feature->all['11.5.2'][]      = array('title'=>'Increase the security of ZenTao and increase the login password for weak password check', 'desc' => '');
$lang->misc->feature->all['11.5.1'][]      = array('title'=>'Add a third-party authentication and fix bugs.', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Added filters to Dynamics', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Integrated the latest ZenTao client', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array('title'=>'Optimize details and fix bug.', 'desc' => "<p>Enhanced test management.</p><p>Optimized the UI of Plan, Release, and Build linked stories and bugs.</p><p>Customize the feature whether to display files in child category.</p><p>Optimize details and fix bug.</p>");

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add Child Plan to a Plan</p><p>Optimize the chosen</p><p>Add Timezone settings</p><p>Optimize Document Library and Document modules</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add upgrade logs and database checkup after upgrading</p><p>Fixed ZenTao client and other bugs, and optimized details.</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'ZenTao integrated desktop.', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Adjust backup mechanism', 'desc' => '<p>Increase backup settings and make backup more flexible</p><p>Show backup progress</p><p>Change the backup directory</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Optimize and adjust menu', 'desc' => '<p>Adjust admin menu</p><p>Adjust the secondary menu of My and Project</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document layout', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Add filter conditions at the bottom of the document library menu.</p>");
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust the child task logic and optimize the display of parent-child task.', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'After the user machine hibernates and the login fails, the session will be refreshed again.', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Upgrade existing interface mechanisms', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'ZenTao desktop is integrated!', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'New UI/UX and new experience', 'desc' => '<ol><li>My Dashboard</li><li>Dynamics</li><li>Product Home</li><li>Product Overview</li><li>Roadmap</li><li>Project Home</li><li>Project overview</li><li>QA Home</li><li>Document Home</li><li>Added work report on My Dashboard</li><li>Add/Edit/Finish todos on My Dashboard</li><li>Add prodcut report on Product Home</li><li>Add prodcut overview on Product Home</li><li>Add project report on Project Home</li><li>Add project overview on Project Home</li><li>Add Testing report on QA Home</li><li>All Product/product Home/All Project/Project Home/QA Home is moved from the right of the secondary Navbar to the left.</li><li>Kanban/Burndown/Tree/ByGroup of Project/Task has been moved from the third Navbar to the secondary one; Tree/ByGroup/Task List has been integrated to a drop-down.</li><li>Bug/Build of Project on the secondary Navbar has been integrated to a drop-down.</li><li>Display build and list by group, which is more reasonable.</li><li>Added tree to display document on the left of the page.</li><li>Added quick entry to document, including Last Update, My Doc and My Favorite</li><li>Added My Favorite to Doc module.</li></ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Gather Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Add recurred Todo', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>"Add Block of 'AssignedToMe'", 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Support generating reports on Test Builds', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'Optimize International package. Added Demo data.', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Webhook Interface feature', 'desc' => 'Support communication with BearyChat, Dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Point', 'desc' => 'More skilled application, more score');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added multiple user task and child tasks to Project', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Product line management to Product', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'Added restricted actions.', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'Upgraded ZenTao framework，enhanced security', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>Added TestSuite, CaseLibrary and QA Report</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'Support Group steps of TestCase', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'Optimized Rich Text Editor and Markdown Editor.', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'Improved Documentation.', 'desc' => '<p>Added Document Home, restructured document library, and added privileges.</p><p>Markdown Editor is supported，and privilege and version managment is added.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Navigation', 'desc' => '<p>You can decide which project show in navigation bar and the order of projects shown in the bar.</p><p> Hover on the navigation bar and a sign will show to its right. Click the sign and a dialog box "Custom Navigation" will show. Drag the block name to switch its order on navigation bar.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Batch Add/Edit Custom', 'desc' => '<p>You can batch add and edit fields on custom pages.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Story/Task/Bug/Case', 'desc' => '<p>You can custom fileds when add a Story/Task/Bug/Case.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Export', 'desc' => '<p>You can custom fileds when export a Story/Task/Bug/Case pages. You can also save it as template for next export.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Story/Task/Bug/Case Search ', 'desc' => '<p>On Story/Task/Bug/Case List page, you can do a combined search on Modules and Tabs.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"ZenTao Tutorial", 'desc' => '<p>Tutorial for rookies is added for first-time users to know how to use ZenTao.</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Release Module is improved.', 'desc' => '<p>Stop action has been added. If Stop to manage it, the Release will not show when Report Bug.</p><p>Bugs that have been omitted in the Release will be related manually.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Create pages of Story and Bug are improved.', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Details Improved', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of Cron is added.', 'desc' => 'Framework of Cron is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'rpm and deb packages are provided.', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Continue improving details', 'desc' => '');
