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

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'Version %s';
$lang->misc->zentao->labels['about']   = 'About ZenTao';
$lang->misc->zentao->labels['support'] = 'Technical Support';
$lang->misc->zentao->labels['cowin']   = 'Help Us';
$lang->misc->zentao->labels['service'] = 'Service List';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = 'ZenTao Pro';
$lang->misc->zentao->about['official']     = "Official Website";
$lang->misc->zentao->about['changelog']    = "Version Log";
$lang->misc->zentao->about['license']      = "License";
$lang->misc->zentao->about['extension']    = "Extension Platform";

$lang->misc->zentao->support['vip']        = "VIP Technical Support";
$lang->misc->zentao->support['manual']     = "User Manual";
$lang->misc->zentao->support['faq']        = "FAQ";
$lang->misc->zentao->support['ask']        = "Q&A";
$lang->misc->zentao->support['qqgroup']    = "Official QQ Group";

$lang->misc->zentao->cowin['donate']       = "Donate";
$lang->misc->zentao->cowin['reportbug']    = "Report Bug ";
$lang->misc->zentao->cowin['feedback']     = "Feedback";
$lang->misc->zentao->cowin['recommend']    = "Refer a Friend";
$lang->misc->zentao->cowin['cowinmore']    = "More";

$lang->misc->zentao->service['zentaotrain']= 'ZenTao Training';
$lang->misc->zentao->service['scrumtrain'] = 'Agile Development Training';
$lang->misc->zentao->service['idc']        = 'ZenTao Online Hosting';
$lang->misc->zentao->service['custom']     = 'ZenTao Customized Development';
$lang->misc->zentao->service['install']    = 'ZenTao Installation Service';
$lang->misc->zentao->service['fixissue']   = 'ZenTao Trouble Shooting';
$lang->misc->zentao->service['servicemore']= 'More';

$lang->misc->mobile      = "Mobile Access";
$lang->misc->noGDLib     = "Please use the browser on your phone to visit <strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - 2016 <a href='http://www.cnezsoft.com' target='_blank'>Qingdao Nature Easy Soft Co., Ltd</a> Tel 4006-8899-23 Email <a href='mailto:co@zentao.net'>co@zentao.net</a>";
$lang->misc->checkTable  = "Check Data Table";
$lang->misc->needRepair  = "Repair Table";
$lang->misc->repairTable = "Database table is damaged due to power outage. Please chech and repair!";
$lang->misc->repairFail  = "Failed to repair. Please go to the database data directory, try to perform <code>myisamchk -r -f %s.MYI</code> repair.";
$lang->misc->tableName   = "Table Name";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "First time to ZenTao? Do you want to start rookie mode?";

$lang->user->noticeResetFile = "<h5>If you are not Administrator, please contact Administrator to repair table.</h5>
    <h5>If you are, please login into your Zentao host and create the <span>%s</span> file.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the ok.txt empty.</li>
    <li>If ok.txt exists, remove it and create one again.</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = 'Latest Version';
$lang->misc->feature->detailed = 'Details';

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'Optimized Rich Text Editor and Markdown Editor.', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'Improved Documentation.', 'desc' => '<p>Added Document Home, restructured document library, and added privileges.</p><p>Markdown Editor is supported，and privilege and version managment is added.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and Testing all support home custom mentioned before. </p>');
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
