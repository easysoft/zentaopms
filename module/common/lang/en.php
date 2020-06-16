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
$lang->colon     = '-';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' on ';
$lang->downArrow = '↓';
$lang->null      = 'Null';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';

$lang->zentaoPMS      = 'ZenTao';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome        = "%s ALM";
$lang->logout         = 'Logout';
$lang->login          = 'Login';
$lang->help           = 'Help';
$lang->aboutZenTao    = 'About';
$lang->profile        = 'Profile';
$lang->changePassword = 'Password';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "I have read and agreed to the terms and conditions of <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Without authorization, I should not remove, hide or cover any logos/links of ZenTao.</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.pm/goto.php?item=aiux' class='link-aiux' target='_blank'>Designed by <strong>AIUX</strong></a>";

$lang->reset        = 'Reset';
$lang->cancel       = 'Cancel';
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
$lang->saveSuccess  = 'Saved';
$lang->confirm      = 'Confirm';
$lang->preview      = 'View';
$lang->goback       = 'Back';
$lang->goPC         = 'PC';
$lang->more         = 'More';
$lang->day          = ' Day';
$lang->customConfig = 'Custom Config';
$lang->public       = 'Public';
$lang->trunk        = 'Trunk';
$lang->sort         = 'Order';
$lang->required     = 'Required';
$lang->noData       = 'No data.';

$lang->actions         = 'Action';
$lang->restore         = 'Reset';
$lang->comment         = 'Note';
$lang->history         = 'History';
$lang->attatch         = 'Files';
$lang->reverse         = 'Inverse';
$lang->switchDisplay   = 'Toggle';
$lang->expand          = 'Expand';
$lang->collapse        = 'Collapse';
$lang->saveSuccess     = 'Saved';
$lang->fail            = 'Fail';
$lang->addFiles        = 'Added Files';
$lang->files           = 'Files ';
$lang->pasteText       = 'Multi-lines Paste';
$lang->uploadImages    = 'Multi-images Upload';
$lang->timeout         = 'Timeout. Check your newtwork connections, or try it again!';
$lang->repairTable     = 'Database table might be damaged. Run phpmyadmin or myisamchk to fix it.';
$lang->duplicate       = '%s has the same title as a file existed.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, your current IP is restricted. PLease contact your Administer to grant your permissions.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Set Home';
$lang->noviceTutorial  = 'ZenTao Tutorial';
$lang->changeLog       = 'Change Log';
$lang->manual          = 'User Manual';
$lang->manualUrl       = 'https://www.zentao.pm/book/zentaomanual/zentao-installation-11.html';
$lang->customMenu      = 'Custom Menu';
$lang->customField     = 'Custom Field';
$lang->lineNumber      = 'Line No.';
$lang->tutorialConfirm = 'You are using ZenTao tutorial. Do you want to quit right now？';

$lang->preShortcutKey  = '[Shortcut:←]';
$lang->nextShortcutKey = '[Shortcut:→]';
$lang->backShortcutKey = '[Shortcut:Alt+↑]';

$lang->select        = 'Select';
$lang->selectAll     = 'Select All';
$lang->selectReverse = 'Select Inverse';
$lang->loading       = 'Loading...';
$lang->notFound      = 'Not found!';
$lang->notPage       = 'Sorry, the features you are visiting are in development!';
$lang->showAll       = '[[Show All]]';
$lang->selectedItems = 'Seleted <strong>{0}</strong> items';

$lang->future       = 'Waiting';
$lang->year         = 'Year';
$lang->workingHour  = 'Hours';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'CreatedBy';
$lang->assignedToAB = 'AssignedTo';
$lang->typeAB       = 'Type';

$lang->common = new stdclass();
$lang->common->common = 'Common Module';

/* Main menu. */
$lang->mainNav = new stdclass();
$lang->mainNav->my          = '<i class="icon icon-menu-my"></i> My|my|index|';
$lang->mainNav->program     = '<i class="icon icon-menu-project"></i> Program|program|index|';
//$lang->mainNav->reporting = '<i class="icon icon-menu-report"></i> Report|report|index|';
//$lang->mainNav->attend    = '<i class="icon icon-file"></i> Attend|attend|personal|';
$lang->mainNav->system      = '<i class="icon icon-menu-users"></i> System|custom|estimate|';
$lang->mainNav->admin       = '<i class="icon icon-menu-backend"></i> Admin|admin|index|';
//$lang->mainNav->recent    = '<i class="icon icon-menu-recent"></i> Recent|recent|index|';

$lang->reporting = new stdclass();
$lang->dividerMenu = ',admin,';

/* Scrum menu. */
$lang->menu = new stdclass();
//$lang->menu->program = 'Home|program|index';
$lang->menu->product = $lang->productCommon . '|product|index|locate=no';
$lang->menu->project = 'Iteration|project|index|locate=no';
$lang->menu->doc     = 'Doc|doc|index|';
$lang->menu->qa      = 'Qa|qa|index';
$lang->menu->company = new stdclass();

