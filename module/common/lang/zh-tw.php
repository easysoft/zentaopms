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

$lang->zentaoPMS        = '禪道';
$lang->logoImg          = 'zt-logo.png';
$lang->welcome          = "%s項目管理系統";
$lang->logout           = '退出';
$lang->login            = '登錄';
$lang->help             = '幫助';
$lang->aboutZenTao      = '關於禪道';
$lang->profile          = '個人檔案';
$lang->changePassword   = '修改密碼';
$lang->unfoldMenu       = '展開導航';
$lang->collapseMenu     = '收起導航';
$lang->preference       = '個性化設置';
$lang->runInfo          = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間: %s 毫秒, 內存: %s KB, 查詢: %s.  </div></div>";
$lang->agreement        = "已閲讀並同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授權協議1.2》</a>。<span class='text-danger'>未經許可，不得去除、隱藏或遮掩禪道軟件的任何標誌及連結。</span>";
$lang->designedByAIUX   = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> 艾體驗設計</a>";

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
$lang->moreLink     = 'More';
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

$lang->sprintCommon = $lang->iterationCommon . '/階段';

$lang->execution = new stdclass();
$lang->executionCommon = '執行';
$lang->execution->sprint = '迭代';
$lang->execution->stage  = '階段';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';

$lang->common = new stdclass();
$lang->common->common = '公有模組';

global $config;
list($programModule, $programMethod) = explode('-', $config->programLink);
list($productModule, $productMethod) = explode('-', $config->productLink);
list($projectModule, $projectMethod) = explode('-', $config->projectLink);

/* 主導航菜單。*/
$lang->mainNav = new stdclass();
$lang->mainNav->my      = '<i class="icon icon-menu-my"></i> 地盤|my|index|';
$lang->mainNav->product = "<i class='icon icon-product'></i> 產品|$productModule|$productMethod|";
if($config->systemMode == 'new')
{
    $lang->mainNav->project   = "<i class='icon icon-project'></i> 項目|$projectModule|$projectMethod|";
    $lang->mainNav->execution = "<i class='icon icon-run'></i> 執行|execution|task|";
}
else
{
    $lang->mainNav->project = "<i class='icon icon-project'></i> $lang->executionCommon|$projectModule|$projectMethod|";
}
$lang->mainNav->qa      = '<i class="icon icon-test"></i> 測試|qa|index|';
$lang->mainNav->repo    = '<i class="icon icon-code1"></i> 代碼|repo|browse|';
$lang->mainNav->doc     = '<i class="icon icon-doc"></i> 文檔|doc|index|';
$lang->mainNav->report  = "<i class='icon icon-statistic'></i> 統計|report|productSummary|";
$lang->mainNav->system  = '<i class="icon icon-group"></i> 組織|custom|browsestoryconcept|';
$lang->mainNav->admin   = '<i class="icon icon-cog-outline"></i> 後台|admin|index|';
if($config->systemMode == 'new') $lang->mainNav->program = "<i class='icon icon-program'></i> 項目集|$programModule|$programMethod|";

$lang->dividerMenu = ',qa,report,admin,';

/* Program set menu. */
$lang->program = new stdclass();
$lang->program->menu = new stdclass();
//$lang->program->menu->index   = '儀錶盤|program|index|';
$lang->program->menu->browse  = array('link' => '項目集|program|browse|');

$lang->project = new stdclass();
$lang->project->menu = new stdclass();
if($config->systemMode == 'new')
{
    $lang->project->menu->browse = array('link' => '項目|project|browse|');
}
else
{
    $lang->project->menu->browse = array('link' => "$lang->executionCommon|project|browse|");
}

$lang->program->viewMenu = new stdclass();
$lang->program->viewMenu->product     = array('link' => '產品|program|product|program=%s', 'alias' => 'view');
$lang->program->viewMenu->project     = array('link' => "項目|program|project|program=%s");
$lang->program->viewMenu->personnel   = array('link' => "人員|personnel|accessible|program=%s");
$lang->program->viewMenu->stakeholder = array('link' => "干係人|program|stakeholder|program=%s", 'alias' => 'createstakeholder');

