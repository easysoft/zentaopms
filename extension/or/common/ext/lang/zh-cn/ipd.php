<?php
$lang->SRCommon = '用户需求';
$lang->demandpool = new stdclass();
$lang->demandpool->common = '需求池';

$lang->demand = new stdclass();
$lang->demand->common = '需求';

$lang->charter = new stdclass();
$lang->charter->common = '立项';

$lang->market = new stdclass();
$lang->market->common = '市场';

$lang->marketreport = new stdclass();
$lang->marketreport->common = '报告';

$lang->marketresearch = new stdclass();
$lang->marketresearch->common = '调研';

$lang->navIcons['demandpool'] = "<i class='icon icon-bars'></i>";
$lang->navIcons['market']     = "<i class='icon icon-market'></i>";

/* Main Navigation. */
$lang->mainNav              = new stdclass();
$lang->mainNav->my          = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->demandpool  = "{$lang->navIcons['demandpool']} {$lang->demandpool->common}|demandpool|browse|";
$lang->mainNav->market      = "{$lang->navIcons['market']} {$lang->market->common}|marketreport|all|";
$lang->mainNav->product     = "{$lang->navIcons['product']} {$lang->productCommon}|product|all|";
$lang->mainNav->charter     = "{$lang->navIcons['project']} {$lang->charter->common}|charter|browse|";
$lang->mainNav->doc         = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|index|";
$lang->mainNav->admin       = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

//if($config->edition != 'open')
//{
//    $lang->navIcons['feedback'] = "<i class='icon icon-feedback'></i>";
//
//    $lang->mainNav->feedback = $lang->navIcons['feedback'] . '反馈|feedback|browse|browseType=unclosed';
//
//    if($config->visions == ',lite,') unset($lang->mainNav->feedback);
//}

$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'demandpool';
$lang->mainNav->menuOrder[15] = 'market';
$lang->mainNav->menuOrder[20] = 'product';
$lang->mainNav->menuOrder[25] = 'charter';
//$lang->mainNav->menuOrder[25] = 'feedback';
$lang->mainNav->menuOrder[30] = 'doc';
$lang->mainNav->menuOrder[35] = 'admin';

$lang->navGroup->demandpool     = 'demandpool';
$lang->navGroup->demand         = 'demandpool';
$lang->navGroup->roadmap        = 'product';
$lang->navGroup->charter        = 'charter';
$lang->navGroup->market         = 'market';
$lang->navGroup->marketreport   = 'market';
$lang->navGroup->marketresearch = 'market';

$lang->demandpool->menu = new stdclass();
$lang->demandpool->menu->browse  = array('link' => "{$lang->demand->common}|demand|browse|poolID=%s", 'alias' => 'create,batchcreate,edit,managetree,view,tostory,showimport,review,change');
//$lang->demandpool->menu->review  = array('link' => "评审|demand|review|poolID=%s");
//$lang->demandpool->menu->kanban  = array('link' => "看板|demand|kanban|poolID=%s");
//$lang->demandpool->menu->track   = array('link' => "矩阵图|demand|track|demandID=%s");
//$lang->demandpool->menu->insight = array('link' => "洞察|demand|insight|demandID=%s");
$lang->demandpool->menu->view    = array('link' => "概况|demandpool|view|poolID=%s", 'alias' => 'edit');

$lang->demandpool->menuOrder[5]  = 'browse';
$lang->demandpool->menuOrder[10] = 'review';
$lang->demandpool->menuOrder[15] = 'kanban';
$lang->demandpool->menuOrder[20] = 'track';
$lang->demandpool->menuOrder[25] = 'insight';
$lang->demandpool->menuOrder[30] = 'view';

