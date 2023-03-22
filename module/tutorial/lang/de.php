<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = 'Anleitung';
$lang->tutorial->desc             = 'Machen Sie sich mit ZenTao vertraut. Es dauert ca. 10 Minuten und Sie können jeder Zeit abbrechen.';
$lang->tutorial->start            = "Los geht's!";
$lang->tutorial->exit             = 'Beenden';
$lang->tutorial->congratulation   = 'Glückwunsch! Sie haben alle Aufgaben erledigt.';
$lang->tutorial->restart          = 'Neustart';
$lang->tutorial->currentTask      = 'Aktuelle Aufgabe';
$lang->tutorial->allTasks         = 'Alle Aufgaben';
$lang->tutorial->previous         = 'Vorheriger';
$lang->tutorial->nextTask         = 'Nächster';
$lang->tutorial->openTargetPage   = 'Offen <strong class="task-page-name">target</strong>';
$lang->tutorial->atTargetPage     = 'Auf <strong class="task-page-name">target</strong>';
$lang->tutorial->reloadTargetPage = 'Neu laden';
$lang->tutorial->target           = 'Ziel';
$lang->tutorial->targetPageTip    = 'Öffne 【%s】 Seite durch befolgen dieser Schritte.';
$lang->tutorial->targetAppTip     = 'Open <strong class="task-page-name">%s</strong>';
$lang->tutorial->requiredTip      = '【%s】 ist erforderlich.';
$lang->tutorial->congratulateTask = 'Glückwunsch! Sie sind fertig【<span class="task-name-current"></span>】!';
$lang->tutorial->serverErrorTip   = 'Fehler!';
$lang->tutorial->ajaxSetError     = 'Abgeschlossene Aufgabe muss definiert sein. Wenn Sie die Aufgabe zurücksetzen möchten, setzen Sie den Wert auf Null.';
$lang->tutorial->novice           = "Für einen Schnellstart, beginnen Sie mit einer 2 Minuten Anleitung?";
$lang->tutorial->dataNotSave      = "In der Anleitung erstellte Daten werden nicht gespeichert!";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => 'Benutzer erstellen');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'browseUser', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-user-btn', 'targetPageName' => 'Benutzer erstellen');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>Einen Benutzer erstellen: </p><ul><li data-target='nav'>Öffne <span class='task-nav'>Unternehmen <i class='icon icon-angle-right'></i> Benutzer<i class='icon icon-angle-right'></i> Neu;</span></li><li data-target='form'>Tragen Sie bitte die Daten ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createProgram']         = array('title' => 'Create a program');
$lang->tutorial->tasks['createProgram']['nav']  = array('app' => 'program', 'module' => 'program', 'method' => 'create', 'menuModule' => 'program', 'menu' => '#heading>.header-btn:first,#navbar>.nav>li[data-id="browse"],.create-program-btn', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-program-btn', 'targetPageName' => 'Create program');
$lang->tutorial->tasks['createProgram']['desc'] = "<p>Create a new program：</p><ul><li data-target='nav'>Open <span class='task-nav'>Program <i class='icon icon-angle-right'></i> Program list <i class='icon icon-angle-right'></i> Create program</span>;</li><li data-target='form'>Fill the form with program information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => 'Produkt erstellen');
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' => 'Produkt');
$lang->tutorial->tasks['createProduct']['desc'] = "<p>Produkt erstellen: </p><ul><li data-target='nav'> Öffnen <span class='task-nav'>Produkt <i class='icon icon-angle-right'></i> Neu;</span></li><li data-target='form'>Tragen Sie die Produktdaten ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => 'Story erstellen');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Story erstellen');
$lang->tutorial->tasks['createStory']['desc'] = "<p>Story erstellen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'>Produkt <i class='icon icon-angle-right'></i>Story <i class='icon icon-angle-right'></i>Erstellen;</span></li><li data-target='form'>Tragen Sie die Informationen ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => 'Create a ' . $lang->projectCommon);
$lang->tutorial->tasks['createProject']['nav']  = array('app' => 'project', 'module' => 'project', 'method' => 'create', 'menuModule' => 'browse', 'menu' => '', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => 'Create ' . $lang->projectCommon);
$lang->tutorial->tasks['createProject']['desc'] = "<p>Create a {$lang->projectCommon}: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> New</span> Page;</li><li data-target='form'>Fill the form with {$lang->projectCommon} information;</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => "Manage {$lang->projectCommon} Team");
$lang->tutorial->tasks['manageTeam']['nav']  = array('app' => 'project', 'module' => 'project', 'method' => 'managemembers', 'menuModule' => '', 'menu' => '#navbar>.nav>li[data-id="browse"],#cards>.col>.panel:first .project-name,#projectForm td.c-name:first a,#navbar>.nav>li[data-id="settings"],#subNavbar>.nav>li[data-id="members"],.manage-team-btn', 'target' => '.manage-team-btn', 'vars' => 'projectID=0', 'form' => '#teamForm', 'requiredFields' => 'accounts1,accounts', 'submit' => '#submit', 'targetPageName' => 'Manage team members');
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>Manage {$lang->projectCommon} team members: </p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> Team <i class='icon icon-angle-right'></i> Manage Team Members</span> Page；</li><li data-target='form'>Choose users for the team.</li><li data-target='submit'>Save</li></ul>";

$lang->tutorial->tasks['createProjectExecution']         = array('title' => 'Create a ' . $lang->executionCommon);
$lang->tutorial->tasks['createProjectExecution']['nav']  = array('app' => 'project', 'module' => 'execution', 'method' => 'create', 'menuModule' => 'browse', 'menu' => '#navbar>.nav>li[data-id="browse"],#cards>.col>.panel:first .project-name,#projectForm td.c-name:first a,#navbar>.nav>li[data-id="execution"],.create-execution-btn', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-execution-btn', 'targetPageName' => 'Create' . $lang->executionCommon);
$lang->tutorial->tasks['createProjectExecution']['desc'] = "<p>Create a new {$lang->executionCommon}：</p><ul><li data-target='nav'>Open <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} list <i class='icon icon-angle-right'></i> Create {$lang->executionCommon}</span>;</li><li data-target='form'>Fill the form with {$lang->executionCommon} information；</li><li data-target='submit'>Save {$lang->executionCommon}</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => 'Story verknüpfen');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'execution', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => 'Story verknüpfen');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>Eine Story mit dem Projekt verknüpfen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Projekt <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i>Story verknüpfen;</span></li><li data-target='form'>Wählen Sie Storys aus der Liste um sie zu verknüpfen;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => 'Aufgaben aufteilen');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'execution', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Aufgabe erstellen');
$lang->tutorial->tasks['createTask']['desc'] = "<p>Aufgaben aufteilen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Projekt <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> WBS;</span></li><li data-target='form'>Tragen Sie die Aufgabeninformationen ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => 'Bug melden');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Bug erstellen');
$lang->tutorial->tasks['createBug']['desc'] = "<p>Bug erstellen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Einen Bug erstellen</span>；</li><li data-target='form'>Tragen Sie die Bug Informationen ein:</li><li data-target='submit'>Speichern</li></ul>";

global $config;
if($config->systemMode == 'light') unset($lang->tutorial->tasks['createProgram']);
