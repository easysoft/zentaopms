<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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

$lang->zentaoPMS      = '禪道';
$lang->logoImg        = 'zt-logo.png';
$lang->welcome        = "%s項目管理系統";
$lang->logout         = '退出';
$lang->login          = '登錄';
$lang->help           = '幫助';
$lang->aboutZenTao    = '關於禪道';
$lang->profile        = '個人檔案';
$lang->changePassword = '更改密碼';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間: %s 毫秒, 內存: %s KB, 查詢: %s.  </div></div>";
$lang->agreement      = "已閲讀並同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授權協議1.2》</a>。<span class='text-danger'>未經許可，不得去除、隱藏或遮掩禪道軟件的任何標誌及連結。</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'>Designed by <strong>艾體驗</strong></a>";

$lang->reset        = '重填';
$lang->cancel       = '取消';
$lang->refresh      = '刷新';
$lang->edit         = '編輯';
$lang->delete       = '刪除';
$lang->close        = '關閉';
$lang->unlink       = '移除';
$lang->import       = '導入';
$lang->export       = '導出';
$lang->setFileName  = '檔案名：';
$lang->submitting   = '稍候...';
$lang->save         = '保存';
$lang->saveSuccess  = '保存成功';
$lang->confirm      = '確認';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->goPC         = 'PC版';
$lang->more         = '更多';
$lang->day          = '天';
$lang->customConfig = '自定義';
$lang->public       = '公共';
$lang->trunk        = '主幹';
$lang->sort         = '排序';
$lang->required     = '必填';
$lang->noData       = '暫無';
$lang->fullscreen   = '全屏';
$lang->retrack      = '收起';
$lang->recent       = '近期';
$lang->whitelist    = '訪問白名單';

$lang->actions         = '操作';
$lang->restore         = '恢復預設';
$lang->comment         = '備註';
$lang->history         = '歷史記錄';
$lang->attatch         = '附件';
$lang->reverse         = '切換順序';
$lang->switchDisplay   = '切換顯示';
$lang->expand          = '展開全部';
$lang->collapse        = '收起';
$lang->saveSuccess     = '保存成功';
$lang->fail            = '失敗';
$lang->addFiles        = '上傳了附件 ';
$lang->files           = '附件 ';
$lang->pasteText       = '多項錄入';
$lang->uploadImages    = '多圖上傳 ';
$lang->timeout         = '連接超時，請檢查網絡環境，或重試！';
$lang->repairTable     = '資料庫表可能損壞，請用phpmyadmin或myisamchk檢查修復。';
$lang->duplicate       = '已有相同標題的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理員限制當前IP登錄，請聯繫管理員解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '設為模組首頁';
$lang->noviceTutorial  = '新手教程';
$lang->changeLog       = '修改日誌';
$lang->manual          = '手冊';
$lang->customMenu      = '自定義導航';
$lang->customField     = '自定義表單項';
$lang->lineNumber      = '行號';
$lang->tutorialConfirm = '檢測到你尚未退出新手教程模式，是否現在退出？';

$lang->preShortcutKey  = '[快捷鍵:←]';
$lang->nextShortcutKey = '[快捷鍵:→]';
$lang->backShortcutKey = '[快捷鍵:Alt+↑]';

$lang->select        = '選擇';
$lang->selectAll     = '全選';
$lang->selectReverse = '反選';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您訪問的對象並不存在！';
$lang->notPage       =  '抱歉，您訪問的功能正在開發中！';
$lang->showAll       = '[[全部顯示]]';
$lang->selectedItems = '已選擇 <strong>{0}</strong> 項';

$lang->future      = '未來';
$lang->year        = '年';
$lang->workingHour = '工時';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';

$lang->common = new stdclass();
$lang->common->common = '公有模組';

global $config;
if($config->URAndSR)
{
    $URCommon = zget($lang, 'URCommon', "用戶需求");
    $SRCommon = zget($lang, 'SRCommon', "軟件需求");
}