$lang->program = new stdclass();

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->subMenu = new stdclass();
$lang->system->subMenu->setmodel = new stdclass();
//$lang->system->menu->setmodel    = array('link' => 'cmmi|custom|setcmmi|', 'class' => 'dropdown dropdown-hover');
$lang->system->menu->estimate    = array('link' => 'Estimate|custom|estimate|');
$lang->system->menu->stage       = array('link' => 'Stage|stage|browse|', 'subModule' => 'stage');
$lang->system->menu->measurement = array('link' => 'Measurement|measurement|settips|', 'subModule' => 'sqlbuilder,measurement,report');
$lang->system->menu->auditcl     = array('link' => 'QA|auditcl|browse|', 'subModule' => 'auditcl');
$lang->system->menu->cmcl        = array('link' => 'Cmcl|cmcl|browse|', 'subModule' => ',cmcl,baseline,');
$lang->system->menu->process     = array('link' => 'Process|process|browse|', 'subModule' => ',activity,output,classify,');
$lang->system->menu->reviewcl    = array('link' => 'Reviewcl|reviewcl|browse|category=PP|', 'subModule' => ',reviewcl,reviewsetting,');
$lang->system->menu->subject     = array('link' => 'Subject|subject|browse|');
$lang->system->menu->holiday     = array('link' => 'Holiday|holiday|browse|');
$lang->system->subMenu->setmodel->scrum  ='scrum|custom|setscrum|';
$lang->system->dividerMenu = ',auditcl,subject,';

//if($this->cookie->systemModel == 'scrum')
//{
//    $lang->system->menu = new stdclass();
//    $lang->system->subMenu->setmodel = new stdclass();
//    $lang->system->menu->setmodel = array('link' => 'scrum|custom|setscrum|', 'class' => 'dropdown dropdown-hover');
//    $lang->system->menu->subject  = array('link' => '科目|subject|browse|');
//    $lang->system->menu->measurement = array('link' => '度量|measurement|settips|', 'subModule' => 'sqlbuilder,measurement');
//    $lang->system->menu->holiday     = array('link' => '节假日|holiday|browse|');
//    
//    $lang->system->subMenu->setmodel->cmmi  ='cmmi|custom|setcmmi|';
//    $lang->mainNav->system = '<i class="icon icon-menu-users"></i> 组织|measurement|settips|';
//    unset($lang->system->dividerMenu);
//}

$lang->stage = new stdclass();
$lang->stage->menu = new stdclass();
$lang->stage->menu->browse  = array('link' => 'Stage List|stage|browse|', 'alias' => 'create,edit,batchcreate');
$lang->stage->menu->settype = 'Stage Type|stage|settype|';

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();
$lang->measurement->menu->settips  = 'Set Tips|measurement|settips|';
$lang->measurement->menu->define   = array('link' => 'Measurement|measurement|browse|type=basic', 'alias' => 'createbasic');
$lang->measurement->menu->data     = array('link' => 'Sqlbuilder|sqlbuilder|browsesqlview|', 'subModule' => 'sqlbuilder');
$lang->measurement->menu->template = array('link' => 'Measurement|measurement|template|', 'subModule' => 'report', 'alias' => 'createtemplate');

$lang->sqlbuilder = new stdclass();
$lang->sqlbuilder->menu = $lang->measurement->menu;

$lang->cmcl = new stdclass();
$lang->cmcl->menu = new stdclass();
$lang->cmcl->menu->browse   = array('link' => 'Cmcl|cmcl|browse|', 'subModule' => 'cmcl');
$lang->cmcl->menu->catalog  = 'Catalog|baseline|catalog|';
$lang->cmcl->menu->template = array('link' => 'Template|baseline|template|', 'alias' => 'createtemplate,edittemplate,view,editbook,managebook');

$lang->baseline = new stdclass();
$lang->baseline->menu = $lang->cmcl->menu;

$lang->process = new stdclass();
$lang->process->menu = new stdclass();
$lang->process->menu->browse   = array('link' => 'Process|process|browse|', 'subModule' => 'process');
$lang->process->menu->activity = array('link' => 'Activity|activity|browse|', 'subModule' => 'activity');
$lang->process->menu->output   = array('link' => 'Output|output|browse|', 'subModule' => 'output');
$lang->process->menu->classify = array('link' => 'Classify|classify|browse|', 'subModule' => 'classify');

$lang->activity = new stdclass();
$lang->output   = new stdclass();
$lang->classify = new stdclass();
$lang->activity->menu = $lang->process->menu;
$lang->output->menu   = $lang->process->menu;
$lang->classify->menu = $lang->process->menu;

$lang->reviewcl = new stdclass();
$lang->reviewcl->menu = new stdclass();
$lang->reviewcl->menu->browse   = array('link' => 'Reviewcl|reviewcl|browse|category=PP', 'subModule' => 'reviewcl');
$lang->reviewcl->menu->version  = array('link' => 'Review Version|reviewsetting|version|');
$lang->reviewcl->menu->reviewer = array('link' => 'Reviewer|reviewsetting|reviewer|');

