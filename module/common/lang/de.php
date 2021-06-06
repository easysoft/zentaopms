<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: de.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '-';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' am ';
$lang->downArrow = '↓';
$lang->null      = 'Null';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';

$lang->zentaoPMS       = 'ZenTao';
$lang->logoImg         = 'zt-logo-en.png';
$lang->welcome         = "%s PMS";
$lang->logout          = 'Abmelden';
$lang->login           = 'Anmelden';
$lang->help            = 'Hilfe';
$lang->aboutZenTao     = 'ZenTao';
$lang->profile         = 'Profil';
$lang->changePassword  = 'Passwort';
$lang->unfoldMenu      = 'Unfold Menu';
$lang->collapseMenu    = 'Collapse Menu';
$lang->preference      = 'Preference';
$lang->runInfo         = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement       = "I have read and agreed to the terms and conditions of <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Without authorization, I should not remove, hide or cover any logos/links of ZenTao.</span>";
$lang->designedByAIUX  = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> AIUX</a>";

$lang->reset        = 'Zurücksetzen';
$lang->cancel       = 'Abbrechen';
$lang->refresh      = 'Aktualisieren';
$lang->edit         = 'Bearbeiten';
$lang->delete       = 'Löschen';
$lang->close        = 'Schließen';
$lang->unlink       = 'Entfernen';
$lang->import       = 'Importieren';
$lang->export       = 'Exportieren';
$lang->setFileName  = 'Dateiame';
$lang->submitting   = 'Speichern...';
$lang->save         = 'Speichern';
$lang->saveSuccess  = 'Gespeichert.';
$lang->confirm      = 'Bestätigen';
$lang->preview      = 'Ansehen';
$lang->goback       = 'Zurück';
$lang->goPC         = 'PC';
$lang->more         = 'Mehr';
$lang->moreLink     = 'MORE';
$lang->day          = 'Tag';
$lang->customConfig = 'Custom Config';
$lang->public       = 'Öffentlich';
$lang->trunk        = 'Trunk';
$lang->sort         = 'Sortieren';
$lang->required     = 'Pflicht';
$lang->noData       = 'Kein Datensatz';
$lang->fullscreen   = 'Fullscreen';
$lang->retrack      = 'Retrack';
$lang->whitelist    = 'Weiße Liste';

$lang->actions         = 'Aktionen';
$lang->restore         = 'Wiederherstellen';
$lang->comment         = 'Notiz';
$lang->history         = 'Historie';
$lang->attatch         = 'Anlagen';
$lang->reverse         = 'Umkehren';
$lang->switchDisplay   = 'Umschalten';
$lang->expand          = 'Alle aufklappen';
$lang->collapse        = 'Zuklappen';
$lang->saveSuccess     = 'Gespeichert';
$lang->fail            = 'Fehlgeschlagen';
$lang->addFiles        = 'Hinzufügen';
$lang->files           = 'Datei ';
$lang->pasteText       = 'Einfügen';
$lang->uploadImages    = 'Hochladen';
$lang->timeout         = 'Timeout. Bitte prüfen Sie Ihre Netzwerkverbindung oder versuchen Sie es erneut!';
$lang->repairTable     = 'Die Datenbank scheint koruppt zu sein. Bitte prüfen Sie die Datenbank.';
$lang->duplicate       = '%s existiert bereits.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, die IP Adresse wurde eingeschränkt. Bitte kontaktieren Sie den Administrator.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Als Startseite setzen';
$lang->noviceTutorial  = 'Anleitung';
$lang->changeLog       = 'Änderungsprotokoll';
$lang->manual          = 'Handbuch';
$lang->customMenu      = 'Benutzer Menü';
$lang->customField     = 'Individualfeld';
$lang->lineNumber      = 'Zeile Nr.';
$lang->tutorialConfirm = 'Sie benutzen die Anleitung. Möchten Sie diese jetzt verlassen？';

