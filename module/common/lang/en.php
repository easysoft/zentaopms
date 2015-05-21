<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow        = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon        = '::';
$lang->comma        = ',';
$lang->dot          = '.';
$lang->at           = ' at ';
$lang->downArrow    = '↓';
$lang->null         = '空';
$lang->ellipsis     = '...';

$lang->zentaoPMS      = 'zentao';
$lang->welcome        = "%s PMS";
$lang->myControl      = "Dashboard";
$lang->currentPos     = 'Current';
$lang->logout         = 'Logout';
$lang->login          = 'Login';
$lang->aboutZenTao    = 'About';
$lang->profile        = 'Profile';
$lang->changePassword = 'Password';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time: %s ms, Memory: %s KB, Queries: %s.  </div></div>";
$lang->agreement      = "I Agree to the <a href='http://zpl.pub/page/zplv11.html' target='_blank'>Z PUBLIC LICENSE 1.1</a>, <span class='text-danger'>and promise to keep the logo, link of ZenTao.</span>";

$lang->reset        = 'Reset';
$lang->cancel       = 'Cancel';
$lang->refresh      = 'Refresh';
$lang->edit         = 'Edit';
$lang->copy         = 'Copy';
$lang->delete       = 'Delete';
$lang->close        = 'Close';
$lang->link         = 'Link';
$lang->unlink       = 'Unlink';
$lang->import       = 'Import';
$lang->export       = 'Export';
$lang->setFileName  = 'Filename:';
$lang->activate     = 'Activate';
$lang->submitting   = 'Saving...';
$lang->save         = 'Save';
$lang->confirm      = 'Confirm';
$lang->preview      = 'View';
$lang->goback       = 'Back';
$lang->goPC         = 'PC';
$lang->go           = 'GO';
$lang->more         = 'More';
$lang->day          = 'Day';

$lang->actions      = 'Actions';
$lang->comment      = 'Comment';
$lang->history      = 'History';
$lang->attatch      = 'Attatch';
$lang->reverse      = 'Reverse';
$lang->switchDisplay= 'Toggle Show';
$lang->switchHelp   = 'Toggle Help';
$lang->addFiles     = 'Add Files';
$lang->files        = 'Files ';
$lang->pasteText    = 'Paste text';
$lang->uploadImages = 'Upload images ';
$lang->timeout      = 'Timed out, please check the network, or retry!';
$lang->repairTable  = 'The table may be damaged, please repair by phpmyadmin or myisamchk!';
$lang->duplicate    = '%s has the same title';
$lang->unfold       = '+';
$lang->fold         = '-';

$lang->selectAll     = 'All';
$lang->selectReverse = 'Inverse';
$lang->notFound      = 'Sorry, the object not found.';
$lang->showAll       = "[[Show all {$lang->projectcommon}s]]";
$lang->hideClosed    = "[[Show {$lang->projectcommon}s going]]";

$lang->future       = 'Future';
$lang->year         = 'Year';
$lang->workingHour  = 'Hour';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Open';
$lang->assignedToAB = 'To';
$lang->typeAB       = 'Type';

$lang->common = new stdclass();
$lang->common->common = 'Common module';

/* The main menu. */
$lang->menu = new stdclass();
$lang->menu->my       = '<i class="icon-home"></i> <span>Dashboard</span>|my|index';
$lang->menu->product  = "{$lang->productcommon}|product|index";
$lang->menu->project  = "{$lang->projectcommon}|project|index";
$lang->menu->qa       = 'Test|qa|index';
$lang->menu->doc      = 'Doc|doc|index';
$lang->menu->report   = 'Report|report|index';
$lang->menu->company  = 'Company|company|index';
$lang->menu->admin    = 'Admin|admin|index';

/* The objects in the search box. */
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Task';
$lang->searchObjects['testcase']    = 'Test Case';
$lang->searchObjects['project']     = $lang->projectcommon;
$lang->searchObjects['product']     = $lang->productcommon;
$lang->searchObjects['user']        = 'User';
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = 'Plan';
$lang->searchObjects['testtask']    = 'Test Build';
$lang->searchObjects['doc']         = 'Doc';
$lang->searchTips                   = 'Id here(ctrl+g)';

/* Encode list of impot. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list of export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'All records';
$lang->exportTypeList['selected'] = 'Only checked';

/* Language. */
$lang->lang = 'Language';

/* Themes. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Default';
$lang->themes['green']      = 'Green';
$lang->themes['red']        = 'Red';
$lang->themes['lightblue']  = 'Light Blue';
$lang->themes['blackberry'] = 'Blackberry';

/* Index mododule menu. */
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "{$lang->productcommon}s|product|browse";
$lang->index->menu->project = "{$lang->projectcommon}s|project|browse";

