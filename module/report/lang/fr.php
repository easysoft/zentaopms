<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->report->common     = 'Rapports';
$lang->report->index      = 'Accueil Rapports';
$lang->report->list       = 'Rapports';
$lang->report->item       = 'Item';
$lang->report->value      = 'Valeur';
$lang->report->percent    = '%';
$lang->report->undefined  = 'N/D';
$lang->report->query      = 'Requête';
$lang->report->annual     = 'Résumé Annuel';

$lang->report->colors[]   = 'AFD8F8';
$lang->report->colors[]   = 'F6BD0F';
$lang->report->colors[]   = '8BBA00';
$lang->report->colors[]   = 'FF8E46';
$lang->report->colors[]   = '008E8E';
$lang->report->colors[]   = 'D64646';
$lang->report->colors[]   = '8E468E';
$lang->report->colors[]   = '588526';
$lang->report->colors[]   = 'B3AA00';
$lang->report->colors[]   = '008ED6';
$lang->report->colors[]   = '9D080D';
$lang->report->colors[]   = 'A186BE';

$lang->report->assign['noassign'] = 'Non affecté';
$lang->report->assign['assign'] = 'Affecté';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = 'Dérive ' . $lang->projectCommon;
$lang->report->productSummary   = 'Résumé ' . $lang->productCommon;
$lang->report->bugCreate        = 'Résumé remontée Bugs';
$lang->report->bugAssign        = 'Résumé affectation Bugs';
$lang->report->workload         = 'Résumé charge travail';
$lang->report->workloadAB       = 'Charge Travail';
$lang->report->bugOpenedDate    = 'Bugs signalés de';
$lang->report->beginAndEnd      = ' de';
$lang->report->dept             = 'Compartiment';
$lang->report->deviationChart   = 'Graphique de Dérive du ' . $lang->projectCommon;

$lang->reportList->project->lists[10] = 'Dérive ' . $lang->projectCommon . '|report|projectdeviation';
$lang->reportList->product->lists[10] = 'Résumé ' . $lang->productCommon . '|report|productsummary';
$lang->reportList->test->lists[10]    = 'Résumé remontée Bugs|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Résumé affectation Bugs|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Résumé charge travail|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Utilisateur';
$lang->report->bugTotal      = 'Bugs';
$lang->report->task          = 'Tâches';
$lang->report->estimate      = 'Estimé';
$lang->report->consumed      = 'Coût';
$lang->report->remain        = 'Reste';
$lang->report->deviation     = 'Dérive';
$lang->report->deviationRate = 'Taux Dérive';
$lang->report->total         = 'Total';
$lang->report->to            = 'à';
$lang->report->taskTotal     = "Tâches Totales";
$lang->report->manhourTotal  = "Heures Totales";
$lang->report->validRate     = "Taux Validation";
$lang->report->validRateTips = "Résolution est Résolu/Reporté ou statut est Résolu/Fermé.";
$lang->report->unplanned     = 'Non planifié';
$lang->report->workday       = 'Heures/Jour';
$lang->report->diffDays      = 'jours';

$lang->report->typeList['default'] = 'Défaut';
$lang->report->typeList['pie']     = 'Camenbert';
$lang->report->typeList['bar']     = 'Barres';
$lang->report->typeList['line']    = 'Lignes';

$lang->report->conditions    = 'Filtré par :';
$lang->report->closedProduct = $lang->productCommon . 's Fermés';
$lang->report->overduePlan   = 'Plans Echus';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Nom Bug';
$lang->report->taskName     = 'Nom Tâche';
$lang->report->todoName     = 'Nom Agenda';
$lang->report->testTaskName = 'Nom Recette';
$lang->report->deadline     = 'Date Butoir';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Note : Vous avez';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Tâches (%s),";
$lang->report->mailTitle->todo     = " Agenda (%s),";
$lang->report->mailTitle->testTask = " Recettes (%s),";

$lang->report->proVersion   = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Essayez ZenTao Pro pour en savoir plus !</a>';
$lang->report->proVersionEn = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Try ZenTao Pro for more!</a>';