$lang->personnel = new stdClass();
$lang->personnel->menu = new stdClass();
$lang->personnel->menu->accessible = array('link' => "可訪問人員|personnel|accessible|program=%s");
$lang->personnel->menu->whitelist  = array('link' => "白名單|personnel|whitelist|program=%s", 'alias' => 'addwhitelist');
$lang->personnel->menu->putinto    = array('link' => "投入人員|personnel|putinto|program=%s");

/* Scrum menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->home = '儀錶盤|product|index|';
$lang->product->menu->list = array('link' => $lang->productCommon . '|product|all|', 'alias' => 'create,batchedit,manageline');

$lang->product->viewMenu = new stdclass();
$lang->product->viewMenu->dashboard   = array('link' => '儀表盤|product|dashboard|productID=%s');
if($config->URAndSR) $lang->product->viewMenu->requirement = array('link' => "$lang->URCommon|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->story       = array('link' => "$lang->SRCommon|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->plan        = array('link' => "計劃|productplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->product->viewMenu->release     = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->viewMenu->roadmap     = '路線圖|product|roadmap|productID=%s';
$lang->product->viewMenu->project     = "項目|product|project|status=all&productID=%s";
$lang->product->viewMenu->track       = array('link' => "矩陣|story|track|productID=%s");
$lang->product->viewMenu->doc         = array('link' => '文檔|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->viewMenu->dynamic     = '動態|product|dynamic|productID=%s';
$lang->product->viewMenu->set         = array('link' => '設置|product|view|productID=%s', 'subModule' => 'tree,branch', 'alias' => 'edit');

$lang->product->setMenu = new stdclass();
$lang->product->setMenu->view      = array('link' => '概況|product|view|productID={PRODUCT}', 'alias' => 'edit');
$lang->product->setMenu->module    = array('link' => '模組|tree|browse|product={PRODUCT}&view=story', 'subModule' => 'tree');
$lang->product->setMenu->branch    = array('link' => '@branch@|branch|manage|product={PRODUCT}', 'subModule' => 'branch');
$lang->product->setMenu->whitelist = array('link' => '白名單|product|whitelist|product={PRODUCT}', 'subModule' => 'personnel');

$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->productplan = new stdclass();

$lang->release->menu     = $lang->product->viewMenu;
$lang->branch->menu      = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->company   = array('link' => '全局設置|custom|browsestoryconcept|', 'subModule' => 'holiday');

$lang->subject = new stdclass();
$lang->subject->menu = new stdclass();
$lang->subject->menu->storyConcept = array('link' => '需求概念|custom|browsestoryconcept|');

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();

$lang->searchTips = '';
$lang->searchAB   = '搜索';

/* 查詢中可以選擇的對象列表。*/
$lang->searchObjects['all']         = '全部';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = '需求';
$lang->searchObjects['task']        = '任務';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = '版本';
$lang->searchObjects['release']     = '發佈';
$lang->searchObjects['productplan'] = $lang->productCommon . '計劃';
$lang->searchObjects['testtask']    = '測試單';
$lang->searchObjects['doc']         = '文檔';
$lang->searchObjects['caselib']     = '用例庫';
$lang->searchObjects['testreport']  = '測試報告';
$lang->searchObjects['program']     = '項目集';
$lang->searchObjects['project']     = '項目';
$lang->searchObjects['execution']   = $lang->executionCommon;
$lang->searchObjects['user']        = '用戶';
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
$lang->index->menu->project = "瀏覽{$lang->executionCommon}|project|browse";

