<?php
/**
 * The report module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: en.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->report->common     = 'Report';
$lang->report->index      = 'Report index';
$lang->report->list       = 'Report list';
$lang->report->item       = 'Item';
$lang->report->value      = 'Value';
$lang->report->percent    = 'Percent';
$lang->report->undefined  = 'Undefined';
$lang->report->time       = 'Time';

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

$lang->report->singleColor[] = 'F6BD0F';

$lang->report->projectDeviation = 'Project deviation';
$lang->report->productInfo      = 'Product information';
$lang->report->bugSummary       = 'Bug summary';
$lang->report->bugAssign        = 'Bug assign';
$lang->report->workload         = 'Workload';

$lang->reportList->project->lists[10] = 'Project deviation|report|projectdeviation';
$lang->reportList->product->lists[10] = 'Product information|report|productinfo';
$lang->reportList->test->lists[10]    = 'Bug summary|report|bugsummary';
$lang->reportList->test->lists[13]    = 'Bug assign|report|bugassign';
$lang->reportList->staff->lists[10]   = 'Workload|report|workload';

$lang->report->id            = 'ID';
$lang->report->project       = 'Project';
$lang->report->product       = 'Product';
$lang->report->user          = 'Username';
$lang->report->bug           = 'Bug';
$lang->report->task          = 'Tasks';
$lang->report->estimate      = 'Estimate';
$lang->report->consumed      = 'Consumed';
$lang->report->remain        = 'Remain';
$lang->report->manhour       = 'Manhour';
$lang->report->deviation     = 'Deviation';
$lang->report->deviationRate = 'Deviation rate';
$lang->report->stories       = 'Stories';
$lang->report->bugs          = 'Bugs';
$lang->report->devConsumed   = 'Develop';
$lang->report->testConsumed  = 'Test';
$lang->report->devTestRate   = 'Dev/Test';
$lang->report->details       = 'Details';
$lang->report->total         = 'Total';
$lang->report->to            = 'to';
$lang->report->taskTotal     = "Task Total";
$lang->report->manhourTotal  = "Manhour Total";
$lang->report->bugTotal      = "%s Bugs";
$lang->report->validRate     = "Valid Rate";
$lang->report->validRateTips = "Resolution is fixed or postponed/status is resolved or closed";
$lang->report->unplanned     = 'unplanned';

/* daily reminder. */
$lang->report->idAB  = 'ID';
$lang->report->bugTitle = 'Bug Title';
$lang->report->taskName = 'Task Name';
$lang->report->todoName = 'Todo Name';

$lang->report->mailtitle        = new stdclass();
$lang->report->mailtitle->begin = 'Notice : Your';
$lang->report->mailtitle->bug   = " Bugs(%s),";
$lang->report->mailtitle->task  = " Tasks(%s),";
$lang->report->mailtitle->todo  = " Todos(%s),";

$lang->report->proVersion = '<a href="http://api.zentao.net/goto.php?item=proversion&from=reportpage" target="_blank">Try pro version for more!</a>';
