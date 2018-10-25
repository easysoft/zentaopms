<?php
/**
 * The misc module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common = 'Misc';
$lang->misc->ping   = 'Ping';
$lang->misc->api    = 'https://api.zentao.net';
$lang->misc->enApi  = 'http://api.zentao.pm';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'Version %s';
$lang->misc->zentao->labels['about']   = 'About';
$lang->misc->zentao->labels['support'] = 'Support';
$lang->misc->zentao->labels['cowin']   = 'Help Us';
$lang->misc->zentao->labels['service'] = 'Service';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = 'ZenTao Pro';
$lang->misc->zentao->about['official']     = "Official Website";
$lang->misc->zentao->about['changelog']    = "Change Log";
$lang->misc->zentao->about['license']      = "License";



$lang->misc->zentao->support['vip']        = "VIP Technical Support";
$lang->misc->zentao->support['manual']     = "User Manual";






$lang->misc->zentao->cowin['reportbug']    = "Report Bug ";
$lang->misc->zentao->cowin['feedback']     = "Feedback";

$lang->misc->zentao->cowin['cowinmore']    = "More";




$lang->misc->zentao->service['servicemore']= 'More';

$lang->misc->mobile      = "Mobile Access";
$lang->misc->noGDLib     = "Please use the browser on your phone to visit <strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - 2018 <a href='http://easysoft.ltd' target='_blank'>Nature Easy Soft Network Technology Co,LTD</a> Email <a href='mailto:Max@easysoft.ltd'>Max@easysoft.ltd</a>";
$lang->misc->checkTable  = "Check Data Table";
$lang->misc->needRepair  = "Repair Table";
$lang->misc->repairTable = "Database table is damaged due to power outage. Please chech and repair!";
$lang->misc->repairFail  = "Failed to repair. Please go to the database data directory, try to perform <code>myisamchk -r -f %s.MYI</code> repair.";
$lang->misc->connectFail = "Failed to connect to database. Error: %s，<br/> Please check the MySQL error log and troubleshoot.";
$lang->misc->tableName   = "Table Name";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "First time to ZenTao? Do you want to start rookie mode?";

$lang->misc->noticeRepair = "<h5>If you are not Administrator, please contact Administrator to repair table.</h5>
    <h5>If you are, please login into your Zentao host and create a file named <span>%s</span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create one again.</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = 'Latest Version';
$lang->misc->feature->detailed = 'Details';

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

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document display', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Adding filter conditions to the bottom of the document library's navigation bottom.</p>");
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust the sub task logic and optimize the display of father child task.', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimizing and adjusting new interface', 'desc' => '<p>Details page restore our previous layout.</p><p>Refactoring forms to add user pages</p><p>When use cases are executed, do not update the use case state if the user manually chooses to pass through and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'After the user machine hibernate login fails, the session is refreshed again.', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Upgrading existing interface mechanisms', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'Xuan.im is integrated!', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'New UI/UX and new experience', 'desc' => '<ol><li>My Dashboard</li><li>Dynamic</li><li>Product Home</li><li>Product overview</li><li>Roadmap</li><li>Project Home</li><li>Project overview</li><li>QA Home</li><li>Document Home</li><li>Added work report on My Dashboard</li><li>Add/Edit/Finish todos on My Dashboard</li><li>Add prodcut report on Product Home</li><li>Add prodcut overview on Product Home</li><li>Add project report on Project Home</li><li>Add project overview on Project Home</li><li>Add QA report on QA Home</li><li>All Product/product Home/All Project/Project Home/QA Home is moved from the right of the secondary Narbar to the left.</li><li>Kanban/Burndown/Tree/ByGroup of Project/Task has been moved from the third Navbar to the secondary one; Tree/ByGroup/Task Liat has been integrated to a drop-down.</li><li>Bug/Build of Project on the secondary Navbar has been integrated to a drop-down.</li><li>Display build and list by group, which is more reasonable.</li><li>Added tree to display docuemnt on the left of the page.</li><li>Added quick entry to document, including Last Update, My Doc and My Favorite</li><li>Added My Favorite to Doc module.</li></ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Centring Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Add Periodic Todo function', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>"Add Block of 'Assigned to Me'", 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Support Generating TestStatements of TestBuilds', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'optimize International package，Added Demo data。', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Webhook Interface feature', 'desc' => 'support communication with BearyChat,dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Score feature', 'desc' => 'More skilled Use, More score');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Multiplayer task and subTask to project tasks', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Product line management to Product View', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'added Restricted Operatio', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'upgraded framework，Enhanced security', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>added TestSuite,CaseLib and Test Statements</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'support Group steps of TestCase', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'Optimized Rich Text Editor and Markdown Editor.', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'Improved Documentation.', 'desc' => '<p>Added Document Home, restructured document library, and added privileges.</p><p>Markdown Editor is supported，and privilege and version managment is added.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Navigation', 'desc' => '<p>You can decide which project show in navigation bar and the order of projects shown in the bar.</p><p> Hover on the navigation bar and a sign will show to its right. Click the sign and a dialog box "Custom Navigation" will show. Drag the block name to switch its order on navigation bar.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Batch Add/Edit Custom', 'desc' => '<p>You can batch add and edit fields on custom pages.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Story/Task/Bug/Case', 'desc' => '<p>You can custom fileds when add a Story/Task/Bug/Case.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Export', 'desc' => '<p>You can custom fileds when export a Story/Task/Bug/Case pages. You can also save it as template for next export.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Story/Task/Bug/Case Search ', 'desc' => '<p>On Story/Task/Bug/Case List page, you can do a combined search on Modules and Tabs.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"Rookie's Tutorial", 'desc' => '<p>Tutorial for Rookies is added for first-time users to know how to use ZenTao.</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Release Module is improved.', 'desc' => '<p>Stop action has been added. If Stop to manage it, the Release will not show when Report Bug.</p><p>Bugs that have been omitted in the Release will be related manually.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Create pages of Story and Bug are improved.', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Details Improved', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of scheduled tasks is added.', 'desc' => 'Framework of scheduled tasks is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'rpm and deb packages are provided.', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Continue improving details', 'desc' => '');
