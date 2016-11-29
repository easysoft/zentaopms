<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '::';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' on ';
$lang->downArrow = '↓';
$lang->null      = 'null';
$lang->ellipsis  = '…';

$lang->zentaoPMS      = 'ZenTao';
$lang->welcome        = "%s PMS";
$lang->logout         = 'Logout';
$lang->login          = 'Login';
$lang->help           = 'Help';
$lang->aboutZenTao    = 'About ZenTao';
$lang->profile        = 'Profile';
$lang->changePassword = 'Password';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "I have read and agreed to the terms and conditions of <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Without authorization, I should not remove, hide, or cover any logos/links of ZenTao.</span>";

$lang->reset        = 'Reset';
$lang->refresh      = 'Refresh';
$lang->edit         = 'Edit';
$lang->delete       = 'Delete';
$lang->close        = 'Close';
$lang->unlink       = 'Unlink';
$lang->import       = 'Import';
$lang->export       = 'Export';
$lang->setFileName  = 'File Name';
$lang->submitting   = 'Saving...';
$lang->save         = 'Save';
$lang->confirm      = 'Confirm';
$lang->preview      = 'View';
$lang->goback       = 'Back';
$lang->goPC         = 'PC';
$lang->more         = 'More';
$lang->day          = 'Day';
$lang->customConfig = 'Custom';
$lang->public       = 'Public';

$lang->actions         = 'Actions';
$lang->comment         = 'Note';
$lang->history         = 'History';
$lang->attatch         = 'Attachment';
$lang->reverse         = 'Reverse';
$lang->switchDisplay   = 'Toggle Show';
$lang->addFiles        = 'Add';
$lang->files           = 'File ';
$lang->pasteText       = 'Paste';
$lang->uploadImages    = 'Upload';
$lang->timeout         = 'Timeout. Pease check your network settings, or try it again!';
$lang->repairTable     = 'Database table might be damaged. Please use phpmyadmin or myisamchk to fix it.';
$lang->duplicate       = '%s has the same title as existing file.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, current IP Login has been restricted. PLease contact Admin to unrestrict it.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Set as Homepage';
$lang->tutorial        = 'Tutorial';
$lang->changeLog       = 'Change Log';
$lang->manual          = 'Manual';
$lang->customMenu      = 'Custom Menu';
$lang->tutorialConfirm = 'You are using tutorial. Do you want to exit right now？';

$lang->preShortcutKey  = '[Shortcut:←]';
$lang->nextShortcutKey = '[Shortcut:→]';
$lang->backShortcutKey = '[Shortcut:Alt+↑]';

$lang->select        = 'Select';
$lang->selectAll     = 'Select All';
$lang->selectReverse = 'Select Reverse';
$lang->loading       = 'Loading...';
$lang->notFound      = 'Not found!';
$lang->showAll       = '[[Show All]]';

$lang->future       = 'Coming';
$lang->year         = 'Year';
$lang->workingHour  = 'Man-Hour';

$lang->idAB         = 'ID';
$lang->priAB        = 'Privil';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Open';
$lang->assignedToAB = 'To';
$lang->typeAB       = 'Type';

$lang->common = new stdclass();
$lang->common->common = 'Common Module';

/* 主导航菜单。*/
$lang->menu = new stdclass();
$lang->menu->my       = '<i class="icon-home"></i><span> My Zone</span>|my|index';
$lang->menu->product  = $lang->productCommon . '|product|index';
$lang->menu->project  = $lang->projectCommon . '|project|index';
$lang->menu->qa       = 'Testing|qa|index';
$lang->menu->doc      = 'Document|doc|index';
$lang->menu->report   = 'Report|report|index';
$lang->menu->company  = 'Company|company|index';
$lang->menu->admin    = 'Administration|admin|index';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Task';
$lang->searchObjects['testcase']    = 'Case';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['user']        = 'User';
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . 'Planning';
$lang->searchObjects['testtask']    = 'Testing Build';
$lang->searchObjects['doc']         = 'Document';
$lang->searchTips                   = 'ID (ctrl+g)';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'All';
$lang->exportTypeList['selected'] = 'Selected';

/* 语言 */
$lang->lang = 'Language';

/* 风格列表。*/
$lang->theme                 = 'Theme';
$lang->themes['default']     = 'Default';
$lang->themes['green']       = 'Green';
$lang->themes['red']         = 'Red';
$lang->themes['lightblue']   = 'Bright Blue';
$lang->themes['blackberry']  = 'Blackberry';

