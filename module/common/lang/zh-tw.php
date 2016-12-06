<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '::';
$lang->comma     = '，';
$lang->dot       = '。';
$lang->at        = ' 于 ';
$lang->downArrow = '↓';
$lang->null      = '空';
$lang->ellipsis  = '…';

$lang->zentaoPMS      = '禪道';
$lang->welcome        = "%s項目管理系統";
$lang->logout         = '退出';
$lang->login          = '登錄';
$lang->help           = '幫助';
$lang->aboutZenTao    = '關於';
$lang->profile        = '個人檔案';
$lang->changePassword = '更改密碼';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間: %s 毫秒, 內存: %s KB, 查詢: %s.  </div></div>";
$lang->agreement      = "已閲讀並同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授權協議1.2》</a>。<span class='text-danger'>未經許可，不得去除、隱藏或遮掩禪道軟件的任何標誌及連結。</span>";

$lang->reset        = '重填';
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
$lang->confirm      = '確認';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->goPC         = 'PC版';
$lang->more         = '更多';
$lang->day          = '天';
$lang->customConfig = '自定義';
$lang->public       = '公共';

$lang->actions         = '操作';
$lang->comment         = '備註';
$lang->history         = '歷史記錄';
$lang->attatch         = '附件';
$lang->reverse         = '切換順序';
$lang->switchDisplay   = '切換顯示';
$lang->addFiles        = '上傳了附件 ';
$lang->files           = '附件 ';
$lang->pasteText       = '粘貼文本 ';
$lang->uploadImages    = '多圖上傳 ';
$lang->timeout         = '連接超時，請檢查網絡環境，或重試！';
$lang->repairTable     = '資料庫表可能損壞，請用phpmyadmin或myisamchk檢查修復。';
$lang->duplicate       = '已有相同標題的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理員限制當前IP登錄，請聯繫管理員解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '設為模組首頁';
$lang->tutorial        = '新手教程';
$lang->changeLog       = '修改日誌';
$lang->manual          = '手冊';
$lang->customMenu      = '自定義導航';
$lang->tutorialConfirm = '檢測到你尚未退出新手教程模式，是否現在退出？';

$lang->preShortcutKey  = '[快捷鍵:←]';
$lang->nextShortcutKey = '[快捷鍵:→]';
$lang->backShortcutKey = '[快捷鍵:Alt+↑]';

$lang->select        = '選擇';
$lang->selectAll     = '全選';
$lang->selectReverse = '反選';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您訪問的對象並不存在！';
$lang->showAll       = '[[全部顯示]]';

$lang->future       = '未來';
$lang->year         = '年';
$lang->workingHour  = '工時';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';

$lang->common = new stdclass();
$lang->common->common = '公有模組';

/* 主導航菜單。*/
$lang->menu = new stdclass();
$lang->menu->my       = '<i class="icon-home"></i><span> 我的地盤</span>|my|index';
$lang->menu->product  = $lang->productCommon . '|product|index';
$lang->menu->project  = $lang->projectCommon . '|project|index';
$lang->menu->qa       = '測試|qa|index';
$lang->menu->doc      = '文檔|doc|index';
$lang->menu->report   = '統計|report|index';
$lang->menu->company  = '組織|company|index';
$lang->menu->admin    = '後台|admin|index';

/* 查詢條中可以選擇的對象列表。*/
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = '需求';
$lang->searchObjects['task']        = '任務';
$lang->searchObjects['testcase']    = '用例';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['user']        = '用戶';
$lang->searchObjects['build']       = '版本';
$lang->searchObjects['release']     = '發佈';
$lang->searchObjects['productplan'] = $lang->productCommon . '計劃';
$lang->searchObjects['testtask']    = '測試版本';
$lang->searchObjects['doc']         = '文檔';
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
$lang->theme                 = '主題';
$lang->themes['default']     = '預設';
$lang->themes['green']       = '綠色';
$lang->themes['red']         = '紅色';
$lang->themes['lightblue']   = '亮藍';
$lang->themes['blackberry']  = '黑莓';