$lang->reviewsetting = new stdclass();
$lang->reviewsetting->menu = $lang->reviewcl->menu;

/* Object list in search form. */
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
$lang->searchObjects['testtask']    = 'Request';
$lang->searchObjects['doc']         = 'Document';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'Test Report';
$lang->searchTips                   = 'ID (ctrl+g)';

/* Code formats for import. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list for export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'All Data';
$lang->exportTypeList['selected'] = 'Selected Data';

/* Language. */
$lang->lang = 'Language';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Default';
$lang->themes['green']      = 'Green';
$lang->themes['red']        = 'Red';
$lang->themes['purple']     = 'Purple';
$lang->themes['pink']       = 'Pink';
$lang->themes['blackberry'] = 'Blackberry';
$lang->themes['classic']    = 'Classic';

/* Index menu settings. */
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "{$lang->productCommon}|product|browse";
$lang->index->menu->project = "{$lang->projectCommon}|project|browse";

/* My dashboard menu settings. */
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index          = 'Home|my|index';
//$lang->my->menu->todo         = 'Todo|my|todo|';
$lang->my->menu->effort         = 'Effort|my|effort|';
//$lang->my->menu->calendar     = array('link' => 'Calendar|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->program        = array('link' => 'Program|my|program|');
$lang->my->menu->task           = array('link' => 'Task|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => 'Request|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => 'Story|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic        = 'Dynamics|my|dynamic|';
//$lang->my->menu->profile        = array('link' => 'Profile|my|profile', 'alias' => 'editprofile');
//$lang->my->menu->changePassword = 'Password|my|changepassword';
//$lang->my->menu->manageContacts = 'Contact|my|managecontacts';
//$lang->my->menu->score          = array('link' => 'Points|my|score', 'subModule' => 'score');

$lang->my->dividerMenu = ',program,requirement,dynamic,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->score       = new stdclass();
$lang->score->menu = $lang->my->menu;

/* Product menu settings. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->story    = array('link' => 'Story|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->plan     = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release  = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap  = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->project  = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->dynamic  = 'Dynamics|product|dynamic|productID=%s';
$lang->product->menu->doc      = array('link' => 'Doc|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->menu->branch   = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module   = 'Module|tree|browse|productID=%s&view=story';
$lang->product->menu->view     = array('link' => 'Overview|product|view|productID=%s', 'alias' => 'edit');

$lang->product->dividerMenu = ',plan,project,doc,';

$lang->productplan = new stdclass();
$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->story       = new stdclass();

$lang->branch->menu      = $lang->product->menu;
$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* Project menu settings. */
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task     = array('link' => 'Task|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban   = array('link' => 'Kanban|project|kanban|projectID=%s');
$lang->project->menu->burn     = array('link' => 'Burndown|project|burn|projectID=%s');
$lang->project->menu->list     = array('link' => 'More|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story    = array('link' => 'Story|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->qa       = array('link' => 'Test|project|bug|projectID=%s', 'subModule' => 'bug,build,testtask', 'alias' => 'build,testtask', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->doc      = array('link' => 'Doc|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->action   = array('link' => 'Dynamics|project|dynamic|projectID=%s', 'subModule' => 'dynamic', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->product  = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team     = array('link' => 'Team|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->view     = array('link' => 'Overview|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');

$lang->project->subMenu = new stdclass();
$lang->project->subMenu->list = new stdclass();
$lang->project->subMenu->list->groupTask = 'Group View|project|groupTask|projectID=%s';
$lang->project->subMenu->list->tree      = 'Tree View|project|tree|projectID=%s';

$lang->project->subMenu->qa = new stdclass();
$lang->project->subMenu->qa->bug      = 'Bug|project|bug|projectID=%s';
$lang->project->subMenu->qa->build    = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->subMenu->qa->testtask = array('link' => 'Request|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');

$lang->project->dividerMenu = ',story,team,product,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA menu settings. */
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s');
$lang->qa->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->qa->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->qa->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => 'Functional Test|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => 'Unit Test|testtask|browseUnits|productID=%s');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testcase->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->testcase->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testtask->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->testtask->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase  = array('link' => 'Case|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask  = array('link' => 'Request|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testreport->menu->report    = array('link' => 'Report|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib   = array('link' => 'Case Library|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->subMenu = $lang->qa->subMenu;
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => 'Case|testcase|browse|', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask  = array('link' => 'Request|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => 'Suite|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => 'Report|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => 'Case Library|caselib|browse|libID=%s', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => 'Code|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => 'Compile|job|browse', 'subModule' => 'compile,job');
$lang->ci->menu->jenkins  = array('link' => 'Jenkins|jenkins|browse', 'alias' => 'create,edit');
$lang->ci->menu->maintain = array('link' => 'Repo|repo|maintain', 'alias' => 'create,edit');
$lang->ci->menu->rules    = array('link' => 'Rule|repo|setrules');

$lang->repo          = new stdclass();
$lang->jenkins       = new stdclass();
$lang->compile       = new stdclass();
$lang->job           = new stdclass();
$lang->repo->menu    = $lang->ci->menu;
$lang->jenkins->menu = $lang->ci->menu;
$lang->compile->menu = $lang->ci->menu;
$lang->job->menu     = $lang->ci->menu;

/* Doc menu settings. */
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->svn = new stdclass();
$lang->git = new stdclass();

/* Report menu settings. */
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual  = array('link' => 'Annual Summary|report|annualData', 'target' => '_blank');
$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => 'Request|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => 'Company|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = 'Note: The report is generated on the results of browsing the list. Click, e.g. AssignedToMe, then click Create Report to generate a report based on AssignedToMe list.';

/* Company menu settings. */
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
$lang->company->menu->browseUser  = array('link' => 'User|company|browse', 'subModule' => ',user,');
$lang->company->menu->dept        = array('link' => 'Dept|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'Group|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic     = 'Dynamic|company|dynamic|';
$lang->company->menu->view        = array('link' => 'Company|company|view');

/* Admin menu settings. */
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => 'Home|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->company   = array('link' => 'Company|company|browse|', 'subModule' => ',user,dept,group,', 'alias' => ',dynamic,view,');
$lang->admin->menu->message   = array('link' => 'Notification|message|index', 'subModule' => 'message,mail,webhook');
//$lang->admin->menu->custom    = array('link' => 'Custom|custom|set', 'subModule' => 'custom');
//$lang->admin->menu->sso       = array('link' => 'Integration|admin|sso');
//$lang->admin->menu->dev       = array('link' => 'Develop|dev|api', 'alias' => 'db', 'subModule' => 'dev,entry');
//$lang->admin->menu->translate = array('link' => 'Translate|dev|translate');
$lang->admin->menu->data      = array('link' => 'Data|backup|index', 'subModule' => 'backup,action');
$lang->admin->menu->safe      = array('link' => 'Security|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->system    = array('link' => 'System|cron|index', 'subModule' => 'cron');

$lang->company->menu = $lang->company->menu;
$lang->dept->menu    = $lang->company->menu;
$lang->group->menu   = $lang->company->menu;
$lang->user->menu    = $lang->company->menu;

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail    = array('link' => 'Mail|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => 'Browser|message|browser');
$lang->admin->subMenu->message->setting = array('link' => 'Settings|message|setting');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = 'Zdoo|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api    = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db     = array('link' => 'Database|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => 'Editor|dev|editor');
$lang->admin->subMenu->dev->entry  = array('link' => 'Application|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->data = new stdclass();
$lang->admin->subMenu->data->backup = array('link' => 'Backup|backup|index', 'subModule' => 'backup');
$lang->admin->subMenu->data->trash  = 'Recycle|action|trash';

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->cron     = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone = array('link' => 'Timezone|custom|timezone', 'subModule' => 'custom');

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

/* Menu group. */
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

$lang->navGroup->product     = 'program';
$lang->navGroup->story       = 'program';
$lang->navGroup->branch      = 'program';
$lang->navGroup->productplan = 'program';
$lang->navGroup->release     = 'program';
$lang->navGroup->tree        = 'program';
$lang->navGroup->project     = 'program';
$lang->navGroup->task        = 'program';
$lang->navGroup->qa          = 'program';
$lang->navGroup->bug         = 'program';
$lang->navGroup->doc         = 'program';
$lang->navGroup->testcase    = 'program';
$lang->navGroup->testtask    = 'program';
$lang->navGroup->testreport  = 'program';
$lang->navGroup->testsuite   = 'program';
$lang->navGroup->caselib     = 'program';
$lang->navGroup->feedback    = 'program';
$lang->navGroup->deploy      = 'program';

$lang->navGroup->programplan    = 'program';
$lang->navGroup->workestimation = 'program';
$lang->navGroup->budget         = 'program';
$lang->navGroup->review         = 'program';
$lang->navGroup->reviewissue    = 'program';
$lang->navGroup->weekly         = 'program';
$lang->navGroup->milestone      = 'program';
$lang->navGroup->pssp           = 'program';
$lang->navGroup->design         = 'program';
$lang->navGroup->repo           = 'program';
$lang->navGroup->issue          = 'program';
$lang->navGroup->risk           = 'program';
$lang->navGroup->auditplan      = 'program';
$lang->navGroup->cm             = 'program';
$lang->navGroup->nc             = 'program';
$lang->navGroup->job            = 'program';
$lang->navGroup->jenkins        = 'program';
$lang->navGroup->compile        = 'program';

$lang->navGroup->durationestimation = 'program';

$lang->navGroup->stage         = 'system';
$lang->navGroup->measurement   = 'system';
$lang->navGroup->report        = 'system';
$lang->navGroup->sqlbuilder    = 'system';
$lang->navGroup->auditcl       = 'system';
$lang->navGroup->cmcl          = 'system';
$lang->navGroup->process       = 'system';
$lang->navGroup->activity      = 'system';
$lang->navGroup->output        = 'system';
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

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "The domain %s cannot be found!";
$lang->error->length          = array("『%s』length error. It should be『%s』", "『%s』length should be <=『%s』and >『%s』.");
$lang->error->reg             = "『%s』format error. It should be『%s』.";
$lang->error->unique          = "『%s』『%s』exists. Go to Admin->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->error->gt              = "『%s』should be >『%s』.";
$lang->error->ge              = "『%s』should be >=『%s』.";
$lang->error->notempty        = "『%s』should not be blank.";
$lang->error->empty           = "『%s』should be null.";
$lang->error->equal           = "『%s』has to be『%s』.";
$lang->error->int             = array("『%s』should be numbers", "『%s』should be 『%s-%s』.");
$lang->error->float           = "『%s』should have numbers, or decimals.";
$lang->error->email           = "『%s』should be valid Email.";
$lang->error->URL             = "『%s』should be url.";
$lang->error->date            = "『%s』should be valid date.";
$lang->error->datetime        = "『%s』should be valid date.";
$lang->error->code            = "『%s』should be letters or numbers.";
$lang->error->account         = "『%s』should be >= 3 letters or numbers.";
$lang->error->passwordsame    = "Passwords should be consistent.";
$lang->error->passwordrule    = "Password should conform to rules. It should be >= 6 characters.";
$lang->error->accessDenied    = 'Access is denied.';
$lang->error->pasteImg        = 'Images are not allowed to be pasted in your browser!';
$lang->error->noData          = 'No data.';
$lang->error->editedByOther   = 'This record might have been changed. Please refresh and try to edit again!';
$lang->error->tutorialData    = 'No data can be imported in tutorial mode. Please quit tutorial first!';
$lang->error->noCurlExt       = 'No Curl module installed';

/* Page info. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No records.";
$lang->pager->digest       = "Total: <strong>%s</strong>. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = " <strong>%s</strong> per page";
$lang->pager->first        = "<i class='icon-step-backward' title='Home'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='Previous Page'></i>";
$lang->pager->next         = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate       = "Go!";
$lang->pager->previousPage = "Prev";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong>.";
$lang->pager->pageOfText   = "Page {0}";
$lang->pager->firstPage    = "First";
$lang->pager->lastPage     = "Last";
$lang->pager->goto         = "Goto";
$lang->pager->pageOf       = "Page <strong>{page}</strong>";
$lang->pager->totalPage    = "<strong>{totalPage}</strong> pages";
$lang->pager->totalCount   = "Total: <strong>{recTotal}</strong> items";
$lang->pager->pageSize     = "<strong>{recPerPage}</strong> per page";
$lang->pager->itemsRange   = "From <strong>{start}</strong> to <strong>{end}</strong>";
$lang->pager->pageOfTotal  = "Page <strong>{page}</strong> of <strong>{totalPage}</strong>";

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = 'Not a valid color value';

$lang->proVersion     = "<a href='https://www.zentao.pm/book/zentaopromanual/free-open-source-project-management-software-zentaopro-127.html' target='_blank' id='proLink' class='text-important'>ZenTao Pro <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "Download Desktop Notification";
$lang->downloadClient = "Download ZenTao Desktop";
$lang->clientHelp     = "Client Help";
$lang->clientHelpLink = "https://www.zentao.pm/book/zentaomanual/scrum-tool-im-integration-206.html";
$lang->website        = "https://www.zentao.pm";

$lang->suhosinInfo     = "Warning! Data is reaching the limit. Please change <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->maxVarsInfo     = "Warning! Data is reaching the limit. Please change <font color=red>max_input_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->pasteTextInfo   = "Paste the text here. Each line will be a title. ";
$lang->noticeImport    = "Imported data contains data that has already existed in system. Please confirm you actions on the date.";
$lang->importConfirm   = "Import";
$lang->importAndCover  = "Override";
$lang->importAndInsert = "Insert";

$lang->noResultsMatch     = "No results match found!";
$lang->searchMore         = "More results：";
$lang->chooseUsersToMail  = "Choose users that will be notified.";
$lang->browserNotice      = 'The browser you currently use might not get the best browsing results. It is recommended that you use Chrome, Firefox, IE9+, Opera or Safari.';
$lang->noticePasteImg     = "You can paste images into the editor.";
$lang->pasteImgFail       = "Failed to paste images. Try again later.";
$lang->pasteImgUploading  = "Uploading...";

/* Time formats settings. */
if(!defined('DT_DATETIME1')) define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2')) define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1'))define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2'))define('DT_MONTHTIME2', 'n/d H:i');
if(!defined('DT_DATE1'))     define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))     define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))     define('DT_DATE3',     'Y/m/d');
if(!defined('DT_DATE4'))     define('DT_DATE4',     'M d');
if(!defined('DT_DATE5'))     define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))     define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))     define('DT_TIME2',     'H:i');

