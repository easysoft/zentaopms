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
$lang->misc->ping   = 'Keep session';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'Version %s';
$lang->misc->zentao->labels['about']   = 'About';
$lang->misc->zentao->labels['support'] = 'Support';
$lang->misc->zentao->labels['cowin']   = 'Help us';
$lang->misc->zentao->labels['service'] = 'Services';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = 'Try zentaopro!';
$lang->misc->zentao->about['official']     = "Official site";
$lang->misc->zentao->about['changelog']    = "Change log";
$lang->misc->zentao->about['license']      = "License";
$lang->misc->zentao->about['extension']    = "Extensions";

$lang->misc->zentao->support['vip']        = "Business";
$lang->misc->zentao->support['manual']     = "Manual";
$lang->misc->zentao->support['faq']        = "FAQ";
$lang->misc->zentao->support['ask']        = "Ask";
$lang->misc->zentao->support['qqgroup']    = "QQ Group";

$lang->misc->zentao->cowin['donate']       = "Donate";
$lang->misc->zentao->cowin['reportbug']    = "Report bug";
$lang->misc->zentao->cowin['feedback']     = "Feedback feature";
$lang->misc->zentao->cowin['recommend']    = "Recommend";
$lang->misc->zentao->cowin['cowinmore']    = "More...";

$lang->misc->zentao->service['zentaotrain']= 'ZenTao training';
$lang->misc->zentao->service['scrumtrain'] = 'Agile training';
$lang->misc->zentao->service['idc']        = 'ZenTao online';
$lang->misc->zentao->service['custom']     = 'custom develop';
$lang->misc->zentao->service['install']    = 'Install service';
$lang->misc->zentao->service['fixissue']   = 'Issue support';
$lang->misc->zentao->service['servicemore']= 'More...';

$lang->misc->mobile      = "Mobile access";
$lang->misc->noGDLib     = "Please visit：<strong>%s</strong>.";
$lang->misc->copyright   = "&copy; 2009-2016 <a href='http://www.cnezsoft.com' target='_blank'>Nature EasySoft Network Tecnology Co.ltd, QingDao, China</a>  4006-8899-23  <a href='mailto:co@zentao.net'>co@zentao.net</a>";
$lang->misc->checkTable  = "Check and repair table";
$lang->misc->needRepair  = "Repair Table";
$lang->misc->repairTable = "The database table can cause of damage because of power failure, need to check and repair!!";
$lang->misc->tableName   = "Table name";
$lang->misc->tableStatus = "Table status";
$lang->misc->novice      = "You may use the ZenTao first, whether to enter the novice mode?";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest         = 'Latest';
$lang->misc->feature->all             = array();
$lang->misc->feature->all['latest']   = array();
$lang->misc->feature->all['latest'][] = array('title'=>'首页自定义', 'desc' => '<p>我的地盘由我做主。现在开始，你可以向首页添加多种多样的内容区块，而且还可以决定如何排列和显示他们。</p><p>我的地盘、产品、项目、测试模块下均支持首页自定义功能。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'导航定制', 'desc' => '<p>导航上显示的项目现在完全由你来决定，不仅仅可以决定在导航上展示哪些内容，还可以决定展示的顺序。</p><p>将鼠标悬浮在导航上稍后会在右侧显示定制按钮，点击打开定制对话框，通过点击切换是否显示，拖放操作来更改显示顺序。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'批量添加、编辑自定义', 'desc' => '<p>可以在批量添加和批量编辑页面自定义操作的字段。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'添加需求、任务、Bug、用例自定义', 'desc' => '<p>可以在添加需求、任务、Bug、用例页面，自定义部分字段是否显示。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'导出自定义', 'desc' => '<p>在导出需求、任务、Bug、用例的时候，用户可以自定义导出的字段，也可以保存模板方便每次导出。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'需求、任务、Bug、用例组合检索功能', 'desc' => '<p>在需求、任务、Bug、用例列表页面，可以实现模块和标签的组合检索。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用户了解禅道使用。</p>');