/* 主導航菜單。*/
$lang->mainNav = new stdclass();
$lang->mainNav->my      = '<i class="icon icon-menu-my"></i> 地盤|my|index|';
$lang->mainNav->program = '<i class="icon icon-folder-open-o"></i> 項目集|program|pgmindex|';
$lang->mainNav->product = '<i class="icon icon-menu-project"></i> 產品|product|index|';
$lang->mainNav->project = '<i class="icon icon-file"></i> 項目|program|prjbrowse|';
$lang->mainNav->system  = '<i class="icon icon-menu-users"></i> 組織|custom|estimate|';
$lang->mainNav->admin   = '<i class="icon icon-menu-backend"></i> 後台|admin|index|';

$lang->reporting = new stdclass();
$lang->dividerMenu = ',admin,';

/* Program set menu. */
$lang->program = new stdclass();
$lang->program->menu = new stdclass();
$lang->program->menu->index   = '主頁|program|pgmindex|';
$lang->program->menu->browse  = array('link' => '項目集|program|pgmbrowse|', 'alias' => 'pgmcreate,pgmedit,pgmgroup,pgmmanagepriv,pgmmanageview,pgmmanagemembers');

$lang->program->viewMenu = new stdclass();
$lang->program->viewMenu->view        = array('link' => '概況|program|pgmview|program=%s');
$lang->program->viewMenu->product     = array('link' => '產品|program|pgmproduct|program=%s', 'alias' => 'view');
$lang->program->viewMenu->project     = array('link' => "項目|program|pgmproject|program=%s");
$lang->program->viewMenu->personnel   = array('link' => "人員|personnel|accessible|program=%s");
$lang->program->viewMenu->stakeholder = array('link' => "干係人|program|pgmstakeholder|program=%s", 'alias' => 'createstakeholder');

$lang->personnel = new stdClass();
$lang->personnel->menu = new stdClass();
$lang->personnel->menu->accessible = array('link' => "可訪問人員|personnel|accessible|program=%s");
$lang->personnel->menu->whitelist  = array('link' => "白名單|personnel|whitelist|program=%s", 'alias' => 'addwhitelist');
$lang->personnel->menu->putinto    = array('link' => "投入人員|personnel|putinto|program=%s");

/* Scrum menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->home = '主頁|product|index|';
$lang->product->menu->list = array('link' => $lang->productCommon . '|product|all|', 'alias' => 'create,batchedit');

$lang->product->viewMenu = new stdclass();
if($config->URAndSR) $lang->product->viewMenu->requirement = array('link' => "$URCommon|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->story       = array('link' => "$lang->productSRCommon|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->plan        = array('link' => "計劃|productplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->product->viewMenu->release     = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->viewMenu->roadmap     = '路線圖|product|roadmap|productID=%s';
$lang->product->viewMenu->branch      = '@branch@|branch|manage|productID=%s';
$lang->product->viewMenu->module      = '模組|tree|browse|productID=%s&view=story';
$lang->product->viewMenu->view        = array('link' => '概況|product|view|productID=%s', 'alias' => 'edit');
$lang->product->viewMenu->whitelist   = array('link' => '白名單|product|whitelist|productID=%s', 'alias' => 'addwhitelist');

$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->productplan = new stdclass();

$lang->release->menu     = $lang->product->viewMenu;
$lang->branch->menu      = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->estimate = array('link' => '估算|custom|estimate|');
$lang->system->menu->stage    = array('link' => '階段|stage|browse|', 'subModule' => 'stage');
$lang->system->menu->subject  = array('link' => '科目|subject|browse|');
$lang->system->menu->holiday  = array('link' => '節假日|holiday|browse|');
$lang->system->menu->custom   = array('link' => '自定義|custom|configurewaterfall|');
$lang->system->dividerMenu    = ',auditcl,subject,';

if(isset($_COOKIE['systemModel']) and $_COOKIE['systemModel'] == 'scrum')
{
    $lang->system->menu = new stdclass();
    $lang->system->menu->subject  = array('link' => '科目|subject|browse|');
    $lang->system->menu->holiday  = array('link' => '節假日|holiday|browse|');
    $lang->system->menu->custom   = array('link' => '自定義|custom|configurescrum|');

    $lang->mainNav->system = '<i class="icon icon-menu-users"></i> 組織|subject|browse|';
    unset($lang->system->dividerMenu);
}

$lang->stage = new stdclass();
$lang->stage->menu = new stdclass();
$lang->stage->menu->browse  = array('link' => '階段列表|stage|browse|', 'alias' => 'create,edit,batchcreate');
$lang->stage->menu->settype = '階段類型|stage|settype|';

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();

/* 查詢條中可以選擇的對象列表。*/
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = "{$lang->productSRCommon}";
$lang->searchObjects['task']        = '任務';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['user']        = '用戶';
$lang->searchObjects['build']       = '版本';
$lang->searchObjects['release']     = '發佈';
$lang->searchObjects['productplan'] = $lang->productCommon . $lang->planCommon;
$lang->searchObjects['testtask']    = '測試單';
$lang->searchObjects['doc']         = '文檔';
$lang->searchObjects['caselib']     = '用例庫';
$lang->searchObjects['testreport']  = '測試報告';
$lang->searchObjects['program']     = '項目集';
$lang->searchTips                   = '編號(ctrl+g)';

