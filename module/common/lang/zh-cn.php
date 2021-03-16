<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '-';
$lang->comma     = '，';
$lang->dot       = '。';
$lang->at        = ' 于 ';
$lang->downArrow = '↓';
$lang->null      = '空';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';

$lang->zentaoPMS        = '禅道';
$lang->logoImg          = 'zt-logo.png';
$lang->welcome          = "%s项目管理系统";
$lang->logout           = '退出';
$lang->login            = '登录';
$lang->help             = '帮助';
$lang->aboutZenTao      = '关于禅道';
$lang->profile          = '个人档案';
$lang->changePassword   = '修改密码';
$lang->unfoldMenu       = '展开导航';
$lang->collapseMenu     = '收起导航';
$lang->preference       = '个性化设置';
$lang->runInfo          = "<div class='row'><div class='u-1 a-center' id='debugbar'>时间: %s 毫秒, 内存: %s KB, 查询: %s.  </div></div>";
$lang->agreement        = "已阅读并同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授权协议1.2》</a>。<span class='text-danger'>未经许可，不得去除、隐藏或遮掩禅道软件的任何标志及链接。</span>";
$lang->designedByAIUX   = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> 艾体验设计</a>";

$lang->reset        = '重填';
$lang->cancel       = '取消';
$lang->refresh      = '刷新';
$lang->edit         = '编辑';
$lang->delete       = '删除';
$lang->close        = '关闭';
$lang->unlink       = '移除';
$lang->import       = '导入';
$lang->export       = '导出';
$lang->setFileName  = '文件名：';
$lang->submitting   = '稍候...';
$lang->save         = '保存';
$lang->saveSuccess  = '保存成功';
$lang->confirm      = '确认';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->goPC         = 'PC版';
$lang->more         = '更多';
$lang->moreLink     = 'More';
$lang->day          = '天';
$lang->customConfig = '自定义';
$lang->public       = '公共';
$lang->trunk        = '主干';
$lang->sort         = '排序';
$lang->required     = '必填';
$lang->noData       = '暂无';
$lang->fullscreen   = '全屏';
$lang->retrack      = '收起';
$lang->recent       = '近期';
$lang->whitelist    = '访问白名单';

$lang->actions         = '操作';
$lang->restore         = '恢复默认';
$lang->comment         = '备注';
$lang->history         = '历史记录';
$lang->attatch         = '附件';
$lang->reverse         = '切换顺序';
$lang->switchDisplay   = '切换显示';
$lang->expand          = '展开全部';
$lang->collapse        = '收起';
$lang->saveSuccess     = '保存成功';
$lang->fail            = '失败';
$lang->addFiles        = '上传了附件 ';
$lang->files           = '附件 ';
$lang->pasteText       = '多项录入';
$lang->uploadImages    = '多图上传 ';
$lang->timeout         = '连接超时，请检查网络环境，或重试！';
$lang->repairTable     = '数据库表可能损坏，请用phpmyadmin或myisamchk检查修复。';
$lang->duplicate       = '已有相同标题的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理员限制当前IP登录，请联系管理员解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '设为模块首页';
$lang->noviceTutorial  = '新手教程';
$lang->changeLog       = '修改日志';
$lang->manual          = '手册';
$lang->customMenu      = '自定义导航';
$lang->customField     = '自定义表单项';
$lang->lineNumber      = '行号';
$lang->tutorialConfirm = '检测到你尚未退出新手教程模式，是否现在退出？';

$lang->preShortcutKey  = '[快捷键:←]';
$lang->nextShortcutKey = '[快捷键:→]';
$lang->backShortcutKey = '[快捷键:Alt+↑]';

$lang->select        = '选择';
$lang->selectAll     = '全选';
$lang->selectReverse = '反选';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您访问的对象并不存在！';
$lang->notPage       =  '抱歉，您访问的功能正在开发中！';
$lang->showAll       = '[[全部显示]]';
$lang->selectedItems = '已选择 <strong>{0}</strong> 项';

$lang->future      = '未来';
$lang->year        = '年';
$lang->workingHour = '工时';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '状态';
$lang->openedByAB   = '创建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '类型';

$lang->common = new stdclass();
$lang->common->common = '公有模块';

global $config;
list($programModule, $programMethod)     = explode('-', $config->programLink);
list($productModule, $productMethod)     = explode('-', $config->productLink);
list($projectModule, $projectMethod)     = explode('-', $config->projectLink);
list($executionModule, $executionMethod) = explode('-', $config->executionLink);

/* 主导航菜单。*/
$lang->mainNav = new stdclass();
$lang->mainNav->my      = '<i class="icon icon-menu-my"></i> 地盘|my|index|';
$lang->mainNav->product = "<i class='icon icon-product'></i> 产品|$productModule|$productMethod|";
if($config->systemMode == 'new')
{
    $lang->mainNav->project   = "<i class='icon icon-project'></i> 项目|$projectModule|$projectMethod|";
    $lang->mainNav->execution = "<i class='icon icon-run'></i> 执行|$executionModule|$executionMethod|";
}
else
{
    $lang->mainNav->execution = "<i class='icon icon-run'></i> $lang->executionCommon|$executionModule|$executionMethod|";
}
$lang->mainNav->qa      = '<i class="icon icon-test"></i> 测试|qa|index|';
$lang->mainNav->repo    = '<i class="icon icon-code1"></i> 代码|repo|browse|';
$lang->mainNav->doc     = '<i class="icon icon-doc"></i> 文档|doc|index|';
$lang->mainNav->report  = "<i class='icon icon-statistic'></i> 统计|report|productSummary|";
$lang->mainNav->system  = '<i class="icon icon-group"></i> 组织|my|team|';
$lang->mainNav->admin   = '<i class="icon icon-cog-outline"></i> 后台|admin|index|';
if($config->systemMode == 'new') $lang->mainNav->program = "<i class='icon icon-program'></i> 项目集|$programModule|$programMethod|";

