<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
$lang->tutorial->targetPageTip     = 'Öffne 【%s】 Seite durch befolgen dieser Schritte.';
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

$lang->tutorial->tasks['createProduct']         = array('title' => 'Produkt erstellen');
$lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' => 'Produkt');
$lang->tutorial->tasks['createProduct']['desc'] = "<p>Produkt erstellen: </p><ul><li data-target='nav'> Öffnen <span class='task-nav'>Produkt <i class='icon icon-angle-right'></i> Neu;</span></li><li data-target='form'>Tragen Sie die Produktdaten ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => 'Story erstellen');
$lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Story erstellen');
$lang->tutorial->tasks['createStory']['desc'] = "<p>Story erstellen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'>Produkt <i class='icon icon-angle-right'></i>Story <i class='icon icon-angle-right'></i>Erstellen;</span></li><li data-target='form'>Tragen Sie die Informationen ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => 'Projekt erstellen');
$lang->tutorial->tasks['createProject']['nav']  = array('module' => 'project', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => 'Projekt erstellen');
$lang->tutorial->tasks['createProject']['desc'] = "<p>Projekt erstellen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Projekt <i class='icon icon-angle-right'></i> New</span> Page;</li><li data-target='form'>Tragen Sie die Projektinformationen ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => 'Projektteam verwalten');
$lang->tutorial->tasks['manageTeam']['nav']  = array('module' => 'project', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => 'Projektteam verwalten');
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>Projektteam verwalten: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Project <i class='icon icon-angle-right'></i> Team <i class='icon icon-angle-right'></i> Teammitglieder verwalten</span> Seite；</li><li data-target='form'>Wählen Sie Benutzer für das Team.</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => 'Story verknüpfen');
$lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'project', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => 'Story verknüpfen');
$lang->tutorial->tasks['linkStory']['desc'] = "<p>Eine Story mit dem Projekt verknüpfen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Projekt <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i>Story verknüpfen;</span></li><li data-target='form'>Wählen Sie Storys aus der Liste um sie zu verknüpfen;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => 'Aufgaben aufteilen');
$lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'project', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Aufgabe erstellen');
$lang->tutorial->tasks['createTask']['desc'] = "<p>Aufgaben aufteilen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Projekt <i class='icon icon-angle-right'></i> Story <i class='icon icon-angle-right'></i> WBS;</span></li><li data-target='form'>Tragen Sie die Aufgabeninformationen ein;</li><li data-target='submit'>Speichern</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => 'Bug melden');
$lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'Bug erstellen');
$lang->tutorial->tasks['createBug']['desc'] = "<p>Bug erstellen: </p><ul><li data-target='nav'>Öffnen <span class='task-nav'> Test <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> Einen Bug erstellen</span>；</li><li data-target='form'>Tragen Sie die Bug Informationen ein:</li><li data-target='submit'>Speichern</li></ul>";