/* 我的地盤菜單設置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index       = '首頁|my|index';
$lang->my->menu->calendar    = array('link' => '日程|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->myWork      = array('link' => '待處理|my|work|mode=task');
if($config->systemMode == 'new')
{
    $lang->my->menu->myProject   = array('link' => '項目|my|project|');
    $lang->my->menu->myExecution = '執行|my|execution|type=undone';
}
else
{
    $lang->my->menu->myExecution = $lang->executionCommon . '|my|execution|type=undone';
}
$lang->my->menu->contribute  = array('link' => '貢獻|my|contribute|mode=task');
$lang->my->menu->dynamic     = '動態|my|dynamic|';
$lang->my->menu->score       = array('link' => '積分|my|score|', 'subModule' => 'score');
$lang->my->menu->team        = array('link' => '團隊|my|team|', 'subModule' => 'user');
$lang->my->menu->contacts    = '聯繫人|my|managecontacts|';

$lang->my->workMenu = new stdclass();
$lang->my->workMenu->task        = '任務|my|work|mode=task';
if($config->URAndSR) $lang->my->workMenu->requirement = "$lang->URCommon|my|work|mode=requirement";
$lang->my->workMenu->story       = "$lang->SRCommon|my|work|mode=story";
$lang->my->workMenu->bug         = 'Bug|my|work|mode=bug';
$lang->my->workMenu->testcase    = '用例|my|work|mode=testcase&type=assigntome';
$lang->my->workMenu->testtask    = '測試單|my|work|mode=testtask&type=wait';

$lang->my->contributeMenu = new stdclass();
$lang->my->contributeMenu->task        = '任務|my|contribute|mode=task';
if($config->URAndSR) $lang->my->contributeMenu->requirement = "$lang->URCommon|my|contribute|mode=requirement";
$lang->my->contributeMenu->story       = "$lang->SRCommon|my|contribute|mode=story";
$lang->my->contributeMenu->bug         = 'Bug|my|contribute|mode=bug';
$lang->my->contributeMenu->testcase    = '用例|my|contribute|mode=testcase&type=openedbyme';
$lang->my->contributeMenu->testtask    = '測試單|my|contribute|mode=testtask&type=done';

$lang->my->dividerMenu = ',myProject,team,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->product->dividerMenu = ',requirement,set,';

$lang->story = new stdclass();

$lang->story->menu = $lang->product->menu;

/* 項目視圖菜單設置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task     = array('link' => '任務|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban   = array('link' => '看板|project|kanban|projectID=%s');
$lang->project->menu->burn     = array('link' => '燃盡圖|project|burn|projectID=%s');
$lang->project->menu->view     = array('link' => '視圖|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story    = array('link' => "{$lang->SRCommon}|project|story|projectID=%s", 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->bug      = array('link' => 'Bug|project|bug|projectID=%s');
$lang->project->menu->build    = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask = array('link' => '測試單|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');
$lang->project->menu->doc      = array('link' => '文檔|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->product  = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team     = array('link' => '團隊|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->more     = array('link' => '更多|project|whitelist|projectID=%s', 'subModule' => 'personnel', 'alias' => 'edit', 'class' => 'dropdown dropdown-hover');

$lang->project->subMenu = new stdclass();
$lang->project->subMenu->view = new stdclass();
$lang->project->subMenu->view->groupTask = '分組視圖|project|grouptask|projectID=%s';
$lang->project->subMenu->view->tree      = '樹狀圖|project|tree|projectID=%s';

$lang->project->subMenu->qa = new stdclass();
$lang->project->subMenu->qa->bug      = 'Bug|project|bug|projectID=%s';
$lang->project->subMenu->qa->build    = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->subMenu->qa->testtask = array('link' => '測試單|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');

$lang->project->subMenu->more = new stdclass();
$lang->project->subMenu->more->whitelist = array('link' => '白名單|project|whitelist|projectID=%s', 'subModule' => 'personnel', 'alias' => 'addwhitelist');
$lang->project->subMenu->more->action    = array('link' => '動態|project|dynamic|projectID=%s');
$lang->project->subMenu->more->view      = array('link' => '概況|project|view|projectID=%s', 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');

$lang->project->dividerMenu = ',project,programplan,projectbuild,story,doc,other,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA視圖菜單設置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->index     = array('link' => '儀表盤|qa|index');
$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report,importunitresult');
$lang->qa->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->qa->menu->report    = array('link' => '報告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->qa->menu->caselib   = array('link' => '用例庫|caselib|browse', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => '功能測試|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => '單元測試|testtask|browseUnits|productID=%s', 'alias' => 'browseunits');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->index     = array('link' => '儀表盤|qa|index');
$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->index     = array('link' => '儀表盤|qa|index');
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testcase->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testcase->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->index     = array('link' => '儀表盤|qa|index');
$lang->testtask->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s', 'subModule' => 'testtask', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testtask->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testtask->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->index     = array('link' => '儀表盤|qa|index');
$lang->testsuite->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report    = array('link' => '報告|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->index     = array('link' => '儀表盤|qa|index');
$lang->testreport->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase  = array('link' => '用例|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask  = array('link' => '測試單|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => '套件|testsuite|browse|productID=%s');
$lang->testreport->menu->report    = array('link' => '報告|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib   = array('link' => '用例庫|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->index     = array('link' => '儀表盤|qa|index');
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

/* 發佈視圖菜單設置。*/
$lang->projectrelease = new stdclass();
$lang->projectrelease->menu = new stdclass();