$lang->dividerMenu = ',qa,report,admin,';

/* Program set menu. */
$lang->program = new stdclass();
$lang->program->menu = new stdclass();
//$lang->program->menu->index  = '仪表盘|program|index|';
$lang->program->menu->browse = array('link' => '项目集|program|browse|');

$lang->project = new stdclass();
$lang->project->menu = new stdclass();
if($config->systemMode == 'new')
{
    $lang->project->menu->browse = array('link' => '项目|project|browse|');
}
else
{
    $lang->project->menu->browse = array('link' => "$lang->executionCommon|project|browse|");
}

$lang->project->dividerMenu = ',execution,programplan,doc,dynamic,';

$lang->program->viewMenu = new stdclass();
$lang->program->viewMenu->product     = array('link' => '产品|program|product|program=%s', 'alias' => 'view');
$lang->program->viewMenu->project     = array('link' => "项目|program|project|program=%s");
$lang->program->viewMenu->personnel   = array('link' => "人员|personnel|invest|program=%s");
$lang->program->viewMenu->stakeholder = array('link' => "干系人|program|stakeholder|program=%s", 'alias' => 'createstakeholder');

$lang->personnel = new stdClass();
$lang->personnel->menu = new stdClass();
$lang->personnel->menu->invest    = array('link' => "投入人员|personnel|invest|program=%s");
$lang->personnel->menu->accessible = array('link' => "可访问人员|personnel|accessible|program=%s");
$lang->personnel->menu->whitelist  = array('link' => "白名单|personnel|whitelist|program=%s", 'alias' => 'addwhitelist');

/* Scrum menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->home = '仪表盘|product|index|';
$lang->product->menu->list = array('link' => $lang->productCommon . '|product|all|', 'alias' => 'create,batchedit,manageline');

$lang->product->viewMenu = new stdclass();
$lang->product->viewMenu->dashboard   = array('link' => '仪表盘|product|dashboard|productID=%s');
if($config->URAndSR) $lang->product->viewMenu->requirement = array('link' => "$lang->URCommon|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->story       = array('link' => "$lang->SRCommon|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->plan        = array('link' => "计划|productplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->product->viewMenu->release     = array('link' => '发布|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->viewMenu->roadmap     = '路线图|product|roadmap|productID=%s';
$lang->product->viewMenu->project     = "项目|product|project|status=all&productID=%s";
$lang->product->viewMenu->track       = array('link' => "矩阵|story|track|productID=%s");
$lang->product->viewMenu->doc         = array('link' => '文档|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->viewMenu->dynamic     = '动态|product|dynamic|productID=%s';
$lang->product->viewMenu->set         = array('link' => '设置|product|view|productID=%s', 'subModule' => 'tree,branch', 'alias' => 'edit');

$lang->product->setMenu = new stdclass();
$lang->product->setMenu->view      = array('link' => '概况|product|view|productID={PRODUCT}', 'alias' => 'edit');
$lang->product->setMenu->module    = array('link' => '模块|tree|browse|product={PRODUCT}&view=story', 'subModule' => 'tree');
$lang->product->setMenu->branch    = array('link' => '@branch@|branch|manage|product={PRODUCT}', 'subModule' => 'branch');
$lang->product->setMenu->whitelist = array('link' => '白名单|product|whitelist|product={PRODUCT}', 'subModule' => 'personnel');

$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->productplan = new stdclass();

$lang->release->menu     = $lang->product->viewMenu;
$lang->branch->menu      = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->team     = array('link' => '团队|my|team|', 'subModule' => 'user');
$lang->system->menu->calendar = array('link' => '日程|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');
$lang->system->menu->dynamic  = '动态|company|dynamic|';
$lang->system->menu->view     = array('link' => '公司|company|view');

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();

$lang->searchTips = '';
$lang->searchAB   = '搜索';

/* 查询中可以选择的对象列表。*/
$lang->searchObjects['all']         = '全部';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = '需求';
$lang->searchObjects['task']        = '任务';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = '版本';
$lang->searchObjects['release']     = '发布';
$lang->searchObjects['productplan'] = $lang->productCommon . '计划';
$lang->searchObjects['testtask']    = '测试单';
$lang->searchObjects['doc']         = '文档';
$lang->searchObjects['caselib']     = '用例库';
$lang->searchObjects['testreport']  = '测试报告';
$lang->searchObjects['program']     = '项目集';
$lang->searchObjects['project']     = '项目';
$lang->searchObjects['execution']   = $lang->executionCommon;
$lang->searchObjects['user']        = '用户';
$lang->searchTips                   = '编号(ctrl+g)';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = '全部记录';
$lang->exportTypeList['selected'] = '选中记录';

/* 语言 */
$lang->lang = 'Language';

