<?php
/**
 * The weekly module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     weekly
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->weekly->common   = $lang->projectCommon . ' Weekly';
$lang->weekly->index    = 'Weekly Overview';
$lang->weekly->progress = 'Progress';
$lang->weekly->workload = 'Workload';
$lang->weekly->total    = 'Total';

$lang->weekly->reportTtitle   = $lang->projectCommon . ': % s Weekly (Week % s)';
$lang->weekly->summary        = $lang->projectCommon . ' Progress';
$lang->weekly->finished       = 'Work finished this week (100% completed work)';
$lang->weekly->postponed      = 'Work unfinished this week';
$lang->weekly->nextWeek       = 'Work planned for next week';
$lang->weekly->workloadByType = 'Workload Summary';

$lang->weekly->term    = 'Reporting Cycle';
$lang->weekly->project = $lang->projectCommon . ' Name';
$lang->weekly->master  = 'Project Manager ';
$lang->weekly->staff   = 'Weekly Effort';

$lang->weekly->weekDesc       = 'Week % s (% s ~% s)';
$lang->weekly->progress       = 'Progress of the ' . $lang->projectCommon;
$lang->weekly->analysisResult = 'Analysis';
$lang->weekly->cost           = $lang->projectCommon . ' Cost';

$lang->weekly->pv = 'Planned Value(PV)';
$lang->weekly->ev = 'Earned Value(EV)';
$lang->weekly->ac = 'Actual Cost(AC)';
$lang->weekly->sv = 'Schedule Variance(SV%)';
$lang->weekly->cv = 'Cost Variance(CV%)';

$lang->weekly->totalCount = 'Total : %u tasks';

$lang->weekly->exportWeeklyReport = 'Export Weekly Report';

$lang->weekly->reportHelpNotice = <<<EOD
<h2>PV Planned Value</h2>
Calculation method:
<br />1) The estimated start date and end date of the task are within the range of the start and end dates of this week, and the estimated work hours are accumulated
<br />2) The estimated start date and end date of the task are before the start and end date of this week, and the estimated work hours are accumulated
<br />3) The scheduled start date of the task is earlier than the start date of this week, the end date is later than the start date of this week, and the scheduled work hours are accumulated when it is earlier than the end date of this week
<br />4) The expected start date of a task is later than the start date of this week, less than the end date of this week, and the end date is later than the end date of this week. The cumulative (expected work hours of the task ÷ task duration days) x the days from the expected start date of the task to the end date of this week
<br />5) The estimated start date of a task is equal to the start date of this week, and the end date is greater than the end date of this week. The cumulative (estimated work hours of the task ÷ task duration days) x the number of days from the estimated start date of the task to the end date of this week
<br />6) The scheduled start date of the task is earlier than the start date of this week, and the end date is equal to the end date of this week. The scheduled work hours are accumulated
<br />7) If the expected start date of a task is earlier than the start date of this week and the end date is later than the end date of this week, the cumulative (expected work hours of the task ÷ task duration days) x the days from the expected start date of the task to the end date of this week
<p>Statistical range:</p>
1) Start date of this week: 00:00:00 on Monday End date of this week: determined according to the calculation of working days and holidays
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Exclude deleted tasks
<br />4) Exclude canceled tasks
<br />5) Exclude tasks in deleted execution
<br />6) No expected start date is filled in the task. The expected start date defaults to the planned start date of the phase to which the task belongs
<br />7) No expected end date is filled in the task. By default, the expected end date is the planned completion date of the phase to which the task belongs
<br />8) Calculation formula only calculates working days
<h2>EV Earned Value</h2>
Calculation method:
<br />1) The task status is done, and the estimated work hours are accumulated
<br />2) The task status is closed and the reason for closing is done, and the estimated work hours are accumulated
<br />3) The task status is in doing, suspended, and the estimated work hours are accumulated × progress
<p>Statistical range:</p>
1) Tasks whose work consumption is not 0 before the end date of this week
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Exclude deleted tasks
<br />4) Exclude canceled tasks
<br />5) Exclude tasks in deleted execution
<br />6) Progress= consumed man hours ÷(consumed man hours+remaining man hours)
<h2>AC Actual Cost</h2>
Calculation method:
<br />1) Accumulate all consumed work hours before the end date of this week
<p>Statistical range:</p>
1) All consumed man hours include task, requirement, bug, use case, version, test sheet, problem, risk, document and review time
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Including deleted tasks, requirements, bugs, use cases, versions, test sheets, problems, risks, documents, and time consuming of reviews
<br />4) Including the time consumption of deleted tasks, requirements, bugs, use cases, versions, test sheets and documents in execution
<br />5) Including the time consumption of cancelled tasks, problems and risks
<h2>SV(%) Schedule Variance</h2>
Calculation method: SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) Cost Variance</h2>
Calculation method: CV(%) = -1 * (1 - (EV / AC))%
EOD;
$lang->weekly->blockHelpNotice = <<<EOD
<h2>Progress this week</h2>
Calculation method:
<br />1) Project progress = consumed task hours / (consumed task hours + remaining task hours) * 100%
<p>Statistical range:</p>
1) Only the working hour consumption data of the task is counted
<br />2) To avoid repeated calculation, the task working hours only include child tasks, not parent tasks
<br />3) Including the working hours consumed by canceled tasks
<br />4) Excluding the working hours consumed in deleted tasks
<br />5) Excluding the working hours consumed in deleted tasks in progress
<br />6) Excluding the remaining working hours of canceled tasks
<br />7) Excluding the remaining working hours of deleted tasks in progress
<h2>PV Planned Value</h2>
Calculation method:
<br />1) The estimated start date and end date of the task are within the range of the start and end dates of this week, and the estimated work hours are accumulated
<br />2) The estimated start date and end date of the task are before the start and end date of this week, and the estimated work hours are accumulated
<br />3) The scheduled start date of the task is earlier than the start date of this week, the end date is later than the start date of this week, and the scheduled work hours are accumulated when it is earlier than the end date of this week
<br />4) The expected start date of a task is later than the start date of this week, less than the end date of this week, and the end date is later than the end date of this week. The cumulative (expected work hours of the task ÷ task duration days) x the days from the expected start date of the task to the end date of this week
<br />5) The estimated start date of a task is equal to the start date of this week, and the end date is greater than the end date of this week. Accumulate (estimated work hours of a task ÷ task duration days) × The number of days from the expected start of the task to the end of this week
<br />6) The scheduled start date of the task is earlier than the start date of this week, and the end date is equal to the end date of this week. The scheduled work hours are accumulated
<br />7) The expected start date of a task is earlier than the start date of this week, and the end date is later than the end date of this week. Accumulate (the expected work hours of the task ÷ the task duration days) × The number of days from the expected start of the task to the end of this week
<p>Statistical range:</p>
1) Start date of this week: 00:00:00 on Monday End date of this week: determined according to the calculation of working days and holidays
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Exclude deleted tasks
<br />4) Exclude canceled tasks
<br />5) Exclude tasks in deleted execution
<br />6) No expected start date is filled in the task. The expected start date defaults to the planned start date of the phase to which the task belongs
<br />7) No expected end date is filled in the task. By default, the expected end date is the planned completion date of the phase to which the task belongs
<br />8) Calculation formula only calculates working days
<h2>EV Earned Value</h2>
Calculation method:
<br />1) The task status is done, and the estimated work hours are accumulated
<br />2) The task status is closed and the reason for closing is done, and the estimated work hours are accumulated
<br />3) The task status is in doing, suspended, and the estimated work hours are accumulated ×progress
<p>Statistical range:</p>
1) Tasks whose work consumption is not 0 before the end date of this week
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Exclude deleted tasks
<br />4) Exclude canceled tasks
<br />5) Exclude deleted tasks in progress
<br />6) Progress=consumed man hours ÷ (consumed man hours+remaining man hours)
<h2>AC Actual Cost</h2>
Calculation method:
<br />1) Accumulate all consumed work hours before the end date of this week
<p>Statistical range:</p>
1) All consumed man hours include task, requirement, bug, use case, version, test sheet, problem, risk, document and review time
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Including deleted tasks, requirements, bugs, use cases, versions, test sheets, problems, risks, documents, and time consuming of reviews
<br />4) Including the time consumption of deleted tasks, requirements, bugs, use cases, versions, test sheets and documents in execution
<br />5) Including the time consumption of cancelled tasks, problems and risks
<h2>SV(%) Schedule Variance</h2>
Calculation method: SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) Schedule Variance</h2>
Calculation method: CV(%) = -1 * (1 - (EV / AC))%
EOD;
