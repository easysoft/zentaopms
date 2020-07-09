<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = 'Todo';
$lang->todo->index        = "Home";
$lang->todo->create       = "Todo hinzufügen";
$lang->todo->createCycle  = "Wiederkehrenden Todo hinzufügen";
$lang->todo->assignTo     = "Zuordnen";
$lang->todo->assignedDate = "Assigned Date";
$lang->todo->assignAction = "Assign Todo";
$lang->todo->start        = "Start Todo";
$lang->todo->activate     = "Aktivieren";
$lang->todo->batchCreate  = "Mehrere hinzufügen";
$lang->todo->edit         = "Bearbeiten";
$lang->todo->close        = "Schließen";
$lang->todo->batchClose   = "Mehrere schließen";
$lang->todo->batchEdit    = "Mehrere bearbeiten";
$lang->todo->view         = "Übersicht";
$lang->todo->finish       = "Abschließen";
$lang->todo->batchFinish  = "Mehrere abschließen";
$lang->todo->export       = "Exportieren";
$lang->todo->delete       = "Löschen";
$lang->todo->import2Today = "Importieren für Heute";
$lang->todo->import       = "Importieren";
$lang->todo->legendBasic  = "Basis Info";
$lang->todo->cycle        = "Wiederkehrend";
$lang->todo->cycleConfig  = "Wiederkehrend setzen";

$lang->todo->reasonList['story'] = "Story übertragen";
$lang->todo->reasonList['task']  = "Aufgabe übertragen";
$lang->todo->reasonList['bug']   = "Bug übertragen";
$lang->todo->reasonList['done']  = "Erledigt";

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Besitzer';
$lang->todo->date         = 'Datum';
$lang->todo->begin        = 'Start';
$lang->todo->end          = 'Ende';
$lang->todo->beginAB      = 'Start';
$lang->todo->endAB        = 'Ende';
$lang->todo->beginAndEnd  = 'Dauer';
$lang->todo->idvalue      = 'Link ID';
$lang->todo->type         = 'Typ';
$lang->todo->pri          = 'Priorität';
$lang->todo->name         = 'Titel';
$lang->todo->status       = 'Status';
$lang->todo->desc         = 'Beschreibung';
$lang->todo->private      = 'Privat';
$lang->todo->cycleDay     = 'Tag';
$lang->todo->cycleWeek    = 'Woche';
$lang->todo->cycleMonth   = 'Monat';
$lang->todo->assignedTo   = 'AssignedTo';
$lang->todo->assignedBy   = 'AssignedBy';
$lang->todo->finishedBy   = 'FinishedBy';
$lang->todo->finishedDate = 'FinishedDate';
$lang->todo->closedBy     = 'ClosedBy';
$lang->todo->closedDate   = 'ClosedDate';
$lang->todo->deadline     = 'Fällig';

$lang->todo->every      = 'Jeden';
$lang->todo->beforeDays = "%s<span class='input-group-addon'>early in advance to be done</span>";
$lang->todo->dayNames   = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');

$lang->todo->confirmBug   = 'Dieser Todo steht in Beziehung mit Bug #%s. Möchten Sie das bearbeiten?';
$lang->todo->confirmTask  = 'Dieser Todo steht in Beziehung mit Task #%s， Möchten Sie das bearbeiten?';
$lang->todo->confirmStory = 'Dieser Todo steht in Beziehung mit Story #%s， Möchten Sie das bearbeiten?';

$lang->todo->statusList['wait']   = 'Wartend';
$lang->todo->statusList['doing']  = 'In Arbeit';
$lang->todo->statusList['done']   = 'Erledigt';
$lang->todo->statusList['closed'] = 'Geschlossen';
//$lang->todo->statusList['cancel']   = 'Abgebrochen';
//$lang->todo->statusList['postpone'] = 'Verzögert';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = 'Normal';
$lang->todo->priList[1] = 'Höchste';
$lang->todo->priList[2] = 'Hoch';
$lang->todo->priList[4] = 'Niedrig';

$lang->todo->typeList['custom'] = 'Eigene';
$lang->todo->typeList['cycle']  = 'Wiederkehrend';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . ' Aufgabe';
$lang->todo->typeList['story']  = $lang->projectCommon . ' Story';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "Möchten Sie diesen ToDo löschen?";
$lang->todo->thisIsPrivate  = 'Dies ist ein privater ToDo';
$lang->todo->lblDisableDate = 'Später setzen';
$lang->todo->lblBeforeDays  = "Erstelle einen ToDo %s Tag(e) früher";
$lang->todo->lblClickCreate = "Klicken um einen ToDo hinzuzufügen";
$lang->todo->noTodo         = 'Keine ToDos dieses Typs.';
$lang->todo->noAssignedTo   = "Zuordung an darf nicht leer sein.";
$lang->todo->unfinishedTodo = 'The todos of ID %s are not finished, and can not close.';

$lang->todo->periods['all']        = 'Alle';
$lang->todo->periods['thisYear']   = 'Dieses Jahr';
$lang->todo->periods['future']     = 'Wartend';
$lang->todo->periods['before']     = 'Nicht abgeschlossen';
$lang->todo->periods['cycle']      = 'Wiederkehrend';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, wurde $extra von <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, wurde von <strong>$actor</strong> als <strong>$extra</strong> markiert.', 'extra' => 'statusList');
