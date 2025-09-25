<?php
/**
 * The weekly module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     weekly
 * @version     $Id
 * @link        https://www.zentao.net
 */
$lang->weekly->common   = 'Report';
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

$lang->weekly->term            = 'Reporting Cycle';
$lang->weekly->project         = $lang->projectCommon . ' Name';
$lang->weekly->master          = 'Project Manager ';
$lang->weekly->staff           = 'Weekly Effort';
$lang->weekly->projectTemplate = "{$lang->projectCommon} Weekly Report Template";

$lang->weekly->weekDesc       = 'Week % s (% s ~% s)';
$lang->weekly->progress       = 'Progress of the ' . $lang->projectCommon;
$lang->weekly->analysisResult = 'Analysis';
$lang->weekly->cost           = $lang->projectCommon . ' Cost';

$lang->weekly->pv = 'Planned Value(PV)';
$lang->weekly->ev = 'Earned Value(EV)';
$lang->weekly->ac = 'Actual Cost(AC)';
$lang->weekly->sv = 'Schedule Variance(SV%)';
$lang->weekly->cv = 'Cost Variance(CV%)';

$lang->weekly->totalCount  = 'Total : %u tasks';
$lang->weekly->builtinDesc = "The system's built-in {$lang->projectCommon} weekly report template automatically generates this week's report under the {$lang->projectCommon} every Monday.";

$lang->weekly->exportWeeklyReport = 'Export Weekly Report';

$lang->weekly->builtInScopes = array();
$lang->weekly->builtInScopes['rnd']  = array();
$lang->weekly->builtInScopes['rnd']['project'] = 'Project';

$lang->weekly->builtInCategoryList['month']     = 'Monthly Report';
$lang->weekly->builtInCategoryList['week']      = 'Weekly Report';
$lang->weekly->builtInCategoryList['day']       = 'Daily Report';
$lang->weekly->builtInCategoryList['milestone'] = 'Milestone';

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

$lang->weekly->builtinRawContent = '{"type":"page","meta":{"id":"mKJhETwxpP","title":"Project Weekly Report Template","createDate":1758524215597,"tags":[]},"blocks":{"type":"block","id":"leP1pQM_0N","flavour":"affine:page","version":2,"props":{"title":{"$blocksuite:internal:text$":true,"delta":[{"insert":"Project Weekly Report Template"}]}},"children":[{"type":"block","id":"cDel0u6OKK","flavour":"affine:note","version":1,"props":{"xywh":"[0,0,498,92]","background":"--affine-note-background-white","index":"a0","lockedBySelf":false,"hidden":false,"displayMode":"both","edgeless":{"style":{"borderRadius":8,"borderSize":4,"borderStyle":"none","shadowType":"--affine-note-shadow-box"}}},"children":[{"type":"block","id":"57JpxtRtgl","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":" ","attributes":{"holder":{"id":"-bGoKXonda","name":"weekly_term","text":"Report Term","hint":"Filter: “Date Range”BetweenThis Week","data":{"type":"weekly_term","blockID":1538,"hint":"Filter: “Date Range”BetweenThis Week","text":"Report Term"}}}},{"insert":"weekly report:"},{"insert":" ","attributes":{"holder":{"id":"ZZ2iJ1NbSm","name":"property_name","text":"Name","hint":"Name"}}},{"insert":" is managed by the project manager "},{"insert":" ","attributes":{"holder":{"id":"No8p_noVvo","name":"property_PM","text":"Manager","hint":"Manager"}}},{"insert":", the number of people involved is "},{"insert":" ","attributes":{"holder":{"id":"yMx6IXU_PN","name":"weekly_staff","text":"Staff Number","hint":"Filter: “Date Range”BetweenThis Week","data":{"type":"weekly_staff","blockID":1539,"hint":"Filter: “Date Range”BetweenThis Week","text":"Staff Number"}}}}]},"collapsed":false},"children":[]},{"type":"block","id":"u_7TkQplvX","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_progress_summary}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_progress_summary&blockID=__TML_ZENTAOCHART__{project_progress_summary}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Project Progress Summary"}},"children":[]},{"type":"block","id":"JF8LjhZ00l","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_finished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_finished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_finished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Finished Tasks"}},"children":[]},{"type":"block","id":"vLxMdWbsaL","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_unfinished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_unfinished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_unfinished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Unfinished Tasks"}},"children":[]},{"type":"block","id":"2kIWtGbWIc","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_workplan}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_workplan&blockID=__TML_ZENTAOCHART__{task_basicStatistic_workplan}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Work Plan"}},"children":[]},{"type":"block","id":"YQQsS51bpa","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_basicStatistic_workload}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_basicStatistic_workload&blockID=__TML_ZENTAOCHART__{project_basicStatistic_workload}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Project Plan Workload Statistic"}},"children":[]},{"type":"block","id":"woAbzWK8vw","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[]},"collapsed":false},"children":[]}]}]}}';
