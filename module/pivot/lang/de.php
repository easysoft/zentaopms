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
$lang->pivot->index     = 'Home';
$lang->pivot->list      = 'Liste';
$lang->pivot->preview   = 'View Pivot Table';
$lang->pivot->item      = 'Eintrag';
$lang->pivot->value     = 'Wert';
$lang->pivot->percent   = '%';
$lang->pivot->undefined = 'Undefiniert';
$lang->pivot->query     = 'Abfrage';
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

$lang->pivot->assign['noassign'] = 'Nicht zugeordnet';
$lang->pivot->assign['assign']   = 'Zugeordnet';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common}abweichungsbericht";
$lang->pivot->productSummary   = $lang->productCommon . ' Zusammenfassung';
$lang->pivot->bugCreate        = 'Bug gemeldet';
$lang->pivot->bugAssign        = 'Bug zugeordnet';
$lang->pivot->workload         = 'Team Arbeitslast ';
$lang->pivot->workloadAB       = 'Arbeitslast';
$lang->pivot->bugOpenedDate    = 'Bug gemeldet am';
$lang->pivot->beginAndEnd      = ' : von';
$lang->pivot->begin            = 'Begin';
$lang->pivot->end              = 'End';
$lang->pivot->dept             = 'Abteilung';
$lang->pivot->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . ' Zusammenfassung|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common} Abweichnung|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bugs gemeldet|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bugs zugeordnet|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Team Arbeitslast|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->executionCommon;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'Name';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = 'Aufgabe';
$lang->pivot->estimate      = 'Schätzung(h)';
$lang->pivot->consumed      = 'Verbraucht';
$lang->pivot->remain        = 'Rest';
$lang->pivot->deviation     = 'Abweichung';
$lang->pivot->deviationRate = 'Abweichungsrate';
$lang->pivot->total         = 'Summe';
$lang->pivot->to            = 'bis';
$lang->pivot->taskTotal     = "Summe Aufgaben";
$lang->pivot->manhourTotal  = "Summe Stunden";
$lang->pivot->validRate     = "Gültigkeitsrate";
$lang->pivot->validRateTips = "Lösung ist gelöst/verschoben oder der Status ist gelöst/geschlossen.";
$lang->pivot->unplanned     = 'Ungeplant';
$lang->pivot->workday       = 'Stunden/Tag';
$lang->pivot->diffDays      = 'Manntage';

$lang->pivot->typeList['default'] = 'Standard';
$lang->pivot->typeList['pie']     = 'Torte';
$lang->pivot->typeList['bar']     = 'Balken';
$lang->pivot->typeList['line']    = 'Linien';

$lang->pivot->conditions    = 'Filter:';
$lang->pivot->closedProduct = 'Geschlossen ' . $lang->productCommon;
$lang->pivot->overduePlan   = 'Fälligkeit Plan';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug-Name';
$lang->pivot->taskName     = 'Aufgabe-Name';
$lang->pivot->todoName     = 'Todo-Name';
$lang->pivot->testTaskName = 'Testaufgabe-Name';
$lang->pivot->deadline     = 'Fällig';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->proVersion    = '<a href="https://www.zentao.net/page/enterprise.html" target="_blank">Testen Sie ZenTao Pro für mehr Informationen!</a>';
$lang->pivot->proVersionEn  = '<a href="https://www.zentao.pm/" etarget="_blank">Testen Sie ZenTao Pro für mehr Informationen!</a>';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day.
For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';
