<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->common     = 'Berichte';
$lang->report->index      = 'Home';
$lang->report->list       = 'Liste';
$lang->report->item       = 'Eintrag';
$lang->report->value      = 'Wert';
$lang->report->percent    = '%';
$lang->report->undefined  = 'Undefiniert';
$lang->report->query      = 'Abfrage';
$lang->report->annual     = 'Annual Summary';

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

$lang->report->assign['noassign'] = 'Nicht zugeordnet';
$lang->report->assign['assign'] = 'Zugeordnet';

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = $lang->projectCommon . ' Abweichung';
$lang->report->productSummary   = $lang->productCommon . ' Zusammenfassung';
$lang->report->bugCreate        = 'Bug gemeldet';
$lang->report->bugAssign        = 'Bug zugeordnet';
$lang->report->workload         = 'Team Arbeitslast ';
$lang->report->workloadAB       = 'Arbeitslast';
$lang->report->bugOpenedDate    = 'Bug gemeldet am';
$lang->report->beginAndEnd      = ' : von';
$lang->report->dept             = 'Abteilung';
$lang->report->deviationChart   = $lang->projectCommon . ' Abweichnungs Chart';

$lang->reportList->project->lists[10] = $lang->projectCommon . ' Abweichnung|report|projectdeviation';
$lang->reportList->product->lists[10] = $lang->productCommon . ' Zusammenfassung|report|productsummary';
$lang->reportList->test->lists[10]    = 'Bugs gemeldet|report|bugcreate';
$lang->reportList->test->lists[13]    = 'Bugs zugeordnet|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Team Arbeitslast|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = $lang->projectCommon;
$lang->report->product       = $lang->productCommon;
$lang->report->user          = 'Name';
$lang->report->bugTotal      = 'Bug';
$lang->report->task          = 'Aufgabe';
$lang->report->estimate      = 'Schätzung(h)';
$lang->report->consumed      = 'Verbraucht';
$lang->report->remain        = 'Rest';
$lang->report->deviation     = 'Abweichung';
$lang->report->deviationRate = 'Abweichungsrate';
$lang->report->total         = 'Summe';
$lang->report->to            = 'bis';
$lang->report->taskTotal     = "Summe Aufgaben";
$lang->report->manhourTotal  = "Summe Stunden";
$lang->report->validRate     = "Gültigkeitsrate";
$lang->report->validRateTips = "Lösung ist gelöst/verschoben oder der Status ist gelöst/geschlossen.";
$lang->report->unplanned     = 'Ungeplant';
$lang->report->workday       = 'Stunden/Tag';
$lang->report->diffDays      = 'Manntage';

$lang->report->typeList['default'] = 'Standard';
$lang->report->typeList['pie']     = 'Torte';
$lang->report->typeList['bar']     = 'Balken';
$lang->report->typeList['line']    = 'Linien';

$lang->report->conditions    = 'Filter:';
$lang->report->closedProduct = 'Geschlossen ' . $lang->productCommon;
$lang->report->overduePlan   = 'Fälligkeit Plan';

/* daily reminder. */
$lang->report->idAB         = 'ID';
$lang->report->bugTitle     = 'Bug-Name';
$lang->report->taskName     = 'Aufgabe-Name';
$lang->report->todoName     = 'Todo-Name';
$lang->report->testTaskName = 'Testaufgabe-Name';
$lang->report->deadline     = 'Fällig';

$lang->report->mailTitle           = new stdclass();
$lang->report->mailTitle->begin    = 'Hinweis: Sie haben';
$lang->report->mailTitle->bug      = " Bug (%s),";
$lang->report->mailTitle->task     = " Aufgaben (%s),";
$lang->report->mailTitle->todo     = " Todo (%s),";
$lang->report->mailTitle->testTask = " Test Aufgaben (%s),";

$lang->report->proVersion   = '<a href="http://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Testen Sie ZenTao Pro für mehr Informationen!</a>';
$lang->report->proVersionEn = '<a href="http://api.zentao.pm/goto.php?item=proversion&from=reportpage" target="_blank">Testen Sie ZenTao Pro für mehr Informationen!</a>';