/* 首頁菜單設置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "瀏覽{$lang->productCommon}|product|browse";
$lang->index->menu->project = "瀏覽{$lang->projectCommon}|project|browse";

/* 我的地盤菜單設置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->account        = array('link' => '<span id="myname"><i class="icon-user"></i> %s' . $lang->arrow . '</span>', 'fixed' => true);
$lang->my->menu->index          = '首頁|my|index';
$lang->my->menu->todo           = array('link' => '待辦|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = array('link' => '任務|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => '測試|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => '需求|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic        = '動態|my|dynamic|';
$lang->my->menu->profile        = array('link' => '檔案|my|profile', 'alias' => 'editprofile');
$lang->my->menu->changePassword = '密碼|my|changepassword';
$lang->my->menu->manageContacts = '聯繫人|my|managecontacts';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* 產品視圖設置。*/
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->dynamic = '動態|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '計劃|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路線圖|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文檔|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->menu->branch  = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module  = '模組|tree|browse|productID=%s&view=story';
$lang->product->menu->view    = array('link' => '概況|product|view|productID=%s', 'alias' => 'edit');
$lang->product->menu->project = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->create  = array('link' => "<i class='icon-plus'></i>&nbsp;添加{$lang->productCommon}|product|create", 'float' => 'right');
$lang->product->menu->all     = array('link' => "<i class='icon-cubes'></i>&nbsp;所有{$lang->productCommon}|product|all|productID=%s", 'float' => 'right');
$lang->product->menu->index   = array('link' => "<i class='icon-home'></i>{$lang->productCommon}主頁|product|index|locate=no", 'float' => 'right');

$lang->story       = new stdclass();
$lang->productplan = new stdclass();
$lang->release     = new stdclass();
$lang->branch      = new stdclass();