$lang->preShortcutKey  = '[Shortcut:←]';
$lang->nextShortcutKey = '[Shortcut:→]';
$lang->backShortcutKey = '[Shortcut:Alt+↑]';

$lang->select        = 'Auswählen';
$lang->selectAll     = 'Alle';
$lang->selectReverse = 'Auswahl umkehren';
$lang->loading       = 'Lade...';
$lang->notFound      = 'Nicht gefunden!';
$lang->notPage       = 'Sorry, the features you are visiting are in development!';
$lang->showAll       = '[[Alle anzeigen]]';
$lang->selectedItems = 'Seleted <strong>{0}</strong> items';

$lang->future      = 'Warte';
$lang->year        = 'Jahr';
$lang->workingHour = 'Stunde';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Status';
$lang->openedByAB   = 'Ersteller';
$lang->assignedToAB = 'Bearbeiter';
$lang->typeAB       = 'Typ';
$lang->nameAB       = 'Name';

$lang->common = new stdclass();
$lang->common->common = 'Standard Module';

global $config;
list($programModule, $programMethod)     = explode('-', $config->programLink);
list($productModule, $productMethod)     = explode('-', $config->productLink);
list($projectModule, $projectMethod)     = explode('-', $config->projectLink);
list($executionModule, $executionMethod) = explode('-', $config->executionLink);

/* Main menu. */
$lang->mainNav = new stdclass();
$lang->mainNav->my      = '<i class="icon icon-menu-my"></i> My|my|index|';
$lang->mainNav->product = "<i class='icon icon-menu-product'></i> Product|$productModule|$productMethod|";
if($config->systemMode == 'new')
{
    $lang->mainNav->project   = "<i class='icon icon-project'></i> Project|$projectModule|$projectMethod|";
    $lang->mainNav->execution = "<i class='icon icon-run'></i> Execution|$executionModule|$executionMethod|";
}
else
{
    $lang->mainNav->execution = "<i class='icon icon-run'></i> $lang->executionCommon|$executionModule|$executionMethod|";
}
$lang->mainNav->qa      = '<i class="icon icon-test"></i> Test|qa|index|';
$lang->mainNav->repo    = '<i class="icon icon-code1"></i> Code|repo|browse|';
$lang->mainNav->doc     = '<i class="icon icon-doc"></i> Doc|doc|index|';
$lang->mainNav->report  = "<i class='icon icon-statistic'></i> Statistic|report|productsummary|";
$lang->mainNav->system  = '<i class="icon icon-menu-users"></i> System|company|browse|';
$lang->mainNav->admin   = '<i class="icon icon-menu-backend"></i> Admin|admin|index|';
if($config->systemMode == 'new') $lang->mainNav->program = "<i class='icon icon-program'></i> Program|$programModule|$programMethod|";

$lang->dividerMenu = ',qa,report,admin,';

/* Program set menu. */
$lang->program = new stdclass();
$lang->program->menu = new stdclass();
$lang->program->menu->index   = 'Dashboard|program|index|';
$lang->program->menu->browse  = array('link' => 'Program|program|browse|', 'alias' => 'create,edit,group,managepriv,manageview,managemembers');

$lang->project = new stdclass();
$lang->project->menu = new stdclass();
if($config->systemMode == 'new')
{
    $lang->project->menu->browse = array('link' => 'Project|project|browse|');
}
else
{
    $lang->project->menu->browse = array('link' => "$lang->executionCommon|project|browse|");
}

$lang->project->dividerMenu = ',execution,programplan,doc,dynamic,';

$lang->program->viewMenu = new stdclass();
$lang->program->viewMenu->product     = array('link' => 'Product|program|product|program=%s', 'alias' => 'view');
$lang->program->viewMenu->project     = array('link' => "Project|program|project|program=%s");
$lang->program->viewMenu->personnel   = array('link' => "Member|personnel|invest|program=%s");
$lang->program->viewMenu->stakeholder = array('link' => "Stakeholder|program|stakeholder|program=%s", 'alias' => 'createstakeholder');