/* Dashboard menu. */
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->account        = '<span id="myname"><i class="icon-user"></i> %s' . $lang->arrow . '</span>';
$lang->my->menu->index          = 'Index|my|index';
$lang->my->menu->todo           = array('link' => 'Todo|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = array('link' => 'Task|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => 'Test|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => 'Story|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectcommon}|my|project|";
$lang->my->menu->dynamic        = 'Dynamic|my|dynamic|';
$lang->my->menu->profile        = array('link' => 'Profile|my|profile|', 'alias' => 'editprofile');
$lang->my->menu->changePassword = 'Change Password|my|changepassword|';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* Product menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => 'Story|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->dynamic = 'Dynamic|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => 'Doc|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->module  = 'Modules|tree|browse|productID=%s&view=story';
$lang->product->menu->view    = array('link' => 'Info|product|view|productID=%s', 'alias' => 'edit');
$lang->product->menu->project = "{$lang->projectcommon}s|product|project|status=all&productID=%s";
$lang->product->menu->create  = array('link' => '<span class="icon-plus">&nbsp;</span>New|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-cubes">&nbsp;</span>All|product|index|locate=no&productID=%s', 'float' => 'right');

$lang->story       = new stdclass();
$lang->productplan = new stdclass();
$lang->release     = new stdclass();

$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* Project menu. */
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => 'Task|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'grouptask,importtask,importbug,burn');
$lang->project->menu->story     = array('link' => 'Story|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = 'Dynamic|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = 'Testtask|project|testtask|projectID=%s';
$lang->project->menu->team      = array('link' => 'Team|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => 'Doc|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = array('link' => "{$lang->productcommon}|project|manageproducts|projectID=%s", 'alias' => 'edit,start,suspend,delay,close');
$lang->project->menu->view      = 'Info|project|view|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add">&nbsp;</span>New|project|create', 'float' => 'right');
$lang->project->menu->all       = array('link' => "<i class='icon-th-large'></i>&nbsp;{$lang->projectcommon}s|project|index|locate=no&status=undone&projectID=%s", 'float' => 'right');

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA menu. */
$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();

$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => 'Case|testcase|browse|productID=%s');
$lang->bug->menu->testtask = array('link' => 'Build|testtask|browse|productID=%s');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => 'Case|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => 'Build|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase');

$lang->testtask = new stdclass();
$lang->testtask->menu = $lang->testcase->menu;

/* Doc menu. */
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => 'Doc|doc|browse|libID=%s', 'alias' => 'view,create,edit');
$lang->doc->menu->edit    = 'Edit Library|doc|editLib|libID=%s';
$lang->doc->menu->module  = 'Modules|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => 'Delete Library|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>New Library|doc|createLib', 'float' => 'right');

/* Report menu. */
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->product = array('link' => "{$lang->productcommon}|report|productinfo");
$lang->report->menu->prj     = array('link' => "{$lang->projectcommon}|report|projectdeviation");
$lang->report->menu->test    = array('link' => 'Test|report|bugsummary', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => 'Company|report|workload');