/* 风格列表。*/
$lang->theme                = '主题';
$lang->themes['default']    = '禅道蓝（默认）';
$lang->themes['green']      = '叶兰绿';
$lang->themes['red']        = '赤诚红';
$lang->themes['purple']     = '玉烟紫';
$lang->themes['pink']       = '芙蕖粉';
$lang->themes['blackberry'] = '露莓黑';
$lang->themes['classic']    = '经典蓝';

/* 首页菜单设置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "浏览{$lang->productCommon}|product|browse";
$lang->index->menu->project = "浏览{$lang->executionCommon}|project|browse";

/* 我的地盘菜单设置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index       = '仪表盘|my|index';
$lang->my->menu->myWork      = array('link' => '待处理|my|work|mode=task');
if($config->systemMode == 'new')
{
    $lang->my->menu->myProject   = array('link' => '项目|my|project|');
    $lang->my->menu->myExecution = '执行|my|execution|type=undone';
}
else
{
    $lang->my->menu->myExecution = $lang->executionCommon . '|my|execution|type=undone';
}
$lang->my->menu->contribute  = array('link' => '贡献|my|contribute|mode=task');
$lang->my->menu->dynamic     = '动态|my|dynamic|';
$lang->my->menu->score       = array('link' => '积分|my|score|', 'subModule' => 'score');
$lang->my->menu->contacts    = '联系人|my|managecontacts|';

$lang->my->workMenu = new stdclass();
$lang->my->workMenu->task        = '任务|my|work|mode=task';
if($config->URAndSR) $lang->my->workMenu->requirement = "$lang->URCommon|my|work|mode=requirement";
$lang->my->workMenu->story       = "$lang->SRCommon|my|work|mode=story";
$lang->my->workMenu->bug         = 'Bug|my|work|mode=bug';
$lang->my->workMenu->testcase    = '用例|my|work|mode=testcase&type=assigntome';
$lang->my->workMenu->testtask    = '测试单|my|work|mode=testtask&type=wait';

$lang->my->contributeMenu = new stdclass();
$lang->my->contributeMenu->task        = '任务|my|contribute|mode=task';
if($config->URAndSR) $lang->my->contributeMenu->requirement = "$lang->URCommon|my|contribute|mode=requirement";
$lang->my->contributeMenu->story       = "$lang->SRCommon|my|contribute|mode=story";
$lang->my->contributeMenu->bug         = 'Bug|my|contribute|mode=bug';
$lang->my->contributeMenu->testcase    = '用例|my|contribute|mode=testcase&type=openedbyme';
$lang->my->contributeMenu->testtask    = '测试单|my|contribute|mode=testtask&type=done';

$lang->my->dividerMenu = ',myWork,score,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->product->dividerMenu = $config->URAndSR ? ',requirement,set,' : ',track,set,';

$lang->story = new stdclass();

$lang->story->menu = $lang->product->menu;

/* 执行视图菜单设置。*/
$lang->execution = new stdclass();
$lang->execution->homeMenu = new stdclass();
$lang->execution->homeMenu->index = '仪表盘|execution|index|';
$lang->execution->homeMenu->list  = array('link' => '执行|execution|all|', 'alias' => 'batchedit');