/* Datepicker. */
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
$lang->datepicker->dpText->TEXT_DATE        = '';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Choose Date';

$lang->datepicker->dayNames     = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$lang->datepicker->abbrDayNames = array('Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat');
$lang->datepicker->monthNames   = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

/* Common action icons. */
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
    unset($lang->menuOrder[35]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->project);
    unset($lang->product->menu->doc);

    /* Rename product module. */
    $lang->menu->product = "{$lang->productCommon}|product|index";

    /* Adjust search items. */
    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['testtask']);
    unset($lang->searchObjects['testsuite']);
    unset($lang->searchObjects['caselib']);
    unset($lang->searchObjects['testreport']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTask')
{
    /* Remove product, report and qa module. */
    unset($lang->menu->product);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[10]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->story);

    /* Adjust sub menu of project  module. */
    unset($lang->project->menu->story);
    unset($lang->project->menu->build);
    unset($lang->project->menu->qa);
    unset($lang->project->menu->product);
    unset($lang->project->menu->doc);

    /* Remove sub menu of product module. */
    unset($lang->product->menu);
    unset($lang->product->menuOrder);

    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['product']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
    unset($lang->searchObjects['testsuite']);
    unset($lang->searchObjects['caselib']);
    unset($lang->searchObjects['testreport']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTest')
{
    /* Remove project and test module. */
    unset($lang->menu->project);
    unset($lang->menu->qa);
    unset($lang->menu->report);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Rename product module. */
    $lang->menu->product = "{$lang->productCommon}|product|index";

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);
    unset($lang->my->menu->story);

    /* Remove sub menu of project module. */
    unset($lang->project->menu);
    unset($lang->project->menuOrder);
    $lang->project->menu = new stdclass();
    $lang->project->menu->list = array('alias' => '');

    /* Add bug, testcase and testtask module. */
    $lang->menu->bug       = 'Bug|bug|index';
    $lang->menu->testcase  = 'Functional Test|testcase|index';
    $lang->menu->unit      = 'Unit Test|testtask|browseUnits';
    $lang->menu->testsuite = 'Suite|testsuite|index';
    $lang->menu->testtask  = 'Request|testtask|index';
    $lang->menu->caselib   = 'Case Library|caselib|browse';

    $lang->menuOrder[6]  = 'bug';
    $lang->menuOrder[7]  = 'testcase';
    $lang->menuOrder[8]  = 'unit';
    $lang->menuOrder[9]  = 'testsuite';
    $lang->menuOrder[10] = 'testtask';
    $lang->menuOrder[11] = 'caselib';
    $lang->menuOrder[12] = 'product';

    /* Adjust sub menu of bug module. */
    $lang->bug->menu = new stdclass();
    $lang->bug->menu->all           = 'All|bug|browse|productID=%s&branch=%s&browseType=all&param=%s';
    $lang->bug->menu->unclosed      = 'Unclosed|bug|browse|productID=%s&branch=%s&browseType=unclosed&param=%s';
    $lang->bug->menu->openedbyme    = 'ReportedByMe|bug|browse|productID=%s&branch=%s&browseType=openedbyme&param=%s';
    $lang->bug->menu->assigntome    = 'AssignedToMe|bug|browse|productID=%s&branch=%s&browseType=assigntome&param=%s';
    $lang->bug->menu->resolvedbyme  = 'ResolvedByMe|bug|browse|productID=%s&branch=%s&browseType=resolvedbyme&param=%s';
    $lang->bug->menu->toclosed      = 'ToBeClosed|bug|browse|productID=%s&branch=%s&browseType=toclosed&param=%s';
    $lang->bug->menu->unresolved    = 'Active|bug|browse|productID=%s&branch=%s&browseType=unresolved&param=%s';
    $lang->bug->menu->more          = array('link' => 'More|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s', 'class' => 'dropdown dropdown-hover');

    $lang->bug->subMenu = new stdclass();
    $lang->bug->subMenu->more = new stdclass();
    $lang->bug->subMenu->more->unconfirmed   = 'Unconfirmed|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s';
    $lang->bug->subMenu->more->assigntonull  = 'Unassigned|bug|browse|productID=%s&branch=%s&browseType=assigntonull&param=%s';
    $lang->bug->subMenu->more->longlifebugs  = 'Stalled|bug|browse|productID=%s&branch=%s&browseType=longlifebugs&param=%s';
    $lang->bug->subMenu->more->postponedbugs = 'Postponed|bug|browse|productID=%s&branch=%s&browseType=postponedbugs&param=%s';
    $lang->bug->subMenu->more->overduebugs   = 'Overdue|bug|browse|productID=%s&branch=%s&browseType=overduebugs&param=%s';
    $lang->bug->subMenu->more->needconfirm   = 'ToBeConfirmed|bug|browse|productID=%s&branch=%s&browseType=needconfirm&param=%s';

    $lang->bug->menuOrder[5]  = 'product';
    $lang->bug->menuOrder[10] = 'all';
    $lang->bug->menuOrder[15] = 'unclosed';
    $lang->bug->menuOrder[20] = 'openedbyme';
    $lang->bug->menuOrder[25] = 'assigntome';
    $lang->bug->menuOrder[30] = 'resolvedbyme';
    $lang->bug->menuOrder[35] = 'toclosed';
    $lang->bug->menuOrder[40] = 'unresolved';
    $lang->bug->menuOrder[45] = 'unconfirmed';
    $lang->bug->menuOrder[50] = 'assigntonull';
    $lang->bug->menuOrder[55] = 'longlifebugs';
    $lang->bug->menuOrder[60] = 'postponedbugs';
    $lang->bug->menuOrder[65] = 'overduebugs';
    $lang->bug->menuOrder[70] = 'needconfirm';

    /* Adjust sub menu of testcase. */
    $lang->testcase->menu = new stdclass();
    $lang->testcase->menu->all     = 'All|testcase|browse|productID=%s&branch=%s&browseType=all';
    $lang->testcase->menu->wait    = 'Waiting|testcase|browse|productID=%s&branch=%s&browseType=wait';
    $lang->testcase->menu->bysuite = array('link' => 'Suite|testsuite|create|productID=%s', 'class' => 'dropdown dropdown-hover');

    $lang->testcase->subMenu = new stdclass();
    $lang->testcase->subMenu->bysuite = new stdclass();
    $lang->testcase->subMenu->bysuite->create = 'Create Suite|testsuite|create|productID=%s';

    $lang->testcase->menuOrder[5]  = 'product';
    $lang->testcase->menuOrder[10] = 'all';
    $lang->testcase->menuOrder[15] = 'wait';
    $lang->testcase->menuOrder[20] = 'suite';

    /* Adjust sub menu of bug module. */
    $lang->testsuite->menu = new stdclass();

    $lang->testsuite->menuOrder[5]  = 'product';

    /* Adjust sub menu of testtask. */
    $lang->testtask->menu = new stdclass();
    $lang->testtask->menu->totalStatus = 'All|testtask|browse|productID=%s&branch=%s&type=%s,totalStatus';
    $lang->testtask->menu->wait        = 'Waiting|testtask|browse|productID=%s&branch=%s&type=%s,wait';
    $lang->testtask->menu->doing       = 'Doing|testtask|browse|productID=%s&branch=%s&type=%s,doing';
    $lang->testtask->menu->blocked     = 'Blocked|testtask|browse|productID=%s&branch=%s&type=%s,blocked';
    $lang->testtask->menu->done        = 'Done|testtask|browse|productID=%s&branch=%s&type=%s,done';
    $lang->testtask->menu->report      = array('link' => 'Report|testreport|browse');

    $lang->testtask->menuOrder[5]  = 'product';
    $lang->testtask->menuOrder[10] = 'scope';
    $lang->testtask->menuOrder[15] = 'totalStatus';
    $lang->testtask->menuOrder[20] = 'wait';
    $lang->testtask->menuOrder[25] = 'doing';
    $lang->testtask->menuOrder[30] = 'blocked';
    $lang->testtask->menuOrder[35] = 'done';
    $lang->testtask->menuOrder[40] = 'report';

    $lang->testreport->menu      = $lang->testtask->menu;
    $lang->testreport->menuOrder = $lang->testtask->menuOrder;

    /* Adjust sub menu of caselib module. */
    $lang->caselib->menu = new stdclass();
    $lang->caselib->menu->all  = 'All|caselib|browse|libID=%s&browseType=all';
    $lang->caselib->menu->wait = 'Waiting|caselib|browse|libID=%s&browseType=wait';
    $lang->caselib->menu->view = 'View|caselib|view|libID=%s';

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
    $lang->menugroup->case       = 'testcase';
    $lang->menugroup->testtask   = 'testtask';
    $lang->menugroup->testsuite  = 'testsuite';
    $lang->menugroup->caselib    = 'caselib';
    $lang->menugroup->testreport = 'testtask';
    $lang->menugroup->build      = 'product';

    /* Adjust search objects. */
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['productplan']);
}