/* Company menu. */
$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->name         = '%s' . $lang->arrow;
$lang->company->menu->browseUser   = array('link' => 'Users|company|browse', 'subModule' => 'user');
$lang->company->menu->dept         = array('link' => 'Department|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup  = array('link' => 'Group|group|browse', 'subModule' => 'group');
$lang->company->menu->view         = array('link' => 'Company|company|view', 'alias' => 'edit');
$lang->company->menu->dynamic      = 'Dynamic|company|dynamic|';
$lang->company->menu->addGroup     = array('link' => '<span class="icon-add">&nbsp;</span>Add Group|group|create', 'float' => 'right');
$lang->company->menu->batchAddUser = array('link' => '<span class="icon-green-user-batchCreate">&nbsp;</span>Batch Add|user|batchCreate', 'subModule' => 'user', 'float' => 'right');
$lang->company->menu->addUser      = array('link' => '<span class="icon-add">&nbsp;</span>Add User|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');

$lang->dept  = new stdclass();
$lang->group = new stdclass();
$lang->user  = new stdclass();

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

/* Admin menu. */
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => 'Index|admin|index');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->custom    = array('link' => 'Custom|custom|index', 'subModule' => 'custom');
$lang->admin->menu->mail      = array('link' => 'Email|mail|index', 'subModule' => 'mail');
$lang->admin->menu->convert   = array('link' => 'Import|convert|index', 'subModule' => 'convert');
$lang->admin->menu->backup    = array('link' => 'Backup|backup|index', 'subModule' => 'backup');
$lang->admin->menu->safe      = array('link' => 'Safe|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->cron      = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->menu->trashes   = array('link' => 'Trash|action|trash', 'subModule' => 'action');
$lang->admin->menu->dev       = array('link' => 'Develop|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor');

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

/* Groups. */
$lang->menugroup = new stdclass();
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
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

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "The domain %s does not exist.";
$lang->error->length          = array("『%s』length should be『%s』", "『%s』length should between『%s』and 『%s』.");
$lang->error->reg             = "『%s』should like『%s』";
$lang->error->unique          = "『%s』has『%s』already. If you are sure this record has been deleted, you can restore it in admin panel, trash page.";
$lang->error->gt              = "『%s』must greater than『%s』.";
$lang->error->ge              = "『%s』must greater than or equal『%s』.";
$lang->error->notempty        = "『%s』can not be empty.";
$lang->error->empty           = "『%s』 must be empty.";
$lang->error->equal           = "『%s』must be『%s』.";
$lang->error->int             = array("『%s』should be interger", "『%s』should between『%s-%s』.");
$lang->error->float           = "『%s』should be a interger or float.";
$lang->error->email           = "『%s』should be email.";
$lang->error->date            = "『%s』should be date";
$lang->error->account         = "『%s』should be a valid account.";
$lang->error->passwordsame    = "Two passwords must be the same";
$lang->error->passwordrule    = "Password should more than six letters.";
$lang->error->accessDenied    = 'No purview';
$lang->error->pasteImg        = 'Your browser does not support the paste image!';
$lang->error->noData          = 'No data';

/* Pager. */
$lang->pager = new stdclass();
$lang->pager->noRecord  = "No records yet.";
$lang->pager->digest    = "<strong>%s</strong> records, %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage= "<strong>%s</strong> per page";
$lang->pager->first     = "<i class='icon-step-backward' title='First'></i>";
$lang->pager->pre       = "<i class='icon-play icon-rotate-180' title='Previous'></i>";
$lang->pager->next      = "<i class='icon-play' title='Next'></i>";
$lang->pager->last      = "<i class='icon-step-forward' title='Last'></i>";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "Official Site";
$lang->chinaScrum     = "<a href='http://api.zentao.net/goto.php?item=chinascrum' target='_blank'>Scrum community</a> ";
$lang->agileTraining  = "<a href='http://api.zentao.net/goto.php?item=agiletrain' target='_blank'>Training</a> ";
$lang->donate         = "<a href='http://api.zentao.net/goto.php?item=donate' target='_blank'>Donate</a> ";
$lang->proVersion     = "<a href='http://api.zentao.net/goto.php?item=proversion&from=footer' target='_blank' class='text-important'><i class='text-danger icon-reply icon-rotate-90'></i> PRO</a> &nbsp; ";
$lang->downNotify     = "Down notify";

$lang->suhosinInfo   = "Warming:data is too large! Please enlarge the setting of <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> in php.ini. Otherwise partial data can't be saved.";
$lang->pasteTextInfo = "Paste the text into a text field, each line of text as a data header.";

$lang->noResultsMatch    = "No results match";
$lang->searchMore        = "Search more results with the key:";
$lang->selectAnOption    = "Select an option";
$lang->selectSomeOptions = "Select some options";
$lang->chooseUsersToMail = "Choose users to mail...";

/* Date times. */
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'F j, H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'F j, Y ');
define('DT_DATE4',     'M j');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'Or ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Last year';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Last month';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Last week';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Yesterday';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'This month';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'This week';
$lang->datepicker->dpText->TEXT_TODAY       = 'Today';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Next year';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Next month';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Close';
$lang->datepicker->dpText->TEXT_DATE        = 'Please select date range';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Choose date';

$lang->datepicker->dayNames     = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$lang->datepicker->abbrDayNames = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$lang->datepicker->monthNames   = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

/* Common action icons */
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
$lang->icons['company']   = 'building';
$lang->icons['user']      = 'user';
$lang->icons['dept']      = 'sitemap';
$lang->icons['tree']      = 'sitemap';
$lang->icons['usecase']   = 'sitemap';
$lang->icons['testcase']  = 'smile';
$lang->icons['result']    = 'flag-checkered';
$lang->icons['mail']      = 'envelope';
$lang->icons['trash']     = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app']       = 'th-large';

$lang->icons['results']        = 'flag-checkered';
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
$lang->icons['activate']       = 'off';
$lang->icons['review']         = 'search';
$lang->icons['confirm']        = 'search';
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