$lang->execution->menu = new stdclass();
$lang->execution->menu->task     = array('link' => '任务|execution|task|executionID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->execution->menu->kanban   = array('link' => '看板|execution|kanban|executionID=%s');
$lang->execution->menu->burn     = array('link' => '燃尽图|execution|burn|executionID=%s');
$lang->execution->menu->view     = array('link' => '视图|execution|grouptask|executionID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->execution->menu->story    = array('link' => "{$lang->SRCommon}|execution|story|executionID=%s", 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->execution->menu->qa       = array('link' => '测试|execution|qa|', 'alias' => 'qa,bug,testcase,testtask,testreport');
$lang->execution->menu->repo     = array('link' => '代码|repo|browse|projectID=%s');
$lang->execution->menu->doc      = array('link' => '文档|doc|objectLibs|type=execution&objectID=%s&from=execution', 'subModule' => 'doc');
$lang->execution->menu->build    = array('link' => '版本|execution|build|executionID=%s', 'subModule' => 'build');
$lang->execution->menu->release  = array('link' => '发布|projectrelease|browse|project=%s');
$lang->execution->menu->action   = array('link' => '动态|execution|dynamic|executionID=%s');
$lang->execution->menu->setting  = array('link' => '设置|execution|view|executionID=%s', 'subModule' => 'personnel', 'alias' => 'edit,manageproducts,team,whitelist,addwhitelist,managemembers', 'class' => 'dropdown dropdown-hover');

$lang->execution->viewMenu = new stdclass();
$lang->execution->viewMenu->groupTask = '分组视图|execution|grouptask|executionID=%s';
$lang->execution->viewMenu->tree      = '树状图|execution|tree|executionID=%s';

$lang->execution->qaMenu = new stdclass();
$lang->execution->qaMenu->qa         = array('link' => '仪表盘|execution|qa|executionID=%s');
$lang->execution->qaMenu->bug        = array('link' => 'Bug|execution|bug|executionID=%s');
$lang->execution->qaMenu->testcase   = array('link' => '用例|execution|testcase|executionID=%s');
$lang->execution->qaMenu->testtask   = array('link' => '测试单|execution|testtask|executionID=%s');
$lang->execution->qaMenu->testreport = array('link' => '报告|execution|testreport|exeutionID=%s');

$lang->execution->settingMenu = new stdclass();
$lang->execution->settingMenu = new stdclass();
$lang->execution->settingMenu->view      = array('link' => '概况|execution|view|executionID={EXECUTION}', 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');
$lang->execution->settingMenu->products  = $lang->productCommon . '|execution|manageproducts|executionID={EXECUTION}';
$lang->execution->settingMenu->team      = array('link' => '团队|execution|team|executionID={EXECUTION}', 'alias' => 'managemembers');
$lang->execution->settingMenu->whitelist = array('link' => '白名单|execution|whitelist|executionID={EXECUTION}', 'subModule' => 'personnel', 'alias' => 'addwhitelist');

$lang->execution->dividerMenu = ',story,build,setting,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->execution->menu;
$lang->build->menu = $lang->execution->menu;

/* QA视图菜单设置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->index      = array('link' => '仪表盘|qa|index');
$lang->qa->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'subModule' => 'testsuite,caselib', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report,importunitresult', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature   = array('link' => '功能测试|testcase|browse|productID=%s', 'subModule' => 'testcase,tree,story');
$lang->qa->subMenu->testcase->unit      = array('link' => '单元测试|testtask|browseUnits|productID=%s', 'alias' => 'browseunits');
$lang->qa->subMenu->testcase->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'subModule' => 'testsuite');
$lang->qa->subMenu->testcase->caselib   = array('link' => '用例库|caselib|browse|libID=0');

$lang->qa->subMenu->testtask = new stdclass();
$lang->qa->subMenu->testtask->testtask = array('link' => '测试单|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report,importunitresult');
$lang->qa->subMenu->testtask->report   = array('link' => '报告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');

$lang->qa->subMenu->automation = new stdclass();
$lang->qa->subMenu->automation->browse      = array('link' => '介绍|automation|browse|productID=%s', 'alias' => '');
//$lang->qa->subMenu->automation->framework   = array('link' => '框架|automation|framework|productID=%s', 'alias' => '');
//$lang->qa->subMenu->automation->data        = array('link' => '数据|automation|date|productID=%s', 'alias' => '');
//$lang->qa->subMenu->automation->interface   = array('link' => '接口|automation|interface|productID=%s', 'alias' => '');
//$lang->qa->subMenu->automation->environment = array('link' => '环境|automation|environment|productID=%s', 'alias' => '');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->index      = array('link' => '仪表盘|qa|index');
$lang->bug->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->index      = array('link' => '仪表盘|qa|index');
$lang->testcase->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->index      = array('link' => '仪表盘|qa|index');
$lang->testtask->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->index      = array('link' => '仪表盘|qa|index');
$lang->testsuite->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'subModule' => 'testsuite', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->index      = array('link' => '仪表盘|qa|index');
$lang->testreport->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'subModule' => 'testreport', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->subMenu = $lang->qa->subMenu;
$lang->caselib->menu->index      = array('link' => '仪表盘|qa|index');
$lang->caselib->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->caselib->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'subModule' => 'caselib', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->automation = new stdclass();
$lang->automation->menu = new stdclass();
$lang->automation->subMenu = $lang->qa->subMenu;
$lang->automation->menu->index      = array('link' => '仪表盘|qa|index');
$lang->automation->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s');
$lang->automation->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->automation->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'subModule' => 'testreport', 'class' => 'dropdown dropdown-hover');
$lang->automation->menu->automation = array('link' => '自动化|automation|browse|productID=%s', 'alias' => '', 'class' => 'dropdown dropdown-hover');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => '代码|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => '构建|job|browse', 'subModule' => 'compile,job');
$lang->ci->menu->jenkins  = array('link' => 'Jenkins|jenkins|browse', 'alias' => 'create,edit');
$lang->ci->menu->maintain = array('link' => '版本库|repo|maintain', 'alias' => 'create,edit');
$lang->ci->menu->rules    = array('link' => '指令|repo|setrules');

$lang->repo          = new stdclass();
$lang->jenkins       = new stdclass();
$lang->compile       = new stdclass();
$lang->job           = new stdclass();
$lang->repo->menu    = $lang->ci->menu;
$lang->jenkins->menu = $lang->ci->menu;
$lang->compile->menu = $lang->ci->menu;
$lang->job->menu     = $lang->ci->menu;

/* 文档视图菜单设置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->svn = new stdclass();
$lang->git = new stdclass();

/* 发布视图菜单设置。*/
$lang->projectrelease = new stdclass();
$lang->projectrelease->menu = new stdclass();

/* 统计视图菜单设置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual    = array('link' => '年度总结|report|annualData|year=&dept=&userID=' . (isset($_SESSION['user']) ? zget($_SESSION['user'], 'id', 0) : 0), 'target' => '_blank');
$lang->report->menu->product   = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->execution = array('link' => '执行|report|executiondeviation');
$lang->report->menu->test      = array('link' => '测试|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff     = array('link' => '组织|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = '注：统计报表的数据来源于列表页面的检索结果，生成统计报表前请先在列表页面进行检索。比如列表页面我们检索的是%tab%，那么报表就是基于之前检索的%tab%的结果集进行统计。';

/* 组织结构视图菜单设置。*/
$lang->company = new stdclass();
$lang->dept    = new stdclass();
$lang->group   = new stdclass();
$lang->user    = new stdclass();
$lang->company->menu = new stdclass();
$lang->dept->menu    = new stdclass();
$lang->group->menu   = new stdclass();
$lang->user->menu    = new stdclass();

$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->browseUser  = array('link' => '用户|company|browse', 'subModule' => ',user,');
$lang->company->menu->dept        = array('link' => '部门|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '权限|group|browse', 'subModule' => 'group');

/* 后台管理菜单设置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => '首页|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->company   = array('link' => '人员|company|browse|', 'subModule' => ',user,dept,group,');
$lang->admin->menu->model     = array('link' => '模型|custom|browsestoryconcept|', 'subModule' => 'holiday');
$lang->admin->menu->custom    = array('link' => '自定义|custom|index', 'subModule' => 'custom');
$lang->admin->menu->extension = array('link' => '插件|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->dev       = array('link' => '二次开发|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor,entry');
$lang->admin->menu->message   = array('link' => '通知|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->system    = array('link' => '系统|backup|index', 'subModule' => 'cron,backup,action');

$lang->subject = new stdclass();
$lang->subject->menu = new stdclass();
$lang->subject->menu->storyConcept = array('link' => '需求概念|custom|browsestoryconcept|');

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail    = array('link' => '邮件|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => '浏览器|message|browser');
$lang->admin->subMenu->message->setting = array('link' => '设置|message|setting');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = 'ZDOO|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api    = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db     = array('link' => '数据库|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => '编辑器|dev|editor');
$lang->admin->subMenu->dev->entry  = array('link' => '应用|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->data       = array('link' => '数据|backup|index', 'subModule' => 'action');
$lang->admin->subMenu->system->safe       = array('link' => '安全|admin|safe', 'alias' => 'checkweak');
$lang->admin->subMenu->system->cron       = array('link' => '定时|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone   = array('link' => '时区|custom|timezone', 'subModule' => 'custom');
$lang->admin->subMenu->system->buildIndex = array('link' => '重建索引|search|buildindex|');

$lang->admin->dividerMenu = ',company,message,system,';

$lang->convert   = new stdclass();
$lang->upgrade   = new stdclass();
$lang->action    = new stdclass();
$lang->backup    = new stdclass();
$lang->extension = new stdclass();
$lang->custom    = new stdclass();
$lang->mail      = new stdclass();
$lang->cron      = new stdclass();
$lang->dev       = new stdclass();
$lang->entry     = new stdclass();
$lang->webhook   = new stdclass();
$lang->message   = new stdclass();
$lang->search    = new stdclass();

/* 菜单分组。*/
$lang->menugroup = new stdclass();
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->branch      = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->case        = 'qa';
$lang->menugroup->testreport  = 'qa';
$lang->menugroup->people      = 'admin';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->score       = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->backup      = 'admin';
$lang->menugroup->cron        = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->custom      = 'admin';
$lang->menugroup->mail        = 'admin';
$lang->menugroup->dev         = 'admin';
$lang->menugroup->entry       = 'admin';
$lang->menugroup->webhook     = 'admin';
$lang->menugroup->message     = 'admin';

$lang->menugroup->repo    = 'ci';
$lang->menugroup->jenkins = 'ci';
$lang->menugroup->compile = 'ci';
$lang->menugroup->job     = 'ci';

/* Nav group.*/
$lang->navGroup = new stdclass();
$lang->navGroup->my     = 'my';
$lang->navGroup->effort = 'my';
$lang->navGroup->score  = 'my';

$lang->navGroup->program   = 'program';
$lang->navGroup->personnel = 'program';

$lang->navGroup->product     = 'product';
$lang->navGroup->productplan = 'product';
$lang->navGroup->release     = 'product';
$lang->navGroup->branch      = 'product';
$lang->navGroup->story       = 'product';
$lang->navGroup->tree        = 'product';

$lang->navGroup->project     = 'project';
$lang->navGroup->qa          = 'project';
$lang->navGroup->bug         = 'project';
$lang->navGroup->doc         = 'project';
$lang->navGroup->testcase    = 'project';
$lang->navGroup->testtask    = 'project';
$lang->navGroup->testreport  = 'project';
$lang->navGroup->testsuite   = 'project';
$lang->navGroup->caselib     = 'project';
$lang->navGroup->feedback    = 'project';
$lang->navGroup->deploy      = 'project';
$lang->navGroup->stakeholder = 'project';

$lang->navGroup->projectstory   = 'project';
$lang->navGroup->review         = 'project';
$lang->navGroup->reviewissue    = 'project';
$lang->navGroup->milestone      = 'project';
$lang->navGroup->pssp           = 'project';
$lang->navGroup->auditplan      = 'project';
$lang->navGroup->cm             = 'project';
$lang->navGroup->nc             = 'project';
$lang->navGroup->projectrelease = 'project';
$lang->navGroup->projectbuild   = 'project';
$lang->navGroup->repo           = 'project';
$lang->navGroup->job            = 'project';
$lang->navGroup->jenkins        = 'project';
$lang->navGroup->compile        = 'project';
$lang->navGroup->report         = 'project';
$lang->navGroup->measrecord     = 'project';

$lang->navGroup->execution = 'execution';
$lang->navGroup->task      = 'execution';
$lang->navGroup->build     = 'execution';

$lang->navGroup->company       = 'system';
$lang->navGroup->sqlbuilder    = 'system';
$lang->navGroup->auditcl       = 'system';
$lang->navGroup->cmcl          = 'system';
$lang->navGroup->todo          = 'system';
$lang->navGroup->ldap          = 'system';
$lang->navGroup->process       = 'system';
$lang->navGroup->activity      = 'system';
$lang->navGroup->zoutput       = 'system';
$lang->navGroup->classify      = 'system';
$lang->navGroup->subject       = 'system';
$lang->navGroup->baseline      = 'system';
$lang->navGroup->reviewcl      = 'system';
$lang->navGroup->reviewsetting = 'system';

$lang->navGroup->attend   = 'attend';
$lang->navGroup->leave    = 'attend';
$lang->navGroup->makeup   = 'attend';
$lang->navGroup->overtime = 'attend';
$lang->navGroup->lieu     = 'attend';

$lang->navGroup->admin     = 'admin';
$lang->navGroup->dept      = 'admin';
$lang->navGroup->user      = 'admin';
$lang->navGroup->group     = 'admin';
$lang->navGroup->dept      = 'admin';
$lang->navGroup->webhook   = 'admin';
$lang->navGroup->sms       = 'admin';
$lang->navGroup->message   = 'admin';
$lang->navGroup->custom    = 'admin';
$lang->navGroup->cron      = 'admin';
$lang->navGroup->backup    = 'admin';
$lang->navGroup->mail      = 'admin';
$lang->navGroup->dev       = 'admin';
$lang->navGroup->entry     = 'admin';
$lang->navGroup->extension = 'admin';
$lang->navGroup->action    = 'admin';
$lang->navGroup->search    = 'admin';

/* 错误提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且大于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-数据-回收站还原。";
$lang->error->gt              = "『%s』应当大于『%s』。";
$lang->error->ge              = "『%s』应当不小于『%s』。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->empty           = "『%s』必须为空。";
$lang->error->equal           = "『%s』必须为『%s』。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->URL             = "『%s』应当为合法的URL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->datetime        = "『%s』应当为合法的日期。";
$lang->error->code            = "『%s』应当为字母或数字的组合。";
$lang->error->account         = "『%s』只能是字母、数字或下划线的组合三位以上。";
$lang->error->passwordsame    = "两次密码应该相同。";
$lang->error->passwordrule    = "密码应该符合规则，长度至少为六位。";
$lang->error->accessDenied    = '您没有访问权限';
$lang->error->pasteImg        = '您的浏览器不支持粘贴图片！';
$lang->error->noData          = '没有数据';
$lang->error->editedByOther   = '该记录可能已经被改动。请刷新页面重新编辑！';
$lang->error->tutorialData    = '新手模式下不会插入数据，请退出新手模式操作';
$lang->error->noCurlExt       = '服务器未安装Curl模块。';

/* 分页信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord     = "暂时没有记录";
$lang->pager->digest       = "共 <strong>%s</strong> 条记录，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = "每页 <strong>%s</strong> 条";
$lang->pager->first        = "<i class='icon-step-backward' title='首页'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='上一页'></i>";
$lang->pager->next         = "<i class='icon-play' title='下一页'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='末页'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "上一页";
$lang->pager->nextPage     = "下一页";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 项，共 <strong>%s</strong> 项";
$lang->pager->pageOfText   = '第 {0} 页';
$lang->pager->firstPage    = '第一页';
$lang->pager->lastPage     = '最后一页';
$lang->pager->goto         = '跳转';
$lang->pager->pageOf       = '第 <strong>{page}</strong> 页';
$lang->pager->totalPage    = '共 <strong>{totalPage}</strong> 页';
$lang->pager->totalCount   = '共 <strong>{recTotal}</strong> 项';
$lang->pager->pageSize     = '每页 <strong>{recPerPage}</strong> 项';
$lang->pager->itemsRange   = '第 <strong>{start}</strong> ~ <strong>{end}</strong> 项';
$lang->pager->pageOfTotal  = '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 页';

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = '不是有效的颜色值';

$lang->downNotify     = "下载桌面提醒";
$lang->clientName     = "客户端";
$lang->downloadClient = "下载客户端";
$lang->clientHelp     = "客户端使用说明";
$lang->clientHelpLink = "http://www.zentao.net/book/zentaopmshelp/302.html#2";
$lang->website        = "https://www.zentao.net";

$lang->suhosinInfo     = "警告：数据太多，请在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->maxVarsInfo     = "警告：数据太多，请在php.ini中修改<font color=red>max_input_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->pasteTextInfo   = "粘贴文本到文本域中，每行文字作为一条数据的标题。";
$lang->noticeImport    = "导入数据中，含有已经存在系统的数据，请确认这些数据要覆盖或者全新插入。";
$lang->importConfirm   = "导入确认";
$lang->importAndCover  = "覆盖";
$lang->importAndInsert = "全新插入";

$lang->noResultsMatch    = "没有匹配结果";
$lang->searchMore        = "搜索此关键字的更多结果：";
$lang->chooseUsersToMail = "选择要发信通知的用户...";
$lang->noticePasteImg    = "可以在编辑器直接贴图。";
$lang->pasteImgFail      = "贴图失败，请稍后重试。";
$lang->pasteImgUploading = "正在上传图片，请稍后...";

/* 时间格式设置。*/
if(!defined('DT_DATETIME1'))  define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))  define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1')) define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2')) define('DT_MONTHTIME2', 'n月d日 H:i');
if(!defined('DT_DATE1'))      define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))      define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))      define('DT_DATE3',     'Y年m月d日');
if(!defined('DT_DATE4'))      define('DT_DATE4',     'n月j日');
if(!defined('DT_DATE5'))      define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))      define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))      define('DT_TIME2',     'H:i');
if(!defined('LONG_TIME'))     define('LONG_TIME',    '2059-12-31');

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本周';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '关闭';
$lang->datepicker->dpText->TEXT_DATE        = '选择时间段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '选择日期';