/* Cmmi menu. */
$lang->menu->cmmi = new stdclass();
$lang->menu->cmmi->workestimation = array('link' => 'Estimation|workestimation|index|program={PROGRAM}', 'subModule' => 'durationestimation,budget');
$lang->menu->cmmi->programplan    = array('link' => 'Programplan|programplan|browse|program={PROGRAM}', 'subModule' => 'programplan');
$lang->menu->cmmi->project        = array('link' => $lang->projectCommon . '|project|task|projectID={PROJECT}', 'subModule' => ',project,task,');
$lang->menu->cmmi->review         = array('link' => 'Review|review|browse|program={PROGRAM}', 'subModule' => ',reviewissue,');
$lang->menu->cmmi->weekly         = array('link' => 'Weekly|weekly|index|program={PROGRAM}', 'subModule' => ',milestone,');
$lang->menu->cmmi->doc            = array('link' => 'Doc|doc|index|program={PROGRAM}');
$lang->menu->cmmi->product        = array('link' => 'Story|product|browse|product={PRODUCT}&branch=&browseType=unclosed&queryID=0&storyType=requirement', 'subModule' => ',story,');
$lang->menu->cmmi->design         = 'Design|design|browse|product={PRODUCT}';
$lang->menu->cmmi->ci             = 'Ci|repo|browse|';
$lang->menu->cmmi->qa             = array('link' => 'QA|bug|browse|product={PRODUCT}', 'subModule' => ',testcase,testtask,testsuite,testreport,caselib,');
$lang->menu->cmmi->release        = array('link' => 'Release|release|browse|product={PRODUCT}', 'subModule' => 'release');
$lang->menu->cmmi->issue          = 'Issue|issue|browse|';
$lang->menu->cmmi->risk           = 'Risk|risk|browse|';
$lang->menu->cmmi->report         = array('link' => 'Measurement|report|programsummary|program={PROGRAM}', 'subModule' => ',report,');
$lang->menu->cmmi->auditplan      = array('link' => 'Audit|auditplan|browse|', 'subModule' => 'nc');
$lang->menu->cmmi->cm             = array('link' => 'CM|cm|browse|program={PROGRAM}', 'subModule' => 'cm');
$lang->menu->cmmi->pssp           = 'Process|pssp|browse|program={PROGRAM}';