$lang->personnel = new stdClass();
$lang->personnel->menu = new stdClass();
$lang->personnel->menu->invest     = array('link' => "Investment|personnel|invest|program=%s");
$lang->personnel->menu->accessible = array('link' => "Accessible|personnel|accessible|program=%s");
$lang->personnel->menu->whitelist  = array('link' => "Whitelist|personnel|whitelist|program=%s", 'alias' => 'addwhitelist');

/* Scrum menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->home = 'Dashboard|product|index|';
$lang->product->menu->list = array('link' => $lang->productCommon . '|product|all|', 'alias' => 'create,batchedit');

$lang->product->viewMenu = new stdclass();
$lang->product->viewMenu->dashboard   = array('link' => 'Dashboard|product|dashboard|productID=%s');
if($config->URAndSR) $lang->product->viewMenu->requirement = array('link' => "$lang->URCommon|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->story       = array('link' => "$lang->SRCommon|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->plan        = array('link' => "Plan|productplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->product->viewMenu->release     = array('link' => "Release|release|browse|productID=%s",     'subModule' => 'release');
$lang->product->viewMenu->roadmap     = 'Roadmap|product|roadmap|productID=%s';
$lang->product->viewMenu->project     = "Project|product|project|status=all&productID=%s";
$lang->product->viewMenu->track       = array('link' => "Track|story|track|productID=%s");
$lang->product->viewMenu->doc         = array('link' => 'Doc|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->viewMenu->dynamic     = 'Dynamic|product|dynamic|productID=%s';
$lang->product->viewMenu->set         = array('link' => 'Setting|product|view|productID=%s', 'subModule' => 'tree,branch', 'alias' => 'edit');

$lang->product->setMenu = new stdclass();
$lang->product->setMenu->view      = array('link' => 'View|product|view|productID={PRODUCT}', 'alias' => 'edit');
$lang->product->setMenu->module    = array('link' => 'Module|tree|browse|product={PRODUCT}&view=story', 'subModule' => 'tree');
$lang->product->setMenu->branch    = array('link' => '@branch@|branch|manage|product={PRODUCT}', 'subModule' => 'branch');
$lang->product->setMenu->whitelist = array('link' => 'Whitelist|product|whitelist|product={PRODUCT}', 'subModule' => 'personnel');

$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->productplan = new stdclass();

$lang->release->menu     = $lang->product->viewMenu;
$lang->branch->menu      = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->company  = array('link' => 'User|company|browse|', 'subModule' => ',user,dept,group,', 'alias' => ',dynamic,view,');
$lang->system->menu->team     = array('link' => 'Team|my|team|');
$lang->system->menu->calendar = array('link' => 'Calendar|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();

$lang->searchTips = '';
$lang->searchAB   = 'Search';

/* Object list in search form. */
$lang->searchObjects['all']         = 'All';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Aufgaben';
$lang->searchObjects['testcase']    = 'Fall';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . 'Plan';
$lang->searchObjects['testtask']    = 'Test Aufgabe';
$lang->searchObjects['doc']         = 'Doc';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'Test-Bericht';
$lang->searchObjects['program']     = 'Program';
$lang->searchObjects['project']     = 'Project';
$lang->searchObjects['execution']   = $lang->executionCommon;
$lang->searchObjects['user']        = 'User';
$lang->searchTips                   = 'ID (strg+g)';

/* Code formats for import. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list for export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'Alle';
$lang->exportTypeList['selected'] = 'Ausgewählte';

/* Language. */
$lang->lang = 'Sprache';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'ZenTao Blau (Standard)';
$lang->themes['green']      = 'Grün';
$lang->themes['red']        = 'Rot';
$lang->themes['purple']     = 'Lila';
$lang->themes['pink']       = 'Pink';
$lang->themes['blackberry'] = 'Dunkelblau';
$lang->themes['classic']    = 'Klassisch';

/* Index menu settings. */
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "{$lang->productCommon}|product|browse";
$lang->index->menu->project = "{$lang->executionCommon}|project|browse";