$lang->branch->menu      = $lang->product->menu;
$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* 項目視圖菜單設置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->list      = array('link' => '%s', 'fixed' => true);
$lang->project->menu->task      = array('link' => '任務|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'grouptask,importtask,burn,importbug,kanban,printkanban,tree');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '動態|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = '測試|project|testtask|projectID=%s';
$lang->project->menu->team      = array('link' => '團隊|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文檔|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->product   = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->view      = array('link' => '概況|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');
$lang->project->menu->create    = array('link' => "<i class='icon-plus'></i>&nbsp;添加{$lang->projectCommon}|project|create", 'float' => 'right');
$lang->project->menu->all       = array('link' => "<i class='icon-th-large'></i>&nbsp;所有{$lang->projectCommon}|project|all|status=undone&projectID=%s", 'float' => 'right');
$lang->project->menu->index     = array('link' => "<i class='icon-home'></i>{$lang->projectCommon}主頁|project|index|locate=no", 'float' => 'right');

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA視圖菜單設置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->qa->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase = array('link' => '用例|testcase|browse|productID=%s');
$lang->qa->menu->testtask = array('link' => '版本|testtask|browse|productID=%s');
$lang->qa->menu->index    = array('link' => "<i class='icon-home'></i>測試主頁|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();

$lang->bug->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->bug->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例|testcase|browse|productID=%s');
$lang->bug->menu->testtask = array('link' => '版本|testtask|browse|productID=%s');
$lang->bug->menu->index    = array('link' => "<i class='icon-home'></i>測試主頁|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();

$lang->testcase->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->testcase->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '版本|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase');
$lang->testcase->menu->index    = array('link' => "<i class='icon-home'></i>測試主頁|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testtask = new stdclass();
$lang->testtask->menu = $lang->testcase->menu;

/* 文檔視圖菜單設置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->doc->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->crumb   = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->create  = array('link' => '<i class="icon-plus"></i>&nbsp;添加文檔庫|doc|createLib', 'float' => 'right');

/* 統計視圖菜單設置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => '測試|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => '組織|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = '註：統計報表的數據，來源於列表頁面的檢索結果，生成統計報表前請先在列表頁面進行檢索。';

/* 組織結構視圖菜單設置。*/
$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->name         = array('link' => '%s' . $lang->arrow, 'fixed' => true);
$lang->company->menu->browseUser   = array('link' => '用戶|company|browse', 'subModule' => 'user');
$lang->company->menu->dept         = array('link' => '部門|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup  = array('link' => '權限|group|browse', 'subModule' => 'group');
$lang->company->menu->view         = array('link' => '公司|company|view', 'alias' => 'edit');
$lang->company->menu->dynamic      = '動態|company|dynamic|';
$lang->company->menu->addGroup     = array('link' => '<i class="icon-group"></i>&nbsp;添加分組|group|create', 'float' => 'right');
$lang->company->menu->batchAddUser = array('link' => '<i class="icon-plus-sign"></i>&nbsp;批量添加|user|batchCreate|dept=%s', 'subModule' => 'user', 'float' => 'right');
$lang->company->menu->addUser      = array('link' => '<i class="icon-plus"></i>&nbsp;添加用戶|user|create|dept=%s', 'subModule' => 'user', 'float' => 'right');

$lang->dept  = new stdclass();
$lang->group = new stdclass();
$lang->user  = new stdclass();

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

/* 後台管理菜單設置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => '首頁|admin|index');
$lang->admin->menu->extension = array('link' => '插件|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->custom    = array('link' => '自定義|custom|set', 'subModule' => 'custom');
$lang->admin->menu->mail      = array('link' => '發信|mail|index', 'subModule' => 'mail');
$lang->admin->menu->backup    = array('link' => '備份|backup|index', 'subModule' => 'backup');
$lang->admin->menu->safe      = array('link' => '安全|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->cron      = array('link' => '計劃任務|cron|index', 'subModule' => 'cron');
$lang->admin->menu->trashes   = array('link' => '資源回收筒|action|trash', 'subModule' => 'action');
$lang->admin->menu->dev       = array('link' => '二次開發|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor');
$lang->admin->menu->sso       = '然之整合|admin|sso';

$lang->convert   = new stdclass();
$lang->upgrade   = new stdclass();
$lang->action    = new stdclass();
$lang->backup    = new stdclass();
$lang->extension = new stdclass();
$lang->custom    = new stdclass();
$lang->editor    = new stdclass();
$lang->mail      = new stdclass();
$lang->cron      = new stdclass();
$lang->dev       = new stdclass();
$lang->search    = new stdclass();

$lang->convert->menu   = $lang->admin->menu;
$lang->upgrade->menu   = $lang->admin->menu;
$lang->action->menu    = $lang->admin->menu;
$lang->backup->menu    = $lang->admin->menu;
$lang->cron->menu      = $lang->admin->menu;
$lang->extension->menu = $lang->admin->menu;
$lang->custom->menu    = $lang->admin->menu;
$lang->editor->menu    = $lang->admin->menu;
$lang->mail->menu      = $lang->admin->menu;
$lang->dev->menu       = $lang->admin->menu;

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
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->backup      = 'admin';
$lang->menugroup->cron        = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->custom      = 'admin';
$lang->menugroup->editor      = 'admin';
$lang->menugroup->mail        = 'admin';
$lang->menugroup->dev         = 'admin';

/* 錯誤提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = "您訪問的域名 %s 沒有對應的公司。";
$lang->error->length          = array("『%s』長度錯誤，應當為『%s』", "『%s』長度應當不超過『%s』，且不小於『%s』。");
$lang->error->reg             = "『%s』不符合格式，應當為:『%s』。";
$lang->error->unique          = "『%s』已經有『%s』這條記錄了。如果您確定該記錄已刪除，請到後台管理-資源回收筒還原。。";
$lang->error->gt              = "『%s』應當大於『%s』。";
$lang->error->ge              = "『%s』應當不小於『%s』。";
$lang->error->notempty        = "『%s』不能為空。";
$lang->error->empty           = "『%s』必須為空。";
$lang->error->equal           = "『%s』必須為『%s』。";
$lang->error->int             = array("『%s』應當是數字。", "『%s』應當介於『%s-%s』之間。");
$lang->error->float           = "『%s』應當是數字，可以是小數。";
$lang->error->email           = "『%s』應當為合法的EMAIL。";
$lang->error->date            = "『%s』應當為合法的日期。";
$lang->error->account         = "『%s』應當為合法的用戶名。";
$lang->error->passwordsame    = "兩次密碼應當相等。";
$lang->error->passwordrule    = "密碼應該符合規則，長度至少為六位。";
$lang->error->accessDenied    = '您沒有訪問權限';
$lang->error->pasteImg        = '您的瀏覽器不支持粘貼圖片！';
$lang->error->noData          = '沒有數據';
$lang->error->editedByOther   = '該記錄可能已經被改動。請刷新頁面重新編輯！';
$lang->error->tutorialData    = '新手模式下不會插入數據，請退出新手模式操作';

/* 分頁信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord  = "暫時沒有記錄";
$lang->pager->digest    = "共 <strong>%s</strong> 條記錄，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage= "每頁 <strong>%s</strong> 條";
$lang->pager->first     = "<i class='icon-step-backward' title='首頁'></i>";
$lang->pager->pre       = "<i class='icon-play icon-rotate-180' title='上一頁'></i>";
$lang->pager->next      = "<i class='icon-play' title='下一頁'></i>";
$lang->pager->last      = "<i class='icon-step-forward' title='末頁'></i>";
$lang->pager->locate    = "GO!";

$lang->proVersion     = "<a href='http://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>專業版 <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "下載桌面提醒";

$lang->suhosinInfo   = "警告：數據太多，請在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（設置更大的數）。 保存並重新啟動apache，否則會造成部分數據無法保存。";
$lang->pasteTextInfo = "粘貼文本到文本域中，每行文字作為一條數據的標題。";
$lang->noticeImport  = "<p style='font-size:14px'>導入數據中，含有已經存在系統的數據，請確認這些數據要覆蓋或者全新插入</p><p><a href='javascript:submitForm(\"cover\")' class='btn btn-mini'>覆蓋</a> <a href='javascript:submitForm(\"insert\")' class='btn btn-mini'>全新插入</a></p>";

$lang->noResultsMatch     = "沒有匹配結果";
$lang->searchMore         = "搜索此關鍵字的更多結果：";
$lang->chooseUsersToMail  = "選擇要發信通知的用戶...";
$lang->browserNotice      = '你目前使用的瀏覽器可能無法得到最佳瀏覽效果，建議使用Chrome、火狐、IE9+、Opera、Safari瀏覽器。';
$lang->noticePasteImg     = "可以在編輯器直接貼圖。";

/* 時間格式設置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'Y年m月d日');
define('DT_DATE4',     'n月j日');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

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

$lang->datepicker->dayNames     = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

/* Common action icons 通用動作表徵圖 */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'cube';
$lang->icons['bug']       = 'bug';
$lang->icons['task']      = 'check-sign';
$lang->icons['tasks']     = 'tasks';
$lang->icons['project']   = 'folder-close-alt';
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
$lang->icons['testcase']  = 'smile';
$lang->icons['result']    = 'list-alt';
$lang->icons['mail']      = 'envelope';
$lang->icons['trash']     = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app']       = 'th-large';

$lang->icons['results']        = 'list-alt';
$lang->icons['create']         = 'plus';
$lang->icons['post']           = 'edit';
$lang->icons['batchCreate']    = 'plus-sign';
$lang->icons['batchEdit']      = 'edit-sign';
$lang->icons['batchClose']     = 'off';
$lang->icons['edit']           = 'pencil';
$lang->icons['delete']         = 'remove';
$lang->icons['copy']           = 'copy';
$lang->icons['report']         = 'bar-chart';
$lang->icons['export']         = 'download-alt';
$lang->icons['report-file']    = 'file-powerpoint';
$lang->icons['import']         = 'upload-alt';
$lang->icons['finish']         = 'ok-sign';
$lang->icons['resolve']        = 'ok-sign';
$lang->icons['start']          = 'play';
$lang->icons['restart']        = 'play';
$lang->icons['run']            = 'play';
$lang->icons['runCase']        = 'play';
$lang->icons['batchRun']       = 'play-sign';
$lang->icons['assign']         = 'hand-right';
$lang->icons['assignTo']       = 'hand-right';
$lang->icons['change']         = 'random';
$lang->icons['link']           = 'link';
$lang->icons['close']          = 'off';
$lang->icons['activate']       = 'magic';
$lang->icons['review']         = 'review';
$lang->icons['confirm']        = 'search';
$lang->icons['confirmBug']     = 'search';
$lang->icons['putoff']         = 'calendar';
$lang->icons['suspend']        = 'pause';
$lang->icons['pause']          = 'pause';
$lang->icons['cancel']         = 'ban-circle';
$lang->icons['recordEstimate'] = 'time';
$lang->icons['customFields']   = 'cogs';
$lang->icons['manage']         = 'cog';
$lang->icons['unlock']         = 'unlock-alt';
$lang->icons['confirmStoryChange'] = 'search';

include (dirname(__FILE__) . '/menuOrder.php');