/* 統計視圖菜單設置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual    = array('link' => '年度總結|report|annualData|year=&dept=&userID=' . (isset($_SESSION['user']) ? zget($_SESSION['user'], 'id', 0) : 0), 'target' => '_blank');
$lang->report->menu->product   = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->execution = array('link' => $lang->executionCommon . '|report|executiondeviation');
$lang->report->menu->test      = array('link' => '測試|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff     = array('link' => '組織|report|workload');

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
$lang->admin->menu->custom  = array('link' => '自定義|custom|index', 'subModule' => 'custom');
$lang->admin->menu->system  = array('link' => '系統|backup|index', 'subModule' => 'cron,admin,backup,action');

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

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->data       = array('link' => '數據|backup|index', 'subModule' => 'action');
$lang->admin->subMenu->system->safe       = array('link' => '安全|admin|safe', 'alias' => 'checkweak');
$lang->admin->subMenu->system->cron       = array('link' => '定時|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone   = array('link' => '時區|custom|timezone', 'subModule' => 'custom');
$lang->admin->subMenu->system->buildIndex = array('link' => '重建索引|search|buildindex|');

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

$lang->navGroup->projectstory   = 'project';
$lang->navGroup->review         = 'project';
$lang->navGroup->reviewissue    = 'project';
$lang->navGroup->milestone      = 'project';
$lang->navGroup->pssp           = 'project';
$lang->navGroup->auditplan      = 'project';
$lang->navGroup->cm             = 'project';
$lang->navGroup->nc             = 'project';
$lang->navGroup->build          = 'project';
$lang->navGroup->projectrelease = 'project';
$lang->navGroup->projectbuild   = 'project';
$lang->navGroup->repo           = 'project';
$lang->navGroup->job            = 'project';
$lang->navGroup->jenkins        = 'project';
$lang->navGroup->compile        = 'project';
$lang->navGroup->report         = 'project';
$lang->navGroup->measrecord     = 'project';

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
$lang->error->account         = "『%s』只能是字母、數字或下劃線的組合三位以上。";
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

$lang->downNotify     = "下載桌面提醒";
$lang->clientName     = "客戶端";
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
if(!defined('LONG_TIME'))     define('LONG_TIME',    '2059-12-31');

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
$lang->menu->scrum->index          = '儀表盤|program|index|project={PROJECT}';
$lang->menu->scrum->project        = "$lang->executionCommon|project|index|locate=no";
$lang->menu->scrum->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story', 'alias' => 'story,track');
$lang->menu->scrum->doc            = '文檔|doc|index|';
$lang->menu->scrum->qa             = '測試|qa|index';
$lang->menu->scrum->ci             = '代碼|repo|browse';
$lang->menu->scrum->projectbuild   = array('link' => '版本|projectbuild|browse|project={PROJECT}');
$lang->menu->scrum->projectrelease = array('link' => '發佈|projectrelease|browse');
$lang->menu->scrum->other          = array('link' => '其他|project|other', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'issue,risk,stakeholder');
$lang->menu->scrum->projectsetting = array('link' => '設置|project|view|project={PROJECT}', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->scrum = new stdclass();
$lang->scrum->subMenu = new stdclass();
$lang->scrum->subMenu->other = new stdclass();
$lang->scrum->subMenu->other->stakeholder = array('link' => '干係人|stakeholder|browse|', 'subModule' => 'stakeholder');

$lang->scrum->setMenu = new stdclass();
$lang->scrum->setMenu->view      = array('link' => '概況|project|view|project={PROJECT}', 'alias' => 'edit');
$lang->scrum->setMenu->products  = array('link' => '產品|project|manageProducts|project={PROJECT}', 'alias' => 'manageproducts');
$lang->scrum->setMenu->group     = array('link' => '權限|project|group|project={PROJECT}', 'alias' => 'group,manageview,managepriv');
$lang->scrum->setMenu->members   = array('link' => '團隊|project|manageMembers|project={PROJECT}', 'alias' => 'managemembers');
$lang->scrum->setMenu->whitelist = array('link' => '白名單|project|whitelist|project={PROJECT}', 'subModule' => 'personnel');

/* Waterfall menu. */
$lang->menu->waterfall = new stdclass();
$lang->menu->waterfall->index          = array('link' => '儀表盤|project|index|project={PROJECT}');
$lang->menu->waterfall->programplan    = array('link' => '計劃|programplan|browse|project={PROJECT}', 'subModule' => 'programplan');
$lang->menu->waterfall->project        = array('link' => $lang->executionCommon . '|project|task|executionID={EXECUTION}', 'subModule' => ',project,task,');
$lang->menu->waterfall->doc            = array('link' => '文檔|doc|index|project={PROJECT}');
$lang->menu->waterfall->weekly         = array('link' => '報告|weekly|index|project={PROJECT}', 'subModule' => ',milestone,');
$lang->menu->waterfall->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story');
$lang->menu->waterfall->design         = '設計|design|browse|product={PRODUCT}';
$lang->menu->waterfall->ci             = '代碼|repo|browse|';
$lang->menu->waterfall->track          = array('link' => '矩陣|projectstory|track', 'alias' => 'track');
$lang->menu->waterfall->qa             = '測試|qa|index';
$lang->menu->waterfall->projectrelease = array('link' => '發佈|projectrelease|browse');
$lang->menu->waterfall->projectbuild   = array('link' => '版本|projectbuild|browse|project={PROJECT}');
$lang->menu->waterfall->other          = array('link' => '其他|project|other', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'issue,risk,stakeholder,nc,workestimation,durationestimation,budget,pssp,measrecord,report');
$lang->menu->waterfall->projectsetting = array('link' => '設置|project|view|project={PROJECT}', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->waterfall = new stdclass();
$lang->waterfall->subMenu = new stdclass();
$lang->waterfall->subMenu->other = new stdclass();
$lang->waterfall->subMenu->other->estimation  = array('link' => '估算|workestimation|index|program=%s', 'subModule' => 'workestimation,durationestimation,budget');
$lang->waterfall->subMenu->other->issue       = array('link' => '問題|issue|browse|', 'subModule' => 'issue');
$lang->waterfall->subMenu->other->risk        = array('link' => '風險|risk|browse|', 'subModule' => 'risk');
$lang->waterfall->subMenu->other->stakeholder = array('link' => '干係人|stakeholder|browse|', 'subModule' => 'stakeholder');
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

$lang->stakeholder->menu->list  = array('link' => '干係人列表|stakeholder|browse|', 'alias' => 'create,edit,view,batchcreate');
$lang->stakeholder->menu->issue = array('link' => '問題管理|stakeholder|issue|');

$lang->nc->menu = $lang->auditplan->menu;
$lang->noMenuModule = array('report', 'my', 'todo', 'effort', 'program', 'product', 'productplan', 'projectbuild', 'projectrelease', 'projectstory', 'story', 'branch', 'release', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'custom', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'pssp', 'sms', 'message', 'webhook', 'search', 'score', 'stage');
if($config->systemMode == 'classic')
{
    $lang->noMenuModule[] = 'project';
    $lang->noMenuModule[] = 'task';
}

include (dirname(__FILE__) . '/menuOrder.php');
