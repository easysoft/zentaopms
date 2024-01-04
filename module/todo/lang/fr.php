<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->todo->index        = 'Accueil';
$lang->todo->create       = 'Ajout Entrée';
$lang->todo->createCycle  = 'Ajout Tâche récurrente';
$lang->todo->assignTo     = 'Affecter à';
$lang->todo->assignedDate = 'Date Affectation';
$lang->todo->assignAction = 'Affacter Action';
$lang->todo->start        = 'Start Todo';
$lang->todo->activate     = 'Activer Entrée';
$lang->todo->batchCreate  = 'Ajout par lot ';
$lang->todo->edit         = 'Editer Agenda';
$lang->todo->close        = 'Fermer Agenda';
$lang->todo->batchClose   = 'Fermeture par lot';
$lang->todo->batchEdit    = 'Edition par lot';
$lang->todo->view         = 'Détail';
$lang->todo->finish       = 'Terminer';
$lang->todo->batchFinish  = 'Terminer par lot';
$lang->todo->export       = 'Exporter Agenda';
$lang->todo->delete       = 'Supprimer';
$lang->todo->import2Today = 'Change Date';
$lang->todo->import       = 'Importer';
$lang->todo->legendBasic  = 'Infos de Base';
$lang->todo->cycle        = 'Rendre Récurrent';
$lang->todo->cycleConfig  = 'Récurrence';
$lang->todo->project      = $lang->projectCommon;
$lang->todo->product      = $lang->productCommon;
$lang->todo->execution    = $lang->executionCommon;
$lang->todo->changeDate   = 'Change Date';
$lang->todo->future       = 'TBD';
$lang->todo->timespanTo   = 'To';
$lang->todo->transform    = 'Transform';

$lang->todo->reasonList['story'] = 'Convertir en Story';
$lang->todo->reasonList['task']  = 'Convertir en Tâche';
$lang->todo->reasonList['bug']   = 'Convertir en Bug';
$lang->todo->reasonList['done']  = 'Fait';

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Owner';
$lang->todo->date         = 'Date';
$lang->todo->begin        = 'Début';
$lang->todo->end          = 'Fin';
$lang->todo->beginAB      = 'Début';
$lang->todo->endAB        = 'Fin';
$lang->todo->beginAndEnd  = 'Début et Fin';
$lang->todo->objectID     = 'Link ID';
$lang->todo->type         = 'Type';
$lang->todo->pri          = 'Priorité';
$lang->todo->name         = 'Titre';
$lang->todo->status       = 'Statut';
$lang->todo->desc         = 'Description';
$lang->todo->config       = 'Config';
$lang->todo->private      = 'Privé';
$lang->todo->cycleDay     = 'Jour';
$lang->todo->cycleWeek    = 'Semaine';
$lang->todo->cycleMonth   = 'Mois';
$lang->todo->cycleYear    = 'Année';
$lang->todo->day          = 'Jour';
$lang->todo->assignedTo   = 'AssignedTo';
$lang->todo->assignedBy   = 'AssignedBy';
$lang->todo->finishedBy   = 'FinishedBy';
$lang->todo->finishedDate = 'FinishedDate';
$lang->todo->closedBy     = 'ClosedBy';
$lang->todo->closedDate   = 'ClosedDate';
$lang->todo->deadline     = 'Expiration';
$lang->todo->deleted      = 'Deleted';
$lang->todo->ditto        = 'Idem';
$lang->todo->from         = 'From';
$lang->todo->generate     = 'Generate a todo';
$lang->todo->advance      = 'Advance';
$lang->todo->cycleType    = 'Cycle type';
$lang->todo->monthly      = 'Monthly';
$lang->todo->weekly       = 'Weekly';

$lang->todo->cycleDaysLabel  = 'Interval days';
$lang->todo->beforeDaysLabel = 'Days in advance';

$lang->todo->every        = 'Chaque';
$lang->todo->specify      = 'Désigner';
$lang->todo->everyYear    = 'Tous les ans';
$lang->todo->beforeDays   = "<span class='input-group-addon'>Créer automatiquement une alerte </span>%s<span class='input-group-addon'>jours avant</span>";
$lang->todo->dayNames     = array(1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 0 => 'Dimanche');
$lang->todo->specifiedDay = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

$lang->todo->confirmBug     = "Cette action est liée au Bug #%s. Voulez-vous l'éditer ?";
$lang->todo->confirmTask    = "Cette action est liée à la Tâche #%s. Voulez-vous l'éditer ?";
$lang->todo->confirmStory   = "Cette action est liée à la Story #%s. Voulez-vous l'éditer ?";
$lang->todo->noOptions      = 'Vous n\'avez pas de %s en attente pour le moment. Veuillez sélectionner le type de Todo.';
$lang->todo->summary        = 'Total todos: <strong>%s</strong>, Wait: <strong>%s</strong>, Doing: <strong>%s</strong>.';
$lang->todo->checkedSummary = 'Seleted: <strong>%total%</strong>, Wait: <strong>%wait%</strong>, Doing: <strong>%doing%</strong>.';

$lang->todo->abbr = new stdclass();
$lang->todo->abbr->start  = 'Start';
$lang->todo->abbr->finish = 'Finish';

$lang->todo->statusList['wait']   = 'En Attente';
$lang->todo->statusList['doing']  = 'En cours';
$lang->todo->statusList['done']   = 'Fait';
$lang->todo->statusList['closed'] = 'Fermé';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[1] = 'Critique';
$lang->todo->priList[2] = 'Importante';
$lang->todo->priList[3] = 'Normale';
$lang->todo->priList[4] = 'Faible';

$lang->todo->typeList['custom']   = 'Person.';
$lang->todo->typeList['cycle']    = 'Récur';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = 'Tâche';
$lang->todo->typeList['story']    = 'Story';
$lang->todo->typeList['testtask'] = 'Testtask';

$lang->todo->fromList['bug']   = 'Related Bug';
$lang->todo->fromList['task']  = 'Related Task';
$lang->todo->fromList['story'] = 'Related' . $lang->SRCommon;

$lang->todo->confirmDelete  = "Voulez-vous supprimer cette entrée de l'agenda ?";
$lang->todo->thisIsPrivate  = "Il s'agit d'un rdv privé";
$lang->todo->lblDisableDate = 'A définir';
$lang->todo->lblBeforeDays  = "Créer une entrée %s jour(s) plus tôt";
$lang->todo->lblClickCreate = "Cliquez pour ajouter une entrée";
$lang->todo->noTodo         = 'Aucune entrée de ce type.';
$lang->todo->noAssignedTo   = "Le champ Assigné à ne doit pas être vide.";
$lang->todo->unfinishedTodo = "Les entrées de l'ID %s ne sont pas terminés et ne peuvent pas se fermer.";
$lang->todo->today          = 'Todo Today';
$lang->todo->selectProduct  = "Please select a {$lang->productCommon}";
$lang->todo->privateTip     = 'Only the todo I create that is assigned to me can be set to private, and only I can see it once it is set to private.';

$lang->todo->periods['all']             = 'Assigned To Yourself';
$lang->todo->periods['before']          = 'Unfinished';
$lang->todo->periods['future']          = 'TBD';
$lang->todo->periods['thisWeek']        = 'This Week';
$lang->todo->periods['thisMonth']       = 'This Month';
$lang->todo->periods['thisYear']        = 'This Year';
$lang->todo->periods['assignedToOther'] = 'Assigned To Other';
$lang->todo->periods['cycle']           = 'Recurrence';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, est $extra par <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, est marqué par <strong>$actor</strong> comme <strong>$extra</strong>.', 'extra' => 'statusList');