/* 導入支持的編碼格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 導出檔案的類型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = '全部記錄';
$lang->exportTypeList['selected'] = '選中記錄';

/* 語言 */
$lang->lang = 'Language';

/* 風格列表。*/
$lang->theme                = '主題';
$lang->themes['default']    = '禪道藍（預設）';
$lang->themes['green']      = '葉蘭綠';
$lang->themes['red']        = '赤誠紅';
$lang->themes['purple']     = '玉煙紫';
$lang->themes['pink']       = '芙蕖粉';
$lang->themes['blackberry'] = '露莓黑';
$lang->themes['classic']    = '經典藍';

/* 首頁菜單設置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "瀏覽{$lang->productCommon}|product|browse";
$lang->index->menu->project = "瀏覽{$lang->projectCommon}|project|browse";

/* 我的地盤菜單設置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index            = '首頁|my|index';
$lang->my->menu->calendar         = array('link' => '日程|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->program          = array('link' => '項目|my|program|');
$lang->my->menu->task             = array('link' => '任務|my|task|', 'subModule' => 'task');
$lang->my->menu->bug              = array('link' => 'Bug|my|bug|', 'subModule' => 'bug');
$lang->my->menu->testtask         = array('link' => '測試|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story            = array('link' => "需求|my|story|", 'subModule' => 'story');
$lang->my->menu->myProject        = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic          = '動態|my|dynamic|';

if($config->URAndSR)
{
    $lang->my->menu->requirement = array('link' => "{$URCommon}|my|requirement|", 'subModule' => 'story');
    $lang->my->menu->story       = array('link' => "{$SRCommon}|my|story|", 'subModule' => 'story');
}

$lang->my->dividerMenu = ',program,requirement,dynamic,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* 產品視圖設置。*/
$lang->scrumproduct = new stdclass();
$lang->scrumproduct->menu = new stdclass();