$lang->datepicker->dayNames     = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

/* Common action icons 通用动作图标 */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'product';
$lang->icons['bug']       = 'bug';
$lang->icons['task']      = 'check-sign';
$lang->icons['tasks']     = 'tasks';
$lang->icons['program']   = 'program';
$lang->icons['project']   = 'project';
$lang->icons['stage']     = 'waterfall';
$lang->icons['sprint']    = 'sprint';
$lang->icons['doc']       = 'file-text';
$lang->icons['doclib']    = 'folder-close';
$lang->icons['story']     = 'lightbulb';
$lang->icons['release']   = 'tags';
$lang->icons['roadmap']   = 'code-fork';
$lang->icons['plan']      = 'flag';
$lang->icons['dynamic']   = 'volume-up';
$lang->icons['build']     = 'tag';
$lang->icons['test']      = 'check';
$lang->icons['testtask']  = 'check';
$lang->icons['group']     = 'group';
$lang->icons['team']      = 'group';
$lang->icons['company']   = 'sitemap';
$lang->icons['user']      = 'user';
$lang->icons['dept']      = 'sitemap';
$lang->icons['tree']      = 'sitemap';
$lang->icons['usecase']   = 'sitemap';
$lang->icons['testcase']  = 'sitemap';
$lang->icons['result']    = 'list-alt';
$lang->icons['mail']      = 'envelope';
$lang->icons['trash']     = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app']       = 'th-large';