/* My dashboard menu settings. */
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index       = 'Index|my|index';
$lang->my->menu->myWork      = array('link' => 'Work|my|work|mode=task');
if($config->systemMode == 'new')
{
    $lang->my->menu->myProject   = array('link' => 'Project|my|project|');
    $lang->my->menu->myExecution = 'Execution|my|execution|type=undone';
}
else
{
    $lang->my->menu->myExecution = $lang->executionCommon . '|my|execution|type=undone';
}
$lang->my->menu->contribute  = array('link' => 'Contribute|my|contribute|mode=task');
$lang->my->menu->dynamic     = 'Dynamic|my|dynamic|';
$lang->my->menu->score       = 'Score|my|score|';
$lang->my->menu->contacts    = 'Contacts|my|managecontacts|';

$lang->my->workMenu = new stdclass();
$lang->my->workMenu->task        = 'Task|my|work|mode=task';
if($config->URAndSR) $lang->my->workMenu->requirement = "$lang->URCommon|my|work|mode=requirement";
$lang->my->workMenu->story       = "$lang->SRCommon|my|work|mode=story";
$lang->my->workMenu->bug         = 'Bug|my|work|mode=bug';
$lang->my->workMenu->testcase    = 'Test Case|my|work|mode=testcase&type=assigntome';
$lang->my->workMenu->testtask    = 'Test Task|my|work|mode=testtask&type=wait';

$lang->my->contributeMenu = new stdclass();
$lang->my->contributeMenu->task     = 'Task|my|contribute|mode=task';
if($config->URAndSR) $lang->my->contributeMenu->requirement = "$lang->URCommon|my|contribute|mode=requirement";
$lang->my->contributeMenu->story    = "$lang->SRCommon|my|contribute|mode=story";
$lang->my->contributeMenu->bug      = 'Bug|my|contribute|mode=bug';
$lang->my->contributeMenu->testcase = 'Test Case|my|contribute|mode=testtask&type=openedbyme';
$lang->my->contributeMenu->testtask = 'Test Task|my|contribute|mode=testtask&type=done';
$lang->my->contributeMenu->doc      = 'Doc|my|contribute|mode=doc&type=openedbyme';

$lang->my->dividerMenu = ',myWork,score,';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->product->dividerMenu = $config->URAndSR ? ',requirement,set,' : ',track,set,';

$lang->story = new stdclass();

$lang->story->menu = $lang->product->menu;

/* Project menu settings. */
$lang->execution = new stdclass();
$lang->execution->menu = new stdclass();

