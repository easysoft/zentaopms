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
$lang->aboutZenTao    = 'ZenTao';
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
$lang->trunk        = 'Trunk';
$lang->sort         = 'Ranking';
$lang->required     = 'Required';

$lang->actions         = 'Actions';
$lang->comment         = 'Note';
$lang->history         = 'History';
$lang->attatch         = 'Attachment';
$lang->reverse         = 'Reverse';
$lang->switchDisplay   = 'Toggle';
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

$lang->future       = 'Pending';
$lang->year         = 'Year';
$lang->workingHour  = 'Man-Hour';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Open';
$lang->assignedToAB = 'To';
$lang->typeAB       = 'Type';

$lang->common = new stdclass();
$lang->common->common = 'Common Module';

/* 主导航菜单。*/
$lang->menu = new stdclass();
$lang->menu->my       = '<i class="icon-home"></i><span>Dashboard</span>|my|index';
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
$lang->searchObjects['productplan'] = $lang->productCommon . 'Plan';
$lang->searchObjects['testtask']    = 'Test Task';
$lang->searchObjects['doc']         = 'Document';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'Test Report';
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
$lang->my->menu->index          = 'Home|my|index';
$lang->my->menu->todo           = array('link' => 'To-Do|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = array('link' => 'Task|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => 'Test Task|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
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
$lang->product->menu->plan    = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => 'Document|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
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
$lang->project->menu->testtask  = array('link' => 'Test Task|project|testtask|projectID=%s');
$lang->project->menu->team      = array('link' => 'Team|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => 'Document|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
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

$lang->qa->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s');
$lang->qa->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->qa->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->qa->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->qa->menu->caselib   = array('link' => 'Library|testsuite|library');
$lang->qa->menu->index     = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();

$lang->bug->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s');
$lang->bug->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => 'Library|testsuite|library');
$lang->bug->menu->index     = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();

$lang->testcase->menu->product   = array('link' => '%s', 'fixed' => true);
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree');
$lang->testcase->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testcase->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testcase->menu->report    = array('link' => 'Report|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testcase->menu->caselib   = array('link' => 'Library|testsuite|library');
$lang->testcase->menu->index     = array('link' => "<i class='icon-home'></i>TestingHomepage|qa|index|locate=no&productID=%s", 'float' => 'right');

$lang->testtask = new stdclass();
$lang->testtask->menu = $lang->testcase->menu;
$lang->testsuite = new stdclass();
$lang->testsuite->menu = $lang->testcase->menu;
$lang->testreport = new stdclass();
$lang->testreport->menu = $lang->testcase->menu;

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->lib       = array('link' => '%s', 'fixed' => true);
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => 'Case|testcase|browse|');
$lang->caselib->menu->testtask  = array('link' => 'Build|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => 'Suite|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => 'Report|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => 'Library|testsuite|library', 'alias' => 'createlib,createcase,libview,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');
$lang->caselib->menu->create    = array('link' => "<i class='icon-plus'></i>Create a Library|testsuite|createLib|", 'float' => 'right');
$lang->caselib->menu->index     = array('link' => "<i class='icon-home'></i>Test Home|qa|index|locate=no&productID=%s", 'float' => 'right');

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
$lang->admin->menu->index     = array('link' => 'Homepage|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->custom    = array('link' => 'Custom|custom|set', 'subModule' => 'custom');
$lang->admin->menu->mail      = array('link' => 'Email|mail|index', 'subModule' => 'mail');
$lang->admin->menu->backup    = array('link' => 'Backup|backup|index', 'subModule' => 'backup');
$lang->admin->menu->safe      = array('link' => 'Security|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->cron      = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->menu->trashes   = array('link' => 'Recycle|action|trash', 'subModule' => 'action');
$lang->admin->menu->dev       = array('link' => 'Develop|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor');
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
$lang->menugroup->testsuite   = 'qa';
$lang->menugroup->caselib     = 'qa';
$lang->menugroup->testreport  = 'qa';
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
$lang->error->editedByOther   = 'This record might have been changed. Please refresh and try to edit again!';
$lang->error->tutorialData    = 'No data can be imported in tutorial mode. Please exit tutorial first!';

/* 分页信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No History";
$lang->pager->digest       = " <strong>%s</strong> in total. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = " <strong>%s</strong> per page";
$lang->pager->first        = "<i class='icon-step-backward' title='Homepage'></i>";
$lang->pager->pre          = "<i class='icon-play icon-rotate-180' title='Previous Page'></i>";
$lang->pager->next         = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "Previous";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong>.";

$lang->proVersion     = "<a href='http://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>ZenTao Pro <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "Download Desktop Notification";

$lang->suhosinInfo   = "Warning! Data is reaching the limit. Please change <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->maxVarsInfo   = "Warning! Data is reaching the limit. Please change <font color=red>max_input_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->pasteTextInfo = "Paste text into text field. Each line will be a header of each data record. ";
$lang->noticeImport  = "<p style='font-size:14px'>Imported data contains data that has already existed in system. Please confirm you actions on the date </p><p><a href='javascript:submitForm(\"cover\")' class='btn btn-mini'>Override</a> <a href='javascript:submitForm(\"insert\")' class='btn btn-mini'>New Insertion</a></p>";

$lang->noResultsMatch     = "No results match!";
$lang->searchMore         = "More results：";
$lang->chooseUsersToMail  = "Choose users you will send notifications to...";
$lang->browserNotice      = 'Your current browser might not show the best effect. Please use Chrome, Firefox, IE9+, Opera or Safari.';
$lang->noticePasteImg     = "Paste image to editor.";

/* 时间格式设置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'M d H:i');
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
$lang->icons['testcase']  = 'sitemap';
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

global $config;
if(isset($config->global->flow) and $config->global->flow == 'onlyStory')
{
    /* Remove project, report and qa module. */
    unset($lang->menu->project);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->project);
    unset($lang->product->menu->doc);
    
    /* Rename product module. */
    $lang->menu->product = 'Product|product|index';

    /* Adjust search items. */
    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['testtask']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTask')
{
    /* Remove product, report and qa module. */
    unset($lang->menu->product);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[10]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->story);

    /* Adjust sub menu of project  module. */
    unset($lang->project->menu->story);
    unset($lang->project->menu->build);
    unset($lang->project->menu->bug);
    unset($lang->project->menu->testtask);
    unset($lang->project->menu->product);
    unset($lang->project->menu->doc);

    /* Remove sub menu of product module. */
    unset($lang->product->menu);
    unset($lang->product->menuOrder);

    $lang->project->menu->task['subModule'] = 'task';
    $lang->project->menu->task['alias']     = 'grouptask,importtask';

    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['product']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
    unset($lang->searchObjects['testtask']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTest')
{
    /* Remove project and test module. */
    unset($lang->menu->project);
    unset($lang->menu->qa);
    unset($lang->menu->report);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[30]);

    /* Rename product module. */
    $lang->menu->product = 'Product|product|index';

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);
    unset($lang->my->menu->story);

    /* Remove sub menu of project module. */
    unset($lang->project->menu);
    unset($lang->project->menuOrder);

    /* Add bug, testcase and testtask module. */
    $lang->menu->bug       = 'Bug|bug|index';
    $lang->menu->testcase  = 'Case|testcase|index';
    $lang->menu->testsuite = 'Suite|testsuite|index';
    $lang->menu->testtask  = 'Testing|testtask|index';
    $lang->menu->caselib   = 'Library|testsuite|library';

    $lang->menuOrder[6]  = 'bug';
    $lang->menuOrder[7]  = 'testcase';
    $lang->menuOrder[8]  = 'testsuite';
    $lang->menuOrder[9]  = 'testtask';
    $lang->menuOrder[10] = 'caselib';
    $lang->menuOrder[11] = 'product';

    /* Adjust sub menu of bug module. */
    $lang->bug->menu = new stdclass();
    $lang->bug->menu->product       = array('link' => '%s', 'fixed' => true);
    $lang->bug->menu->unclosed      = 'Open|bug|browse|productID=%s&branch=%s&browseType=unclosed&param=%s';
    $lang->bug->menu->all           = 'All|bug|browse|productID=%s&branch=%s&browseType=all&param=%s';
    $lang->bug->menu->assigntome    = 'Assigned To Me|bug|browse|productID=%s&branch=%s&browseType=assigntome&param=%s';
    $lang->bug->menu->openedbyme    = 'Created By Me|bug|browse|productID=%s&branch=%s&browseType=openedbyme&param=%s';
    $lang->bug->menu->resolvedbyme  = 'Resolved By Me|bug|browse|productID=%s&branch=%s&browseType=resolvedbyme&param=%s';
    $lang->bug->menu->unconfirmed   = 'Not Confirmed|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s';
    $lang->bug->menu->assigntonull  = 'Unassigned|bug|browse|productID=%s&branch=%s&browseType=assigntonull&param=%s';
    $lang->bug->menu->unresolved    = 'Unresolved|bug|browse|productID=%s&branch=%s&browseType=unresolved&param=%s';
    $lang->bug->menu->toclosed      = 'To Be Closed|bug|browse|productID=%s&branch=%s&browseType=toclosed&param=%s';
    $lang->bug->menu->longlifebugs  = 'Pending|bug|browse|productID=%s&branch=%s&browseType=longlifebugs&param=%s';
    $lang->bug->menu->postponedbugs = 'PostPoned|bug|browse|productID=%s&branch=%s&browseType=postponedbugs&param=%s';
    $lang->bug->menu->overduebugs   = 'Overdue|bug|browse|productID=%s&branch=%s&browseType=overduebugs&param=%s';

    $lang->bug->menuOrder[5]  = 'product';
    $lang->bug->menuOrder[10] = 'unclosed';
    $lang->bug->menuOrder[15] = 'all';
    $lang->bug->menuOrder[20] = 'assigntome';
    $lang->bug->menuOrder[25] = 'openedbyme';
    $lang->bug->menuOrder[30] = 'resolvedbyme';
    $lang->bug->menuOrder[35] = 'unresolved';
    $lang->bug->menuOrder[40] = 'assigntonull';
    $lang->bug->menuOrder[45] = 'unresolved';
    $lang->bug->menuOrder[50] = 'toclosed';
    $lang->bug->menuOrder[55] = 'longlifebugs';
    $lang->bug->menuOrder[60] = 'postponedbugs';
    $lang->bug->menuOrder[65] = 'overduebugs';

    /* Adjust sub menu of testcase. */
    $lang->testcase->menu = new stdclass();
    $lang->testcase->menu->product = array('link' => '%s', 'fixed' => true);
    $lang->testcase->menu->all     = 'All|testcase|browse|productID=%s&branch=%s&browseType=all';
    $lang->testcase->menu->wait    = 'Wait|testcase|browse|productID=%s&branch=%s&browseType=wait';
    $lang->testcase->menu->suite   = array('link' => '%s', 'fixed' => true);

    $lang->testcase->menuOrder[5]  = 'product';
    $lang->testcase->menuOrder[10] = 'all';
    $lang->testcase->menuOrder[15] = 'wait';
    $lang->testcase->menuOrder[20] = 'suite';

    /* Adjust sub menu of bug module. */
    $lang->testsuite->menu = new stdclass();
    $lang->testsuite->menu->product = array('link' => '%s', 'fixed' => true);
    $lang->testsuite->menu->create  = array('link' => "<i class='icon-plus'></i> Create Suite|testsuite|create|productID=%s", 'float' => 'right');

    $lang->testsuite->menuOrder[5]  = 'product';
    $lang->testsuite->menuOrder[10] = 'create';

    /* Adjust sub menu of testtask. */
    $lang->testtask->menu = new stdclass();
    $lang->testtask->menu->product     = array('link' => '%s', 'fixed' => true);
    $lang->testtask->menu->scope       = array('link' => '%s', 'fixed' => true);
    $lang->testtask->menu->wait        = 'Build to be Tested|testtask|browse|productID=%s&branch=%s&type=%s,wait';
    $lang->testtask->menu->doing       = 'Testing Build|testtask|browse|productID=%s&branch=%s&type=%s,doing';
    $lang->testtask->menu->blocked     = 'Blocked Build|testtask|browse|productID=%s&branch=%s&type=%s,blocked';
    $lang->testtask->menu->done        = 'Tested Build|testtask|browse|productID=%s&branch=%s&type=%s,done';
    $lang->testtask->menu->totalStatus = 'All|testtask|browse|productID=%s&branch=%s&type=%s,totalStatus';
    $lang->testtask->menu->report      = array('link' => 'Report|testreport|browse');
    $lang->testtask->menu->create      = array('link' => "<i class='icon-plus'></i> Create|testtask|create|productID=%s", 'float' => 'right');

    $lang->testtask->menuOrder[5]   = 'product';
    $lang->testtask->menuOrder[10]  = 'scope';
    $lang->testtask->menuOrder[15]  = 'wait';
    $lang->testtask->menuOrder[20]  = 'doing';
    $lang->testtask->menuOrder[25]  = 'blocked';
    $lang->testtask->menuOrder[30]  = 'done';
    $lang->testtask->menuOrder[35]  = 'totalStatus';
    $lang->testtask->menuOrder[40]  = 'report';
    $lang->testtask->menuOrder[45]  = 'create';

    $lang->testreport->menu      = $lang->testtask->menu;
    $lang->testreport->menuOrder = $lang->testtask->menuOrder;

    /* Adjust sub menu of caselib module. */
    $lang->caselib->menu = new stdclass();
    $lang->caselib->menu->lib    = array('link' => '%s', 'fixed' => true);
    $lang->caselib->menu->all    = 'All|testsuite|library|libID=%s&browseType=all';
    $lang->caselib->menu->wait   = 'Wait|testsuite|library|libID=%s&browseType=wait';
    $lang->caselib->menu->view   = 'View|testsuite|libview|libID=%s';
    $lang->caselib->menu->create = array('link' => "<i class='icon-plus'></i> Create Library|testsuite|createLib", 'float' => 'right');

    $lang->caselib->menuOrder[5]  = 'lib';
    $lang->caselib->menuOrder[10] = 'all';
    $lang->caselib->menuOrder[15] = 'wait';
    $lang->caselib->menuOrder[20] = 'view';

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->story);
    unset($lang->product->menu->project);
    unset($lang->product->menu->release);
    unset($lang->product->menu->dynamic);
    unset($lang->product->menu->plan);
    unset($lang->product->menu->roadmap);
    unset($lang->product->menu->doc);
    unset($lang->product->menu->module);
    unset($lang->product->menu->index);

    $lang->product->menu->build = array('link' => 'Build|product|build', 'subModule' => 'build');

    $lang->product->menuOrder[5]  = 'build';
    $lang->product->menuOrder[10] = 'view';
    $lang->product->menuOrder[15] = 'order';

    $lang->build->menu      = $lang->product->menu;
    $lang->build->menuOrder = $lang->product->menuOrder;
    
    /* Adjust menu group. */
    $lang->menugroup->bug        = 'bug';
    $lang->menugroup->testcase   = 'testcase';
    $lang->menugroup->testtask   = 'testtask';
    $lang->menugroup->testsuite  = 'testsuite';
    $lang->menugroup->testreport = 'testtask';
    $lang->menugroup->build      = 'product';
    
    /* Adjust search objects. */ 
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
}