$lang->icons['results']            = 'list-alt';
$lang->icons['create']             = 'plus';
$lang->icons['post']               = 'edit';
$lang->icons['batchCreate']        = 'plus-sign';
$lang->icons['batchEdit']          = 'edit-sign';
$lang->icons['batchClose']         = 'off';
$lang->icons['edit']               = 'edit';
$lang->icons['delete']             = 'close';
$lang->icons['copy']               = 'copy';
$lang->icons['report']             = 'bar-chart';
$lang->icons['export']             = 'export';
$lang->icons['report-file']        = 'file-powerpoint';
$lang->icons['import']             = 'import';
$lang->icons['finish']             = 'checked';
$lang->icons['resolve']            = 'check';
$lang->icons['start']              = 'play';
$lang->icons['restart']            = 'play';
$lang->icons['run']                = 'run';
$lang->icons['runCase']            = 'run';
$lang->icons['batchRun']           = 'play-sign';
$lang->icons['assign']             = 'hand-right';
$lang->icons['assignTo']           = 'hand-right';
$lang->icons['change']             = 'fork';
$lang->icons['link']               = 'link';
$lang->icons['close']              = 'off';
$lang->icons['activate']           = 'magic';
$lang->icons['review']             = 'glasses';
$lang->icons['confirm']            = 'search';
$lang->icons['confirmBug']         = 'search';
$lang->icons['putoff']             = 'calendar';
$lang->icons['suspend']            = 'pause';
$lang->icons['pause']              = 'pause';
$lang->icons['cancel']             = 'ban-circle';
$lang->icons['recordEstimate']     = 'time';
$lang->icons['customFields']       = 'cogs';
$lang->icons['manage']             = 'cog';
$lang->icons['unlock']             = 'unlock-alt';
$lang->icons['confirmStoryChange'] = 'search';
$lang->icons['score']              = 'tint';