$lang->cmmiproduct    = new stdclass();
$lang->workestimation = new stdclass();
$lang->budget         = new stdclass();
$lang->programplan    = new stdclass();
$lang->review         = new stdclass();
$lang->weekly         = new stdclass();
$lang->milestone      = new stdclass();
$lang->design         = new stdclass();
$lang->auditplan      = new stdclass();
$lang->cm             = new stdclass();
$lang->nc             = new stdclass();
$lang->pssp           = new stdclass();
$lang->issue          = new stdclass();
$lang->risk           = new stdclass();
$lang->durationestimation = new stdclass();

$lang->workestimation->menu = new stdclass();
$lang->budget->menu         = new stdclass();
$lang->programplan->menu    = new stdclass();
$lang->review->menu         = new stdclass();
$lang->weekly->menu         = new stdclass();
$lang->milestone->menu      = new stdclass();
$lang->cmmiproduct->menu    = new stdclass();
$lang->design->menu         = new stdclass();
$lang->auditplan->menu      = new stdclass();
$lang->cm->menu             = new stdclass();
$lang->pssp->menu           = new stdclass();
$lang->issue->menu          = new stdclass();
$lang->risk->menu           = new stdclass();
$lang->durationestimation->menu = new stdclass();

$lang->workestimation->menu->index    = 'Workestimation|workestimation|index|program={PROGRAM}';
$lang->workestimation->menu->duration = array('link' => 'Durationestimation|durationestimation|index|program={PROGRAM}', 'subModule' => 'durationestimation');
$lang->workestimation->menu->budget   = array('link' => 'Budget|budget|summary|', 'subModule' => 'budget');

