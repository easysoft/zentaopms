<?php
/**
 * The pivot module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->pivot->index     = 'Accueil Rapports';
$lang->pivot->list      = 'Rapports';
$lang->pivot->preview   = 'View Pivot Table';
$lang->pivot->item      = 'Item';
$lang->pivot->value     = 'Valeur';
$lang->pivot->percent   = '%';
$lang->pivot->undefined = 'N/D';
$lang->pivot->query     = 'Requête';
$lang->pivot->project   = $lang->projectCommon;
$lang->pivot->PO        = 'PO';

$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';

$lang->pivot->assign['noassign'] = 'Non affecté';
$lang->pivot->assign['assign']   = 'Affecté';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "Rapport de déviation du{$lang->execution->common}";
$lang->pivot->productSummary   = 'Résumé ' . $lang->productCommon;
$lang->pivot->bugCreate        = 'Résumé remontée Bugs';
$lang->pivot->bugAssign        = 'Résumé affectation Bugs';
$lang->pivot->workload         = 'Résumé charge travail';
$lang->pivot->workloadAB       = 'Charge Travail';
$lang->pivot->bugOpenedDate    = 'Bugs signalés de';
$lang->pivot->beginAndEnd      = ' de';
$lang->pivot->begin            = 'Begin';
$lang->pivot->end              = 'End';
$lang->pivot->dept             = 'Compartiment';
$lang->pivot->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = 'Résumé ' . $lang->productCommon . '|pivot|productsummary';
$lang->pivotList->project->lists[10] = "Dérv {$lang->execution->common}|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Résumé remontée Bugs|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Résumé affectation Bugs|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Résumé charge travail|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->executionCommon;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'Utilisateur';
$lang->pivot->bugTotal      = 'Bugs';
$lang->pivot->task          = 'Tâches';
$lang->pivot->estimate      = 'Estimé';
$lang->pivot->consumed      = 'Coût';
$lang->pivot->remain        = 'Reste';
$lang->pivot->deviation     = 'Dérive';
$lang->pivot->deviationRate = 'Taux Dérive';
$lang->pivot->total         = 'Total';
$lang->pivot->to            = 'à';
$lang->pivot->taskTotal     = "Tâches Totales";
$lang->pivot->manhourTotal  = "Heures Totales";
$lang->pivot->validRate     = "Taux Validation";
$lang->pivot->validRateTips = "Résolution est Résolu/Reporté ou statut est Résolu/Fermé.";
$lang->pivot->unplanned     = 'Non planifié';
$lang->pivot->workday       = 'Heures/Jour';
$lang->pivot->diffDays      = 'jours';

$lang->pivot->typeList['default'] = 'Défaut';
$lang->pivot->typeList['pie']     = 'Camenbert';
$lang->pivot->typeList['bar']     = 'Barres';
$lang->pivot->typeList['line']    = 'Lignes';

$lang->pivot->conditions    = 'Filtré par :';
$lang->pivot->closedProduct = $lang->productCommon . 's Fermés';
$lang->pivot->overduePlan   = 'Plans Echus';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Nom Bug';
$lang->pivot->taskName     = 'Nom Tâche';
$lang->pivot->todoName     = 'Nom Agenda';
$lang->pivot->testTaskName = 'Nom Recette';
$lang->pivot->deadline     = 'Date Butoir';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->proVersion    = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Essayez ZenTao Pro pour en savoir plus !</a>';
$lang->pivot->proVersionEn  = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Try ZenTao Pro for more!</a>';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day.
For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview']['product'] = $lang->product->common;
$lang->pivot->featureBar['preview']['project'] = $lang->project->common;
$lang->pivot->featureBar['preview']['test']    = $lang->qa->common;
$lang->pivot->featureBar['preview']['staff']   = $lang->system->common;