/* Scrum menu. */
$lang->menu = new stdclass();
$lang->menu->scrum = new stdclass();
$lang->menu->scrum->index          = '仪表盘|project|index|project={PROJECT}';
$lang->menu->scrum->execution      = "$lang->executionCommon|execution|all|status=all&projectID={PROJECT}&from=project";
$lang->menu->scrum->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story', 'alias' => 'story,track');
$lang->menu->scrum->doc            = '文档|doc|index|';
$lang->menu->scrum->qa             = array('link' => '测试|qa|index', 'subModule' => 'testcase,testtask');
$lang->menu->scrum->ci             = '代码|repo|browse';
$lang->menu->scrum->projectbuild   = array('link' => '版本|projectbuild|browse|project={PROJECT}');
$lang->menu->scrum->projectrelease = array('link' => '发布|projectrelease|browse|project={PROJECT}');
$lang->menu->scrum->dynamic        = array('link' => '动态|project|dynamic|project={PROJECT}');
$lang->menu->scrum->projectsetting = array('link' => '设置|project|view|project={PROJECT}', 'subModule' => 'stakeholder', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->projectQa = new stdclass();
$lang->projectQa->menu = new stdclass();
$lang->projectQa->menu->index      = array('link' => '仪表盘|qa|index');
$lang->projectQa->menu->bug        = array('link' => 'Bug|bug|browse|productID=%s', 'subModule' => 'bug');
$lang->projectQa->menu->testcase   = array('link' => '用例|testcase|browse|productID=%s', 'subModule' => 'testsuite,testcase,caselib');
$lang->projectQa->menu->testtask   = array('link' => '测试单|testtask|browse|productID=%s', 'subModule' => 'testtask', 'class' => 'dropdown dropdown-hover');

$lang->projectQa->subMenu = new stdclass();
$lang->projectQa->subMenu->testtask = new stdclass();
$lang->projectQa->subMenu->testtask->testtask = array('link' => '测试单|testtask|browse|productID=%s');
$lang->projectQa->subMenu->testtask->report   = array('link' => '报告|testreport|browse|productID=%s');

$lang->scrum = new stdclass();
$lang->scrum->setMenu = new stdclass();
$lang->scrum->setMenu->view        = array('link' => '概况|project|view|project={PROJECT}', 'alias' => 'edit');
$lang->scrum->setMenu->products    = array('link' => '产品|project|manageProducts|project={PROJECT}', 'alias' => 'manageproducts');
$lang->scrum->setMenu->members     = array('link' => '团队|project|manageMembers|project={PROJECT}', 'alias' => 'managemembers');
$lang->scrum->setMenu->whitelist   = array('link' => '白名单|project|whitelist|project={PROJECT}', 'subModule' => 'personnel');
$lang->scrum->setMenu->stakeholder = array('link' => '干系人|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->scrum->setMenu->group       = array('link' => '权限|project|group|project={PROJECT}', 'alias' => 'group,manageview,managepriv');

/* Waterfall menu. */
$lang->menu->waterfall = new stdclass();
$lang->menu->waterfall->index          = array('link' => '仪表盘|project|index|project={PROJECT}');
$lang->menu->waterfall->programplan    = array('link' => '计划|programplan|browse|project={PROJECT}', 'subModule' => 'programplan');
$lang->menu->waterfall->project        = array('link' => $lang->executionCommon . '|project|task|executionID={EXECUTION}', 'subModule' => ',project,task,');
$lang->menu->waterfall->doc            = array('link' => '文档|doc|index|project={PROJECT}');
$lang->menu->waterfall->weekly         = array('link' => '报告|weekly|index|project={PROJECT}', 'subModule' => ',milestone,');
$lang->menu->waterfall->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story');
$lang->menu->waterfall->design         = '设计|design|browse|product={PRODUCT}';
$lang->menu->waterfall->ci             = '代码|repo|browse|';
$lang->menu->waterfall->track          = array('link' => '矩阵|projectstory|track', 'alias' => 'track');
$lang->menu->waterfall->qa             = '测试|qa|index';
$lang->menu->waterfall->projectrelease = array('link' => '发布|projectrelease|browse');
$lang->menu->waterfall->projectbuild   = array('link' => '版本|projectbuild|browse|project={PROJECT}');
$lang->menu->waterfall->dynamic        = array('link' => '动态|project|dynamic|project={PROJECT}');
$lang->menu->waterfall->other          = array('link' => '其他|project|other', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'issue,risk,stakeholder,nc,workestimation,durationestimation,budget,pssp,measrecord,report');
$lang->menu->waterfall->projectsetting = array('link' => '设置|project|view|project={PROJECT}', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->waterfall = new stdclass();
$lang->waterfall->subMenu = new stdclass();
$lang->waterfall->subMenu->other = new stdclass();
$lang->waterfall->subMenu->other->estimation  = array('link' => '估算|workestimation|index|program=%s', 'subModule' => 'workestimation,durationestimation,budget');
$lang->waterfall->subMenu->other->issue       = array('link' => '问题|issue|browse|', 'subModule' => 'issue');
$lang->waterfall->subMenu->other->risk        = array('link' => '风险|risk|browse|', 'subModule' => 'risk');
$lang->waterfall->subMenu->other->stakeholder = array('link' => '干系人|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->waterfall->subMenu->other->report      = array('link' => '度量|report|projectsummary|project=%s', 'subModule' => ',report,measrecord');
$lang->waterfall->subMenu->other->auditplan   = array('link' => 'QA|auditplan|browse|', 'subModule' => 'nc');

$lang->waterfall->setMenu = new stdclass();
$lang->waterfall->setMenu = $lang->scrum->setMenu;

$lang->waterfallproduct   = new stdclass();
$lang->review             = new stdclass();
$lang->milestone          = new stdclass();
$lang->auditplan          = new stdclass();
$lang->cm                 = new stdclass();
$lang->nc                 = new stdclass();
$lang->pssp               = new stdclass();
$lang->stakeholder        = new stdclass();
$lang->projectstory       = new stdclass();

$lang->review->menu             = new stdclass();
$lang->milestone->menu          = new stdclass();
$lang->auditplan->menu          = new stdclass();
$lang->cm->menu                 = new stdclass();
$lang->pssp->menu               = new stdclass();
$lang->stakeholder->menu        = new stdclass();
$lang->waterfallproduct->menu   = new stdclass();
$lang->projectstory->menu       = new stdclass();

$lang->stakeholder->menu = $lang->scrum->setMenu;

$lang->nc->menu = $lang->auditplan->menu;
$lang->noMenuModule = array('report', 'my', 'todo', 'effort', 'program', 'product', 'execution', 'task', 'build', 'productplan', 'projectbuild', 'projectrelease', 'projectstory', 'story', 'branch', 'release', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'custom', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'pssp', 'sms', 'message', 'webhook', 'search', 'score', 'stage', 'entry');

include (dirname(__FILE__) . '/menuOrder.php');
