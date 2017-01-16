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
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

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
$lang->misc->tableName   = "Table Name";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "First time to ZenTao? Do you want to start rookie mode?";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = 'Latest Version';
$lang->misc->feature->detailed = '详情';

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禅道云发信功能', 'desc' => '<p>禅道云发信是禅道联合SendCloud推出的一项免费发信服务，只有用户绑定禅道，并通过验证即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'优化富文本编辑器和markdown编辑器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'调整文档功能', 'desc' => '<p>增加文档模块首页，重新组织文档库结构，增加权限</p><p>多种文件浏览方式，文档支持Markdown，增加文档权限管理，增加文件版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and Testing all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Navigation', 'desc' => '<p>You can decide which project show in navigation bar and the order of projects shown in the bar.</p><p> Hover on the navigation bar and a sign will show to its right. Click the sign and a dialog box "Custom Navigation" will show. Drag the block name to switch its order on navigation bar.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Batch Add/Edit Custom', 'desc' => '<p>You can batch add and edit fields on custom pages.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Add Custom Story/Task/Bug/Case', 'desc' => '<p>You can custom fileds when add a Story/Task/Bug/Case.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Export', 'desc' => '<p>You can custom fileds when export a Story/Task/Bug/Case pages. You can save it as template for next export.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Story/Task/Bug/Case Combinatorial Search ', 'desc' => '<p>On Story/Task/Bug/Case List page, you can do combinatorial search on Modules and Tabs.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"Rookie's Tutorial", 'desc' => '<p>Tutorial for Rookies is added for users to know how to use ZenTao.</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'产品实现分支功能', 'desc' => '<p>产品增加分支/平台类型，相应的需求、计划、Bug、用例、模块等都增加分支。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'调整发布模块', 'desc' => '<p>发布增加停止维护操作，当发布停止维护时，创建Bug将不显示这个发布。</p><p>发布中遗留的bug改为手工关联。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'调整需求和Bug的创建页面', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增强安全', 'desc' => '<p>加强对管理员弱口令的检查。</p><p>写插件，上传插件的时候需要创建ok文件。</p><p>敏感操作增加管理员口令的检查</p><p>对输入内容做striptags, specialchars处理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善细节', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供计划任务框架', 'desc' => '增加计划任务框架，加入每日提醒、更新燃尽图、备份、发信等重要任务。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加数据表格功能', 'desc' => '<p>可配置数据表格中可显示的字段，按照配置字段显示想看的数据</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'继续完善细节', 'desc' => '');