$lang->product->menu              = new stdclass();
$lang->product->menu->requirement = array('link' => "{$lang->URCommon}|product|browse|productID=%s&branch=&browseType=assignedtome&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->roadmap     = array('link' => "路标|roadmap|browse|productID=%s");
#$lang->product->menu->initiating  = array('link' => "立项|product|initiating|productID=%s");
$lang->product->menu->settings    = array('link' => "{$lang->settings}|product|view|productID=%s", 'subModule' => 'tree,branch', 'alias' => 'edit,whitelist,addwhitelist');

$lang->product->menu->settings['subMenu'] = new stdclass();
$lang->product->menu->settings['subMenu']->view      = array('link' => "{$lang->overview}|product|view|productID=%s", 'alias' => 'edit');
$lang->product->menu->settings['subMenu']->module    = array('link' => "{$lang->module}|tree|browse|product=%s&view=story", 'subModule' => 'tree');
$lang->product->menu->settings['subMenu']->branch    = array('link' => "@branch@|branch|manage|product=%s", 'subModule' => 'branch');
$lang->product->menu->settings['subMenu']->whitelist = array('link' => "{$lang->whitelist}|product|whitelist|product=%s", 'subModule' => 'personnel');

$lang->product->menuOrder = array();
$lang->product->menuOrder[5]  = 'requirement';
$lang->product->menuOrder[10] = 'roadmap';
$lang->product->menuOrder[15] = 'initiating';
$lang->product->menuOrder[20] = 'settings';

unset($lang->product->homeMenu->home);
unset($lang->product->homeMenu->kanban);

$lang->charter->menu           = new stdclass();
$lang->charter->menu->all      = array('link' => "全部|charter|browse|browseType=all");
$lang->charter->menu->wait     = array('link' => "待立项|charter|browse|browseType=wait");
$lang->charter->menu->launched = array('link' => "已立项|charter|browse|browseType=launched");
$lang->charter->menu->failed   = array('link' => "未通过|charter|browse|browseType=failed");
//$lang->charter->menu->settings = array('link' => "{$lang->settings}|charter|view|charterID=%s", 'subModule' => 'tree,branch', 'alias' => 'edit,whitelist,addwhitelist');

unset($lang->my->menu->project);
unset($lang->my->menu->execution);
unset($lang->my->menu->contribute);
unset($lang->my->menu->meeting);
unset($lang->doc->menu->project);
unset($lang->doc->menu->api);

$lang->my->menu->work = array('link' => "{$lang->my->work}|my|work|mode=requirement", 'subModule' => 'task');

$lang->my->menu->work['subMenu'] = new stdclass();
$lang->my->menu->work['subMenu']->requirement = "$lang->URCommon|my|work|mode=requirement";

unset($lang->createIcons['bug']);
unset($lang->createIcons['story']);
unset($lang->createIcons['task']);
unset($lang->createIcons['testcase']);
unset($lang->createIcons['program']);
unset($lang->createIcons['project']);
unset($lang->createIcons['execution']);
unset($lang->createIcons['kanbanspace']);
unset($lang->createIcons['kanban']);

$lang->searchObjects = array();
$lang->searchObjects['all']            = '全部';
$lang->searchObjects['story']          = '需求';
$lang->searchObjects['demandpool']     = '需求池';
$lang->searchObjects['demand']         = '需求池需求';
$lang->searchObjects['roadmap']        = '路标';
$lang->searchObjects['charter']        = '立项';
$lang->searchObjects['product']        = $lang->productCommon;
$lang->searchObjects['doc']            = '文档';
$lang->searchObjects['market']         = '市场';
$lang->searchObjects['marketreport']   = '报告';
$lang->searchObjects['marketresearch'] = '调研';
$lang->searchTips                      = '编号(ctrl+g)';

$lang->market->homeMenu           = new stdclass();
$lang->market->homeMenu->report   = array('link' => "报告|marketreport|all", 'subModule' => 'marketreport');
$lang->market->homeMenu->research = array('link' => "调研|marketresearch|all", 'subModule' => 'marketresearch', 'exclude' => 'marketresearch-reports');
$lang->market->homeMenu->market   = array('link' => "市场|market|browse|browseType=all");

$lang->market->menu           = new stdclass();
$lang->market->menu->report   = array('link' => "报告|marketreport|browse|marketID=%s", 'subModule' => 'marketreport');
$lang->market->menu->research = array('link' => "调研|marketresearch|browse|marketID=%s", 'subModule' => 'marketresearch', 'exclude' => 'marketresearch-reports');
$lang->market->menu->view     = array('link' => "概况|market|view|marketID=%s", 'alias' => 'edit');