$lang->durationestimation->menu = $lang->workestimation->menu;
$lang->budget->menu = $lang->workestimation->menu;

$lang->programplan->menu->gantt = array('link' => 'Gantt|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=gantt');
$lang->programplan->menu->lists = 'Stage|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=lists';

$lang->review->menu->review = array('link' => 'Reivew|review|browse|program={PROGRAM}', 'subModule' => 'review');
$lang->review->menu->issue  = array('link' => 'Reviewissue|reviewissue|issue|program={PROGRAM}', 'subModule' => 'reviewissue');

$lang->reviewissue = new stdclass();
$lang->reviewissue->menu = $lang->review->menu;

$lang->weekly->menu->browse = 'Weekly|weekly|index|program={PROGRAM}';
$lang->weekly->menu->issue  = 'Milestone|milestone|index|program={PROGRAM}';

$lang->cmmiproduct->menu->requirement = 'User Story|product|browse|product={PRODUCT}&branch=&browseType=unclosed&queryID=0&storyType=requirement';
$lang->cmmiproduct->menu->story       = 'Software Story|product|browse|product={PRODUCT}&branch=&browseType=unclosed&queryID=0&storyType=story';
$lang->cmmiproduct->menu->track       = 'Track|story|track|product={PRODUCT}';

$lang->auditplan->menu->browse = array('link' => 'Auditplan|auditplan|browse|', 'alias' => 'create,edit');
$lang->auditplan->menu->nc     = array('link' => 'NC|nc|browse|program={PROGRAM}', 'subModule' => 'nc');

$lang->story->menu         = $lang->cmmiproduct->menu;
$lang->milestone->menu     = $lang->weekly->menu;
$lang->nc->menu            = $lang->auditplan->menu;

$lang->cm->menu->browse = array('link' => 'Baseline|cm|browse|program={PROGRAM}', 'alias' => 'create,edit');
$lang->cm->menu->report = 'Report|cm|report|program={PROGRAM}';

$lang->noMenuModule     = array('my', 'todo', 'effort', 'program', 'report', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'holiday', 'custom', 'auditcl', 'subject', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'issue', 'risk', 'pssp', 'sms', 'message', 'webhook', 'search');
$lang->haveMenuMethod   = array('custom');
