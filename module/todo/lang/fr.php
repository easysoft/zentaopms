<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->todo->common       = 'Agenda';
$lang->todo->index        = "Accueil";
$lang->todo->create       = "Ajout Entrée";
$lang->todo->createCycle  = "Ajout Tâche récurrente";
$lang->todo->assignTo     = "Affecter à";
$lang->todo->assignedDate = "Date Affectation";
$lang->todo->assignAction = "Affacter Action";
$lang->todo->start        = "Start Todo";
$lang->todo->activate     = "Activer Entrée";
$lang->todo->batchCreate  = "Ajout par lot ";
$lang->todo->edit         = "Editer Agenda";
$lang->todo->close        = "Fermer Agenda";
$lang->todo->batchClose   = "Fermeture par lot";
$lang->todo->batchEdit    = "Edition par lot";
$lang->todo->view         = "Détail";
$lang->todo->finish       = "Terminer";
$lang->todo->batchFinish  = "Terminer par lot";
$lang->todo->export       = "Exporter Agenda";
$lang->todo->delete       = "Supprimer";
$lang->todo->import2Today = "Importer à Aujourd'hui";
$lang->todo->import       = "Importer";
$lang->todo->legendBasic  = "Infos de Base";
$lang->todo->cycle        = "Rendre Récurrent";
$lang->todo->cycleConfig  = "Récurrence";

$lang->todo->reasonList['story'] = "Convertir en Story";
$lang->todo->reasonList['task']  = "Convertir en Tâche";
$lang->todo->reasonList['bug']   = "Convertir en Bug";
$lang->todo->reasonList['done']  = "Fait";

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Owner';
$lang->todo->date         = 'Date';
$lang->todo->begin        = 'Début';
$lang->todo->end          = 'Fin';
$lang->todo->beginAB      = 'Début';
$lang->todo->endAB        = 'Fin';
$lang->todo->beginAndEnd  = 'Début et Fin';
$lang->todo->idvalue      = 'Link ID';
$lang->todo->type         = 'Type';
$lang->todo->pri          = 'Priorité';
$lang->todo->name         = 'Titre';
$lang->todo->status       = 'Statut';
$lang->todo->desc         = 'Description';
$lang->todo->private      = 'Privé';
$lang->todo->cycleDay     = 'Jour';
$lang->todo->cycleWeek    = 'Semaine';
$lang->todo->cycleMonth   = 'Mois';
$lang->todo->assignedTo   = 'AssignedTo';
$lang->todo->assignedBy   = 'AssignedBy';
$lang->todo->finishedBy   = 'FinishedBy';
$lang->todo->finishedDate = 'FinishedDate';
$lang->todo->closedBy     = 'ClosedBy';
$lang->todo->closedDate   = 'ClosedDate';
$lang->todo->deadline     = 'Expiration';

$lang->todo->every      = 'Chaque';
$lang->todo->beforeDays = "<span class='input-group-addon'>Créer automatiquement une alerte </span>%s<span class='input-group-addon'>jours avant</span>";
$lang->todo->dayNames   = array(1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 0 => 'Dimanche');

$lang->todo->confirmBug   = "Cette action est liée au Bug #%s. Voulez-vous l'éditer ?";
$lang->todo->confirmTask  = "Cette action est liée à la Tâche #%s. Voulez-vous l'éditer ?";
$lang->todo->confirmStory = "Cette action est liée à la Story #%s. Voulez-vous l'éditer ?";

$lang->todo->statusList['wait']   = 'En Attente';
$lang->todo->statusList['doing']  = 'En cours';
$lang->todo->statusList['done']   = 'Fait';
$lang->todo->statusList['closed'] = 'Fermé';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = 'Normale';
$lang->todo->priList[1] = 'Critique';
$lang->todo->priList[2] = 'Importante';
$lang->todo->priList[4] = 'Faible';

$lang->todo->typeList['custom']   = 'Person.';
$lang->todo->typeList['cycle']    = 'Récur';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = 'Tâche';
$lang->todo->typeList['story']    = 'Story';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "Voulez-vous supprimer cette entrée de l'agenda ?";
$lang->todo->thisIsPrivate  = "Il s'agit d'un rdv privé";
$lang->todo->lblDisableDate = 'A définir';
$lang->todo->lblBeforeDays  = "Créer une entrée %s jour(s) plus tôt";
$lang->todo->lblClickCreate = "Cliquez pour ajouter une entrée";
$lang->todo->noTodo         = 'Aucune entrée de ce type.';
$lang->todo->noAssignedTo   = "Le champ Assigné à ne doit pas être vide.";
$lang->todo->unfinishedTodo = "Les entrées de l'ID %s ne sont pas terminés et ne peuvent pas se fermer.";

$lang->todo->periods['all']        = 'Toutes les entrées';
$lang->todo->periods['thisYear']   = 'Cette Année';
$lang->todo->periods['future']     = 'A définir';
$lang->todo->periods['before']     = 'Non terminée';
$lang->todo->periods['cycle']      = 'Récurrence';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, est $extra par <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, est marqué par <strong>$actor</strong> comme <strong>$extra</strong>.', 'extra' => 'statusList');