/* 首页菜单设置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "View{$lang->productCommon}|product|browse";
$lang->index->menu->project = "View{$lang->projectCommon}|project|browse";

/* 我的地盘菜单设置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->account        = array('link' => '<span id="myname"><i class="icon-user"></i> %s' . $lang->arrow . '</span>', 'fixed' => true);
$lang->my->menu->index          = 'Homepage|my|index';
$lang->my->menu->todo           = array('link' => 'To-Do|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = array('link' => 'Task|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => 'Testing|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => 'Story|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic        = 'Dynamic|my|dynamic|';
$lang->my->menu->profile        = array('link' => 'Profile|my|profile', 'alias' => 'editprofile');
$lang->my->menu->changePassword = 'Password|my|changepassword';
$lang->my->menu->manageContacts = 'Contact|my|managecontacts';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* 产品视图设置。*/
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->product->menu->story   = array('link' => 'Story|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->dynamic = 'Dynamic|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => 'Planning|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => 'Document|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->branch  = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module  = 'Module|tree|browse|productID=%s&view=story';
$lang->product->menu->view    = array('link' => 'Overview|product|view|productID=%s', 'alias' => 'edit');
$lang->product->menu->project = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->create  = array('link' => "<i class='icon-plus'></i>&nbsp;Add{$lang->productCommon}|product|create", 'float' => 'right');
$lang->product->menu->all     = array('link' => "<i class='icon-cubes'></i>&nbsp;All{$lang->productCommon}|product|all|productID=%s", 'float' => 'right');
$lang->product->menu->index   = array('link' => "<i class='icon-home'></i>{$lang->productCommon}Homepage|product|index|locate=no", 'float' => 'right');

$lang->story       = new stdclass();
$lang->productplan = new stdclass();
$lang->release     = new stdclass();
$lang->branch      = new stdclass();

$lang->branch->menu      = $lang->product->menu;
$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->list      = array('link' => '%s', 'fixed' => true);
$lang->project->menu->task      = array('link' => 'Task|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'grouptask,importtask,burn,importbug,kanban,printkanban,tree');
$lang->project->menu->story     = array('link' => 'Story|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = 'Dynamic|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = 'Testing|project|testtask|projectID=%s';
$lang->project->menu->team      = array('link' => 'Team|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => 'Document|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->view      = array('link' => 'Overview|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');
$lang->project->menu->create    = array('link' => "<i class='icon-plus'></i>&nbsp;Add{$lang->projectCommon}|project|create", 'float' => 'right');
$lang->project->menu->all       = array('link' => "<i class='icon-th-large'></i>&nbsp;All{$lang->projectCommon}|project|all|status=undone&projectID=%s", 'float' => 'right');
$lang->project->menu->index     = array('link' => "<i class='icon-home'></i>{$lang->projectCommon}Homepage|project|index|locate=no", 'float' => 'right');

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->qa->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase = array('link' => 'Case|testcase|browse|productID=%s');
$lang->qa->menu->testtask = array('link' => 'Build|testtask|browse|productID=%s');
$lang->qa->menu->index    = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();

$lang->bug->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->bug->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => 'Case|testcase|browse|productID=%s');
$lang->bug->menu->testtask = array('link' => 'Build|testtask|browse|productID=%s');
$lang->bug->menu->index    = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();

$lang->testcase->menu->product  = array('link' => '%s', 'fixed' => true);
$lang->testcase->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => 'Case|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => 'Build|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase');
$lang->testcase->menu->index    = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testtask = new stdclass();
$lang->testtask->menu = $lang->testcase->menu;

/* 文档视图菜单设置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->doc->menu->list    = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->crumb   = array('link' => '%s', 'fixed' => true);
$lang->doc->menu->create  = array('link' => '<i class="icon-plus"></i>&nbsp;Add Doc Lib|doc|createLib', 'float' => 'right');

/* 统计视图菜单设置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => 'Testing|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => 'Company|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = 'Note: Report is generated from search results. Please search in the list page before you generate a report.';

/* 组织结构视图菜单设置。*/
$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->name         = array('link' => '%s' . $lang->arrow, 'fixed' => true);
$lang->company->menu->browseUser   = array('link' => 'User|company|browse', 'subModule' => 'user');
$lang->company->menu->dept         = array('link' => 'Department|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup  = array('link' => 'Group|group|browse', 'subModule' => 'group');
$lang->company->menu->view         = array('link' => 'Company|company|view', 'alias' => 'edit');
$lang->company->menu->dynamic      = 'Dynamic|company|dynamic|';
$lang->company->menu->addGroup     = array('link' => '<i class="icon-group"></i>&nbsp;Add Group|group|create', 'float' => 'right');
$lang->company->menu->batchAddUser = array('link' => '<i class="icon-plus-sign"></i>&nbsp;Batch Add|user|batchCreate|dept=%s', 'subModule' => 'user', 'float' => 'right');
$lang->company->menu->addUser      = array('link' => '<i class="icon-plus"></i>&nbsp;Add User|user|create|dept=%s', 'subModule' => 'user', 'float' => 'right');

$lang->dept  = new stdclass();
$lang->group = new stdclass();
$lang->user  = new stdclass();

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

/* 后台管理菜单设置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => 'Homepage|admin|index');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->custom    = array('link' => 'Custom|custom|set', 'subModule' => 'custom');
$lang->admin->menu->mail      = array('link' => 'Email|mail|index', 'subModule' => 'mail');
$lang->admin->menu->backup    = array('link' => 'Backup|backup|index', 'subModule' => 'backup');
$lang->admin->menu->safe      = array('link' => 'Security|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->cron      = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->menu->trashes   = array('link' => 'Recycle|action|trash', 'subModule' => 'action');
$lang->admin->menu->dev       = array('link' => 'Reproduct|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor');
$lang->admin->menu->sso       = 'RangerTeam|admin|sso';

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

/* 错误提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = "The domain %s cannot be found!";
$lang->error->length          = array("『%s』Length Error. It should be『%s』", "『%s』length should be <=『%s』and >=『%s』.");
$lang->error->reg             = "『%s』Format Error. It should be『%s』.";
$lang->error->unique          = "『%s』『%s』existed. Please go to Admin->Recycle to restore it, if you are sure it has been deleted.";
$lang->error->gt              = "『%s』should be >『%s』.";
$lang->error->ge              = "『%s』should be >=『%s』.";
$lang->error->notempty        = "『%s』should not be blank.";
$lang->error->empty           = "『%s』should be null.";
$lang->error->equal           = "『%s』has to be『%s』.";
$lang->error->int             = array("『%s』has to be numbers", "『%s』should be 『%s-%s』.");
$lang->error->float           = "『%s』should be numbers, decimals included.";
$lang->error->email           = "『%s』should be valid EMAIL.";
$lang->error->date            = "『%s』should be valid date.";
$lang->error->account         = "『%s』should be valid account.";
$lang->error->passwordsame    = "Two passwords should be consistent.";
$lang->error->passwordrule    = "Password should meet requirements. It should be 6 characters at least.";
$lang->error->accessDenied    = 'Access is denied.';
$lang->error->pasteImg        = 'Paste Image is not allowed in your browser!';
$lang->error->noData          = 'No Data';
$lang->error->editedByOther   = 'This record might have been modified. Please refresh and try to edit again!';
$lang->error->tutorialData    = 'No data can be imported in tutorial mode. Please exit tutorial first!';

/* 分页信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord  = "No History";
$lang->pager->digest    = " <strong>%s</strong> in total. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage= " <strong>%s</strong> per page";
$lang->pager->first     = "<i class='icon-step-backward' title='Homepage'></i>";
$lang->pager->pre       = "<i class='icon-play icon-rotate-180' title='Previous Page'></i>";
$lang->pager->next      = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last      = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate    = "GO!";

$lang->proVersion     = "<a href='http://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>ZenTao Pro <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "Download Desktop Notification";

$lang->suhosinInfo   = "Warning! Data is reaching the limit. Please change <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> (set larger value) in php.ini, then save and restart Apache, or some data will not be saved.";
$lang->pasteTextInfo = "Paste text into text field. Each line will be a header of each data record. ";
$lang->noticeImport  = "<p style='font-size:14px'>Imported data contains data that has already existed in system. Please confirm you actions on the date </p><p><a href='javascript:submitForm(\"cover\")' class='btn btn-mini'>Override</a> <a href='javascript:submitForm(\"insert\")' class='btn btn-mini'>New Insertion</a></p>";

$lang->noResultsMatch     = "No results match!";
$lang->searchMore         = "More results：";
$lang->chooseUsersToMail  = "Choose users you want send Email to...";
$lang->browserNotice      = 'Your current browser might not show the best effect. Please use Chrome, Firefox, IE9+, Opera or Safari.';
$lang->noticePasteImg     = "Can parse image in editor.";

/* 时间格式设置。*/
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

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'or ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Last Year';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Last Month';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Last Week';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Yesterday';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'This Month';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'This Week';
$lang->datepicker->dpText->TEXT_TODAY       = 'Today';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Next Year';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Next Month';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Close';
$lang->datepicker->dpText->TEXT_DATE        = 'Choose Time';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Choose Date';

$lang->datepicker->dayNames     = array('Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat');
$lang->datepicker->abbrDayNames = array('Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat');
$lang->datepicker->monthNames   = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

/* Common action icons 通用动作图标 */
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