$lang->execution->menu->task     = array('link' => 'Aufgabenliste|execution|task|executionID=%s', 'subModule' => 'task,grouptask,tree', 'alias' => 'grouptask,importtask,importbug,tree', 'class' => 'dropdown dropdown-hover');
$lang->execution->menu->kanban   = array('link' => 'Kanban|execution|kanban|executionID=%s');
$lang->execution->menu->burn     = array('link' => 'Burn|execution|burn|executionID=%s');
$lang->execution->menu->view     = array('link' => 'View|execution|grouptask|executionID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->execution->menu->story    = array('link' => 'Story|execution|story|executionID=%s', 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->execution->menu->bug      = array('link' => 'Bug|execution|bug|executionID=%s');
$lang->execution->menu->build    = array('link' => 'Build|execution|build|executionID=%s', 'subModule' => 'build');
$lang->execution->menu->testtask = array('link' => 'Test Aufgabe|execution|testtask|executionID=%s', 'subModule' => 'testreport,testtask');
$lang->execution->menu->doc      = array('link' => 'Dok|doc|objectLibs|type=execution&objectID=%s&from=execution', 'subModule' => 'doc');
$lang->execution->menu->product  = $lang->productCommon . '|execution|manageproducts|executionID=%s';
$lang->execution->menu->team     = array('link' => 'Team|execution|team|executionID=%s', 'alias' => 'managemembers');
$lang->execution->menu->more     = array('link' => 'More|execution|whitelist|executionID=%s', 'subModule' => 'personnel', 'alias' => 'edit', 'class' => 'dropdown dropdown-hover');

$lang->execution->subMenu = new stdclass();
$lang->execution->subMenu->view = new stdclass();
$lang->execution->subMenu->view->groupTask = 'Gruppenansicht|execution|grouptask|executionID=%s';
$lang->execution->subMenu->view->tree      = 'Baumansicht|execution|tree|executionID=%s';

$lang->execution->subMenu->qa = new stdclass();
$lang->execution->subMenu->qa->bug      = 'Bug|execution|bug|executionID=%s';
$lang->execution->subMenu->qa->build    = array('link' => 'Build|execution|build|executionID=%s', 'subModule' => 'build');
$lang->execution->subMenu->qa->testtask = array('link' => 'Test Aufgabe|execution|testtask|executionID=%s', 'subModule' => 'testreport,testtask', 'alias' => 'create');

$lang->execution->subMenu->more = new stdclass();
$lang->execution->subMenu->more->whitelist = array('link' => 'Whitelist|execution|whitelist|executionID=%s', 'subModule' => 'personnel', 'alias' => 'addwhitelist');
$lang->execution->subMenu->more->action    = array('link' => 'Verlauf|execution|dynamic|executionID=%s');
$lang->execution->subMenu->more->view      = array('link' => 'Übersicht|execution|view|executionID=%s', 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');

$lang->execution->dividerMenu = ',execution,programplan,executionbuild,story,doc,other,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->execution->menu;
$lang->build->menu = $lang->execution->menu;

/* QA视图菜单设置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->index     = array('link' => 'Index|qa|index');
$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib');
$lang->qa->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report,importunitresult');
$lang->qa->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->qa->menu->report    = array('link' => 'Berichte|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->qa->menu->caselib   = array('link' => 'Bibliothek|caselib|browse', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => 'Functional Test|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => 'Unit Test|testtask|browseUnits|productID=%s');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->index     = array('link' => 'Index|qa|index');
$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => 'Berichte|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => 'Bibliothek|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->index     = array('link' => 'Index|qa|index');
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testcase->menu->report    = array('link' => 'Berichte|testreport|browse|productID=%s');
$lang->testcase->menu->caselib   = array('link' => 'Bibliothek|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->index     = array('link' => 'Index|qa|index');
$lang->testtask->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testtask->menu->report    = array('link' => 'Berichte|testreport|browse|productID=%s');
$lang->testtask->menu->caselib   = array('link' => 'Bibliothek|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->index     = array('link' => 'Index|qa|index');
$lang->testsuite->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report    = array('link' => 'Report|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib   = array('link' => 'Library|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->index     = array('link' => 'Index|qa|index');
$lang->testreport->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase  = array('link' => 'Fälle|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask  = array('link' => 'Build|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => 'Suite|testsuite|browse|productID=%s');
$lang->testreport->menu->report    = array('link' => 'Berichte|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib   = array('link' => 'Bibliothek|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->index     = array('link' => 'Index|qa|index');
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => 'Fälle|testcase|browse|', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask  = array('link' => 'Build|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => 'Suite|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => 'Berichte|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => 'Bibliothek|caselib|browse|libID=%s', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');

$lang->caselib->subMenu = new stdclass();
$lang->caselib->subMenu->testcase = new stdclass();
$lang->caselib->subMenu->testcase->feature = array('link' => 'Functional Test|testcase|browse|', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->caselib->subMenu->testcase->unit    = array('link' => 'Unit Test|testtask|browseUnits|');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => 'Code|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => 'Build|job|browse', 'subModule' => 'compile,job');
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

/* Project release menu settings. */
$lang->projectrelease = new stdclass();
$lang->projectrelease->menu = new stdclass();

/* Report menu settings. */
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual    = array('link' => 'Annual Summary|report|annualData|year=&dept=&userID=' . (isset($_SESSION['user']) ? zget($_SESSION['user'], 'id', 0) : 0), 'target' => '_blank');
$lang->report->menu->product   = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->execution = array('link' => 'Execution|report|executiondeviation');
$lang->report->menu->test      = array('link' => 'Test|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff     = array('link' => 'Unternehmen|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = 'Hinweis: Berichte wurde auf Basis der Suche generiert. Bitte suchen Sie in der Liste bevor Sie einen Bericht generieren.';

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
$lang->company->menu->browseUser  = array('link' => 'Benutzer|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => 'Abteilung|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'Gruppen|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic     = 'Dynamic|company|dynamic|';
$lang->company->menu->view        = array('link' => 'Company|company|view');

/* Admin menu settings. */
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => 'Home|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->model     = array('link' => 'Model|custom|browsestoryconcept|', 'subModule' => 'holiday');
$lang->admin->menu->custom    = array('link' => 'Custom|custom|index', 'subModule' => 'custom');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->dev       = array('link' => 'Develop|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor,entry');
$lang->admin->menu->message   = array('link' => 'Message|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->system    = array('link' => 'System|backup|index', 'subModule' => 'cron,backup,action');

$lang->subject = new stdclass();
$lang->subject->menu = new stdclass();
$lang->subject->menu->storyConcept = array('link' => 'Story Concpet|custom|browsestoryconcept|');

$lang->company->menu = $lang->company->menu;
$lang->dept->menu    = $lang->company->menu;
$lang->group->menu   = $lang->company->menu;
$lang->user->menu    = $lang->company->menu;

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail    = array('link' => 'Mail|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => 'Browser|message|browser');
$lang->admin->subMenu->message->setting = array('link' => 'Settings|message|setting', 'subModule' => 'message');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = 'Zdoo|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api    = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db     = array('link' => 'Database|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => 'Editor|dev|editor');
$lang->admin->subMenu->dev->entry  = array('link' => 'Application|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->data       = array('link' => 'Data|backup|index', 'subModule' => 'action');
$lang->admin->subMenu->system->safe       = array('link' => 'Sicherheit|admin|safe', 'alias' => 'checkweak');
$lang->admin->subMenu->system->cron       = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone   = array('link' => 'Timezone|custom|timezone', 'subModule' => 'custom');
$lang->admin->subMenu->system->buildIndex = array('link' => 'Full Text Search|search|buildindex|');

$lang->admin->dividerMenu = ',message,system,';

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
$lang->navGroup->build          = 'project';
$lang->navGroup->repo           = 'project';
$lang->navGroup->job            = 'project';
$lang->navGroup->jenkins        = 'project';
$lang->navGroup->compile        = 'project';
$lang->navGroup->report         = 'project';
$lang->navGroup->measrecord     = 'project';

$lang->navGroup->execution = 'execution';
$lang->navGroup->task      = 'execution';
$lang->navGroup->build     = 'execution';
$lang->navGroup->repo      = 'execution';

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

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "The domain %s cannot be found!";
$lang->error->length          = array("『%s』Length Error. It should be『%s』", "『%s』length should be <=『%s』and >『%s』.");
$lang->error->reg             = "『%s』Format Error. It should be『%s』.";
$lang->error->unique          = "『%s』『%s』existed. Please go to Admin->Data->Recycle to restore it, if you are sure it is deleted.";
$lang->error->gt              = "『%s』should be >『%s』.";
$lang->error->ge              = "『%s』should be >=『%s』.";
$lang->error->notempty        = "『%s』should not be blank.";
$lang->error->empty           = "『%s』should be null.";
$lang->error->equal           = "『%s』has to be『%s』.";
$lang->error->int             = array("『%s』should be numbers", "『%s』should be 『%s-%s』.");
$lang->error->float           = "『%s』should be numbers, decimals included.";
$lang->error->email           = "『%s』should be valid Email.";
$lang->error->URL             = "『%s』should be url.";
$lang->error->date            = "『%s』should be valid date.";
$lang->error->datetime        = "『%s』should be valid date.";
$lang->error->code            = "『%s』should be letters or numbers.";
$lang->error->account         = "『%s』should be valid account.";
$lang->error->passwordsame    = "The two passwords should be the same.";
$lang->error->passwordrule    = "Password should follow rules. It must be at least 6 characters.";
$lang->error->accessDenied    = 'Access is denied.';
$lang->error->pasteImg        = 'Image is not allowed to be pasted in your browser!';
$lang->error->noData          = 'No Data';
$lang->error->editedByOther   = 'This record might have been changed. Please refresh and try to edit again!';
$lang->error->tutorialData    = 'No data can be imported in tutorial mode. Please exit tutorial first!';
$lang->error->noCurlExt       = 'No Curl module installed';

/* Page info. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No Records";
$lang->pager->digest       = " <strong>%s</strong> in total. %s <strong>%s/%s</strong> &nbsp; ";
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

$lang->downNotify     = "Download Desktop Notification";
$lang->clientName     = "Desktop";
$lang->downloadClient = "Download ZenTao Desktop";
$lang->clientHelp     = "Client Help";
$lang->clientHelpLink = "https://www.zentao.pm/book/zentaomanual/scrum-tool-im-integration-206.html";
$lang->website        = "http://www.zentao.net";

$lang->suhosinInfo     = "Warning! Data is reaching the limit. Please change <font color=red>sohusin.post.max_vars</font> and <font color=red>sohusin.request.max_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->maxVarsInfo     = "Warning! Data is reaching the limit. Please change <font color=red>max_input_vars</font> (set larger %s value) in php.ini, then save and restart Apache or php-fpm, or some data will not be saved.";
$lang->pasteTextInfo   = "Paste text here. Each line will be the title of each record. ";
$lang->noticeImport    = "Imported data contains data that has already existed in system. Please confirm you actions on the date.";
$lang->importConfirm   = "Import Confirm";
$lang->importAndCover  = "Override";
$lang->importAndInsert = "New Insertion";

$lang->noResultsMatch     = "Keine weiteren Treffer!";
$lang->searchMore         = "Weitere Treffer：";
$lang->chooseUsersToMail  = "Choose users that will be notified.";
$lang->noticePasteImg     = "Paste images here";
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
if(!defined('LONG_TIME'))    define('LONG_TIME',    '2059-12-31');

/* Datepicker. */
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'oder ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Letzes Jahr';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Letzen Monat';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Letzte Woche';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Gestern';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'Dieser Monat';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'Diese Woche';
$lang->datepicker->dpText->TEXT_TODAY       = 'Heute';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Nächstes Jahr';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Nächsten Monat';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Schließen';
$lang->datepicker->dpText->TEXT_DATE        = 'Zeit wählen';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Datum wählen';

$lang->datepicker->dayNames     = array('Sonntag', 'Montag', 'Diensteg', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
$lang->datepicker->abbrDayNames = array('Son', 'Mon', 'Die', 'Mit', 'Don', 'Fri', 'Sam');
$lang->datepicker->monthNames   = array('Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez');

/* Common action icons. */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'cube';
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
$lang->menu->scrum->index          = 'Index|project|index|project={PROJECT}';
$lang->menu->scrum->project        = "$lang->executionCommon|project|index|locate=no";
$lang->menu->scrum->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story', 'subModule' => 'story', 'alias' => 'story,track');
$lang->menu->scrum->doc            = 'Doc|doc|index|';
$lang->menu->scrum->qa             = 'QA|qa|index';
$lang->menu->scrum->ci             = 'Code|repo|browse';
$lang->menu->scrum->build          = array('link' => 'Build|project|build|project={PROJECT}');
$lang->menu->scrum->projectrelease = array('link' => 'Release|projectrelease|browse');
$lang->menu->scrum->dynamic        = array('link' => 'Dynamic|project|dynamic|project={PROJECT}');
$lang->menu->scrum->projectsetting = array('link' => 'Setting|project|view|project={PROJECT}', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->scrum = new stdclass();
$lang->scrum->setMenu = new stdclass();
$lang->scrum->setMenu->view        = array('link' => 'View|project|view|project={PROJECT}');
$lang->scrum->setMenu->products    = array('link' => 'Product|project|manageProducts|project={PROJECT}', 'alias' => 'manageproducts');
$lang->scrum->setMenu->members     = array('link' => 'Member|project|manageMembers|project={PROJECT}', 'alias' => 'managemembers');
$lang->scrum->setMenu->whitelist   = array('link' => 'White List|project|whitelist|project={PROJECT}', 'subModule' => 'personnel');
$lang->scrum->setMenu->stakeholder = array('link' => 'Stakeholder|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->scrum->setMenu->group       = array('link' => 'Priv Group|project|group|project={PROJECT}', 'alias' => 'group,manageview,managepriv');

/* Waterfall menu. */
$lang->menu->waterfall = new stdclass();
$lang->menu->waterfall->index          = array('link' => 'Dashboard|project|index|project={PROJECT}');
$lang->menu->waterfall->programplan    = array('link' => 'Plan|programplan|browse|project={PROJECT}', 'subModule' => 'programplan');
$lang->menu->waterfall->project        = array('link' => $lang->executionCommon . '|execution|task|executionID={EXECUTION}', 'subModule' => ',project,task,');
$lang->menu->waterfall->doc            = array('link' => 'Doc|doc|index|project={PROJECT}');
$lang->menu->waterfall->weekly         = array('link' => 'Weekly|weekly|index|project={PROJECT}', 'subModule' => ',milestone,');
$lang->menu->waterfall->projectstory   = array('link' => $lang->SRCommon . '|projectstory|story');
$lang->menu->waterfall->design         = 'Design|design|browse|product={PRODUCT}';
$lang->menu->waterfall->ci             = 'Repo|repo|browse|';
$lang->menu->waterfall->track          = array('link' => 'Track|projectstory|track', 'alias' => 'track');
$lang->menu->waterfall->qa             = 'QA|qa|index';
$lang->menu->waterfall->projectrelease = array('link' => 'Release|projectrelease|browse');
$lang->menu->waterfall->build          = array('link' => 'Build|project|build|project={PROJECT}');
$lang->menu->waterfall->dynamic        = array('link' => 'Dynamic|project|dynamic|project={PROJECT}');
$lang->menu->waterfall->other          = array('link' => 'Other|project|other', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'issue,risk,stakeholder,nc,workestimation,durationestimation,budget,pssp,measrecord,report');
$lang->menu->waterfall->projectsetting = array('link' => 'Setting|project|view|project={PROJECT}', 'alias' => 'edit,manageproducts,group,managemembers,manageview,managepriv,whitelist,addwhitelist');

$lang->waterfall = new stdclass();
$lang->waterfall->subMenu = new stdclass();
$lang->waterfall->subMenu->other = new stdclass();
$lang->waterfall->subMenu->other->estimation  = array('link' => 'Estimation|workestimation|index|program=%s', 'subModule' => 'workestimation,durationestimation,budget');
$lang->waterfall->subMenu->other->issue       = array('link' => 'Issue|issue|browse|', 'subModule' => 'issue');
$lang->waterfall->subMenu->other->risk        = array('link' => 'Risk|risk|browse|', 'subModule' => 'risk');
$lang->waterfall->subMenu->other->stakeholder = array('link' => 'Stakeholder|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->waterfall->subMenu->other->report      = array('link' => 'Report|report|projectsummary|project=%s', 'subModule' => ',report,measrecord');
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
$lang->noMenuModule = array('report', 'my', 'todo', 'effort', 'program', 'product', 'execution', 'task', 'build', 'productplan', 'project', 'projectrelease', 'projectstory', 'story', 'branch', 'release', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'custom', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'pssp', 'sms', 'message', 'webhook', 'search', 'score', 'stage');

include (dirname(__FILE__) . '/menuOrder.php');