$lang->scrumproduct->menu->story   = array('link' => "{$lang->productSRCommon}|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->scrumproduct->menu->plan    = array('link' => "{$lang->planCommon}|productplan|browse|productID=%s", 'subModule' => 'productplan');
//$lang->scrumproduct->menu->release = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->scrumproduct->menu->roadmap = '路線圖|product|roadmap|productID=%s';
$lang->scrumproduct->menu->project = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->scrumproduct->menu->dynamic = '動態|product|dynamic|productID=%s';
$lang->scrumproduct->menu->doc     = array('link' => '文檔|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->scrumproduct->menu->branch  = '@branch@|branch|manage|productID=%s';
$lang->scrumproduct->menu->module  = '模組|tree|browse|productID=%s&view=story';
$lang->scrumproduct->menu->view    = array('link' => '概況|product|view|productID=%s', 'alias' => 'edit');

if($config->URAndSR)
{
    $lang->scrumproduct->menu->requirement = array('link' => "{$URCommon}|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
    $lang->scrumproduct->menu->story       = array('link' => "{$SRCommon}|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
}

$lang->product->dividerMenu = ',project,doc,';

$lang->story = new stdclass();

$lang->story->menu = $lang->product->menu;

/* 項目視圖菜單設置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task      = array('link' => '任務|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban    = array('link' => '看板|project|kanban|projectID=%s');
$lang->project->menu->burn      = array('link' => '燃盡圖|project|burn|projectID=%s');
$lang->project->menu->list      = array('link' => '更多|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story     = array('link' => "{$lang->productSRCommon}|project|story|projectID=%s", 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->qa        = array('link' => '測試|project|bug|projectID=%s', 'subModule' => 'bug,build,testtask', 'alias' => 'build,testtask', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->doc       = array('link' => '文檔|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->action    = array('link' => '動態|project|dynamic|projectID=%s', 'subModule' => 'dynamic', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->product   = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team      = array('link' => '團隊|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->view      = array('link' => '概況|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');
$lang->project->menu->whitelist = array('link' => '白名單|project|whitelist|projectID=%s', 'alias' => 'addwhitelist', 'subModule' => 'personnel');

$lang->project->subMenu = new stdclass();
$lang->project->subMenu->list = new stdclass();
$lang->project->subMenu->list->groupTask = '分組視圖|project|groupTask|projectID=%s';
$lang->project->subMenu->list->tree      = '樹狀圖|project|tree|projectID=%s';

$lang->project->subMenu->qa = new stdclass();
$lang->project->subMenu->qa->bug      = 'Bug|project|bug|projectID=%s';
$lang->project->subMenu->qa->build    = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->subMenu->qa->testtask = array('link' => '測試單|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');

$lang->project->dividerMenu = ',story,team,product,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA視圖菜單設置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->qa->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->qa->menu->report    = array('link' => '報告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->qa->menu->caselib   = array('link' => '用例庫|caselib|browse', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => '功能測試|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => '單元測試|testtask|browseUnits|productID=%s');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testcase->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testcase->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s', 'subModule' => 'testtask', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testtask->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testtask->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testreport->menu->report    = array('link' => '報告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => '用例|testcase|browse|', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask  = array('link' => '測試單|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => '套件|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => '報告|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => '用例庫|caselib|browse|libID=%s', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');

$lang->caselib->subMenu = new stdclass();
$lang->caselib->subMenu->testcase = new stdclass();
$lang->caselib->subMenu->testcase->feature = array('link' => '功能測試|testcase|browse|', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->caselib->subMenu->testcase->unit    = array('link' => '單元測試|testtask|browseUnits|');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => '代碼|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => '構建|job|browse', 'subModule' => 'compile,job');
$lang->ci->menu->jenkins  = array('link' => 'Jenkins|jenkins|browse', 'alias' => 'create,edit');
$lang->ci->menu->maintain = array('link' => '版本庫|repo|maintain', 'alias' => 'create,edit');
$lang->ci->menu->rules    = array('link' => '指令|repo|setrules');

$lang->repo          = new stdclass();
$lang->jenkins       = new stdclass();
$lang->compile       = new stdclass();
$lang->job           = new stdclass();
$lang->repo->menu    = $lang->ci->menu;
$lang->jenkins->menu = $lang->ci->menu;
$lang->compile->menu = $lang->ci->menu;
$lang->job->menu     = $lang->ci->menu;

/* 文檔視圖菜單設置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->svn = new stdclass();
$lang->git = new stdclass();

/* 項目發布視圖菜單設置。*/
$lang->projectrelease = new stdclass();
$lang->projectrelease->menu = new stdclass();

/* 統計視圖菜單設置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual  = array('link' => '年度總結|report|annualData', 'target' => '_blank');
$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => '測試|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => '組織|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = '註：統計報表的數據來源於列表頁面的檢索結果，生成統計報表前請先在列表頁面進行檢索。比如列表頁面我們檢索的是%tab%，那麼報表就是基于之前檢索的%tab%的結果集進行統計。';

/* 組織結構視圖菜單設置。*/
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
$lang->company->menu->browseUser  = array('link' => '用戶|company|browse', 'subModule' => ',user,');
$lang->company->menu->dept        = array('link' => '部門|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '權限|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic     = '動態|company|dynamic|';
$lang->company->menu->view        = array('link' => '公司|company|view');

/* 後台管理菜單設置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index   = array('link' => '首頁|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->company = array('link' => '人員|company|browse|', 'subModule' => ',user,dept,group,', 'alias' => ',dynamic,view,');
$lang->admin->menu->message = array('link' => '通知|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->data    = array('link' => '數據|backup|index', 'subModule' => 'backup,action');
$lang->admin->menu->safe    = array('link' => '安全|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->system  = array('link' => '系統|cron|index', 'subModule' => 'cron,search');

$lang->company->menu = $lang->company->menu;
$lang->dept->menu    = $lang->company->menu;
$lang->group->menu   = $lang->company->menu;
$lang->user->menu    = $lang->company->menu;

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail    = array('link' => '郵件|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => '瀏覽器|message|browser');
$lang->admin->subMenu->message->setting = array('link' => '設置|message|setting');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = 'ZDOO|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api    = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db     = array('link' => '資料庫|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => '編輯器|dev|editor');
$lang->admin->subMenu->dev->entry  = array('link' => '應用|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->data = new stdclass();
$lang->admin->subMenu->data->backup = array('link' => '備份|backup|index', 'subModule' => 'backup');
$lang->admin->subMenu->data->trash  = '資源回收筒|action|trash';

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->cron     = array('link' => '定時|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone = array('link' => '時區|custom|timezone', 'subModule' => 'custom');

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

/* 菜單分組。*/
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
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->case        = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->testsuite   = 'qa';
$lang->menugroup->caselib     = 'qa';
$lang->menugroup->testreport  = 'qa';
$lang->menugroup->report      = 'reporting';
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
$lang->navGroup->todo   = 'my';
$lang->navGroup->effort = 'my';

$lang->navGroup->personnel = 'program';

$lang->navGroup->productplan = 'product';
$lang->navGroup->release     = 'product';
$lang->navGroup->branch      = 'product';
$lang->navGroup->story       = 'product';

$lang->navGroup->project     = 'project';
$lang->navGroup->tree        = 'project';
$lang->navGroup->task        = 'project';
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

$lang->navGroup->programplan    = 'project';
$lang->navGroup->workestimation = 'project';
$lang->navGroup->budget         = 'project';
$lang->navGroup->review         = 'project';
$lang->navGroup->reviewissue    = 'project';
$lang->navGroup->weekly         = 'project';
$lang->navGroup->milestone      = 'project';
$lang->navGroup->pssp           = 'project';
$lang->navGroup->design         = 'project';
$lang->navGroup->repo           = 'project';
$lang->navGroup->issue          = 'project';
$lang->navGroup->risk           = 'project';
$lang->navGroup->auditplan      = 'project';
$lang->navGroup->cm             = 'project';
$lang->navGroup->nc             = 'project';
$lang->navGroup->job            = 'project';
$lang->navGroup->jenkins        = 'project';
$lang->navGroup->compile        = 'project';
$lang->navGroup->build          = 'project';
$lang->navGroup->projectrelease = 'project';

$lang->navGroup->durationestimation = 'project';

$lang->navGroup->stage         = 'system';
$lang->navGroup->measurement   = 'system';
$lang->navGroup->report        = 'system';
$lang->navGroup->sqlbuilder    = 'system';
$lang->navGroup->auditcl       = 'system';
$lang->navGroup->cmcl          = 'system';
$lang->navGroup->process       = 'system';
$lang->navGroup->activity      = 'system';
$lang->navGroup->zoutput       = 'system';
$lang->navGroup->classify      = 'system';
$lang->navGroup->subject       = 'system';
$lang->navGroup->baseline      = 'system';
$lang->navGroup->reviewcl      = 'system';
$lang->navGroup->reviewsetting = 'system';
$lang->navGroup->holiday       = 'system';

$lang->navGroup->attend   = 'attend';
$lang->navGroup->leave    = 'attend';
$lang->navGroup->makeup   = 'attend';
$lang->navGroup->overtime = 'attend';
$lang->navGroup->lieu     = 'attend';

$lang->navGroup->admin     = 'admin';
$lang->navGroup->company   = 'admin';
$lang->navGroup->dept      = 'admin';
$lang->navGroup->ldap      = 'admin';
$lang->navGroup->group     = 'admin';
$lang->navGroup->webhook   = 'admin';
$lang->navGroup->sms       = 'admin';
$lang->navGroup->message   = 'admin';
$lang->navGroup->user      = 'admin';
$lang->navGroup->custom    = 'admin';
$lang->navGroup->cron      = 'admin';
$lang->navGroup->backup    = 'admin';
$lang->navGroup->mail      = 'admin';
$lang->navGroup->dev       = 'admin';
$lang->navGroup->extension = 'admin';
$lang->navGroup->action    = 'admin';
$lang->navGroup->search    = 'admin';

/* 錯誤提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = "您訪問的域名 %s 沒有對應的公司。";
$lang->error->length          = array("『%s』長度錯誤，應當為『%s』", "『%s』長度應當不超過『%s』，且大於『%s』。");
$lang->error->reg             = "『%s』不符合格式，應當為:『%s』。";
$lang->error->unique          = "『%s』已經有『%s』這條記錄了。如果您確定該記錄已刪除，請到後台-數據-資源回收筒還原。";
$lang->error->gt              = "『%s』應當大於『%s』。";
$lang->error->ge              = "『%s』應當不小於『%s』。";
$lang->error->notempty        = "『%s』不能為空。";
$lang->error->empty           = "『%s』必須為空。";
$lang->error->equal           = "『%s』必須為『%s』。";
$lang->error->int             = array("『%s』應當是數字。", "『%s』應當介於『%s-%s』之間。");
$lang->error->float           = "『%s』應當是數字，可以是小數。";
$lang->error->email           = "『%s』應當為合法的EMAIL。";
$lang->error->URL             = "『%s』應當為合法的URL。";
$lang->error->date            = "『%s』應當為合法的日期。";
$lang->error->datetime        = "『%s』應當為合法的日期。";
$lang->error->code            = "『%s』應當為字母或數字的組合。";
$lang->error->account         = "『%s』只能是字母和數字的組合三位以上。";
$lang->error->passwordsame    = "兩次密碼應該相同。";
$lang->error->passwordrule    = "密碼應該符合規則，長度至少為六位。";
$lang->error->accessDenied    = '您沒有訪問權限';
$lang->error->pasteImg        = '您的瀏覽器不支持粘貼圖片！';
$lang->error->noData          = '沒有數據';
$lang->error->editedByOther   = '該記錄可能已經被改動。請刷新頁面重新編輯！';
$lang->error->tutorialData    = '新手模式下不會插入數據，請退出新手模式操作';
$lang->error->noCurlExt       = '伺服器未安裝Curl模組。';

/* 分頁信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord     = "暫時沒有記錄";
$lang->pager->digest       = "共 <strong>%s</strong> 條記錄，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = "每頁 <strong>%s</strong> 條";
$lang->pager->first        = "<i class='icon-step-backward' title='首頁'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='上一頁'></i>";
$lang->pager->next         = "<i class='icon-play' title='下一頁'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='末頁'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "上一頁";
$lang->pager->nextPage     = "下一頁";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 項，共 <strong>%s</strong> 項";
$lang->pager->pageOfText   = '第 {0} 頁';
$lang->pager->firstPage    = '第一頁';
$lang->pager->lastPage     = '最後一頁';
$lang->pager->goto         = '跳轉';
$lang->pager->pageOf       = '第 <strong>{page}</strong> 頁';
$lang->pager->totalPage    = '共 <strong>{totalPage}</strong> 頁';
$lang->pager->totalCount   = '共 <strong>{recTotal}</strong> 項';
$lang->pager->pageSize     = '每頁 <strong>{recPerPage}</strong> 項';
$lang->pager->itemsRange   = '第 <strong>{start}</strong> ~ <strong>{end}</strong> 項';
$lang->pager->pageOfTotal  = '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 頁';

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = '不是有效的顏色值';

$lang->proVersion     = "<a href='https://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>專業版 <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "下載桌面提醒";
$lang->downloadClient = "下載客戶端";
$lang->clientHelp     = "客戶端使用說明";
$lang->clientHelpLink = "http://www.zentao.net/book/zentaopmshelp/302.html#2";
$lang->website        = "https://www.zentao.net";

$lang->suhosinInfo     = "警告：數據太多，請在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（大於%s的數）。 保存並重新啟動apache或php-fpm，否則會造成部分數據無法保存。";
$lang->maxVarsInfo     = "警告：數據太多，請在php.ini中修改<font color=red>max_input_vars</font>（大於%s的數）。 保存並重新啟動apache或php-fpm，否則會造成部分數據無法保存。";
$lang->pasteTextInfo   = "粘貼文本到文本域中，每行文字作為一條數據的標題。";
$lang->noticeImport    = "導入數據中，含有已經存在系統的數據，請確認這些數據要覆蓋或者全新插入。";
$lang->importConfirm   = "導入確認";
$lang->importAndCover  = "覆蓋";
$lang->importAndInsert = "全新插入";

$lang->noResultsMatch    = "沒有匹配結果";
$lang->searchMore        = "搜索此關鍵字的更多結果：";
$lang->chooseUsersToMail = "選擇要發信通知的用戶...";
$lang->noticePasteImg    = "可以在編輯器直接貼圖。";
$lang->pasteImgFail      = "貼圖失敗，請稍後重試。";
$lang->pasteImgUploading = "正在上傳圖片，請稍後...";

/* 時間格式設置。*/
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

/* datepicker 時間*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本週';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '關閉';
$lang->datepicker->dpText->TEXT_DATE        = '選擇時間段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '選擇日期';

$lang->datepicker->dayNames     = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

/* Common action icons 通用動作表徵圖 */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'cube';
$lang->icons['bug']       = 'bug';
$lang->icons['task']      = 'check-sign';
$lang->icons['tasks']     = 'tasks';
$lang->icons['project']   = 'stack';
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
$lang->icons['run']                = 'play';
$lang->icons['runCase']            = 'play';
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
$lang->menu->scrum->program        = '儀表盤|program|index|';
//$lang->menu->scrum->product      = $lang->productCommon . '|product|index|locate=no';
$lang->menu->scrum->project        = "$lang->projectCommon|project|index|locate=no";
$lang->menu->scrum->doc            = '文檔|doc|index|';
$lang->menu->scrum->qa             = '測試|qa|index';
$lang->menu->scrum->projectrelease = array('link' => '發布|projectrelease|browse|product={PRODUCT}');
$lang->menu->scrum->stakeholder    = '干係人|stakeholder|browse';

/* Waterfall menu. */
$lang->menu->waterfall = new stdclass();
$lang->menu->waterfall->programindex   = array('link' => '儀表盤|program|index|program={PROGRAM}');
$lang->menu->waterfall->programplan    = array('link' => '計劃|programplan|browse|program={PROGRAM}', 'subModule' => 'programplan');
$lang->menu->waterfall->project        = array('link' => $lang->projectCommon . '|project|task|projectID={PROJECT}', 'subModule' => ',project,task,');
$lang->menu->waterfall->weekly         = array('link' => '報告|weekly|index|program={PROGRAM}', 'subModule' => ',milestone,');
$lang->menu->waterfall->doc            = array('link' => '文檔|doc|index|program={PROGRAM}');
//$lang->menu->waterfall->product        = array('link' => '需求|product|browse|product={PRODUCT}', 'subModule' => ',story,');
$lang->menu->waterfall->design         = '設計|design|browse|product={PRODUCT}';
$lang->menu->waterfall->ci             = '代碼|repo|browse|';
$lang->menu->waterfall->qa             = array('link' => '測試|bug|browse|product={PRODUCT}', 'subModule' => ',testcase,testtask,testsuite,testreport,caselib,');
$lang->menu->waterfall->projectrelease = array('link' => '發布|projectrelease|browse|product={PRODUCT}');
$lang->menu->waterfall->issue          = '問題|issue|browse|';
$lang->menu->waterfall->risk           = '風險|risk|browse|';
$lang->menu->waterfall->list           = array('link' => '更多|workestimation|index|program={PROGRAM}', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'stakeholder,workestimation,durationestimation,budget,pssp,stakeholder');

$lang->waterfall = new stdclass();
$lang->waterfall->subMenu = new stdclass();
$lang->waterfall->subMenu->list = new stdclass();
$lang->waterfall->subMenu->list->workestimation = array('link' => '估算|workestimation|index|program=%s', 'subModule' => 'durationestimation,budget');
$lang->waterfall->subMenu->list->stakeholder    = array('link' => '干係人|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->waterfall->subMenu->list->program        = '項目|program|edit|';

$lang->waterfallproduct   = new stdclass();
$lang->workestimation     = new stdclass();
$lang->budget             = new stdclass();
$lang->programplan        = new stdclass();
$lang->review             = new stdclass();
$lang->weekly             = new stdclass();
$lang->milestone          = new stdclass();
$lang->design             = new stdclass();
$lang->auditplan          = new stdclass();
$lang->cm                 = new stdclass();
$lang->nc                 = new stdclass();
$lang->pssp               = new stdclass();
$lang->issue              = new stdclass();
$lang->risk               = new stdclass();
$lang->stakeholder        = new stdclass();
$lang->durationestimation = new stdclass();

$lang->workestimation->menu     = new stdclass();
$lang->budget->menu             = new stdclass();
$lang->programplan->menu        = new stdclass();
$lang->review->menu             = new stdclass();
$lang->weekly->menu             = new stdclass();
$lang->milestone->menu          = new stdclass();
$lang->design->menu             = new stdclass();
$lang->auditplan->menu          = new stdclass();
$lang->cm->menu                 = new stdclass();
$lang->pssp->menu               = new stdclass();
$lang->issue->menu              = new stdclass();
$lang->risk->menu               = new stdclass();
$lang->stakeholder->menu        = new stdclass();
$lang->waterfallproduct->menu   = new stdclass();
$lang->durationestimation->menu = new stdclass();

$lang->stakeholder->menu->list  = array('link' => '干係人列表|stakeholder|browse|', 'alias' => 'create,edit,view,batchcreate');
$lang->stakeholder->menu->issue = array('link' => '問題管理|stakeholder|issue|');

$lang->workestimation->menu->index    = '工作量估算|workestimation|index|program={PROGRAM}';
$lang->workestimation->menu->duration = array('link' => '工期估算|durationestimation|index|program={PROGRAM}', 'subModule' => 'durationestimation');
$lang->workestimation->menu->budget   = array('link' => '費用估算|budget|summary|', 'subModule' => 'budget');

$lang->durationestimation->menu = $lang->workestimation->menu;
$lang->budget->menu = $lang->workestimation->menu;

$lang->programplan->menu->gantt = array('link' => '甘特圖|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=gantt');
$lang->programplan->menu->lists = array('link' => '階段列表|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=lists', 'alias' => 'create');

$lang->waterfallproduct->menu->plan  = array('link' => "{$lang->planCommon}|productplan|browse|productID={PRODUCT}", 'subModule' => 'productplan');
$lang->waterfallproduct->menu->story = '需求|product|browse|product={PRODUCT}';
$lang->waterfallproduct->menu->track = '跟蹤矩陣|story|track|product={PRODUCT}';

if($config->URAndSR)
{
    $lang->waterfallproduct->menu->requirement = array('link' => "{$URCommon}|product|browse|productID={PRODUCT}&branch=&browseType=unclosed&param=0&storyType=requirement");
    $lang->waterfallproduct->menu->story       = array('link' => "{$SRCommon}|product|browse|productID={PRODUCT}");
}

$lang->nc->menu = $lang->auditplan->menu;
$lang->noMenuModule = array('my', 'todo', 'effort', 'program', 'product', 'productplan', 'story', 'branch', 'release', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'holiday', 'custom', 'auditcl', 'subject', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'issue', 'risk', 'pssp', 'sms', 'message', 'webhook', 'search');

include (dirname(__FILE__) . '/menuOrder.php');
