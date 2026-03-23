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
$lang->weekly->index    = 'Weekly Report Overview';
$lang->weekly->progress = 'Progress';
$lang->weekly->workload = 'Workload';
$lang->weekly->total    = 'Total';

$lang->weekly->reportTtitle   = $lang->projectCommon . ': % s Weekly Report (Week % s)';
$lang->weekly->summary        = $lang->projectCommon . ' Progress';
$lang->weekly->finished       = 'Work Completed This Week (100% Finished Tasks)';
$lang->weekly->postponed      = 'Incomplete Work This Week';
$lang->weekly->nextWeek       = 'Work Plan for Next Week';
$lang->weekly->workloadByType = 'Workload Summary';

$lang->weekly->term            = 'Reporting Period';
$lang->weekly->project         = $lang->projectCommon . ' Name';
$lang->weekly->master          = 'Project Manager ';
$lang->weekly->staff           = 'Participants This Week';
$lang->weekly->projectTemplate = "{$lang->projectCommon} Weekly Report Template";

$lang->weekly->weekDesc       = 'Week %s (%s – %s)';
$lang->weekly->progress       = $lang->projectCommon . 'Progress';
$lang->weekly->analysisResult = 'Analysis';
$lang->weekly->cost           = $lang->projectCommon . ' Cost';

$lang->weekly->pv = 'Planned Value(PV)';
$lang->weekly->ev = 'Earned Value(EV)';
$lang->weekly->ac = 'Actual Cost(AC)';
$lang->weekly->sv = 'Schedule Variance(SV%)';
$lang->weekly->cv = 'Cost Variance(CV%)';

$lang->weekly->totalCount  = 'Total of %u tasks.';
$lang->weekly->builtinDesc = "A built‑in {$lang->projectCommon} weekly report template automatically creates this week’s report every Monday under the corresponding {$lang->projectCommon}。";

$lang->weekly->exportWeeklyReport = 'Export Weekly Report';

$lang->weekly->builtInScopes = array();
$lang->weekly->builtInScopes['rnd']  = array();
$lang->weekly->builtInScopes['rnd']['project'] = 'Project';

$lang->weekly->builtInCategoryList['month']     = 'Monthly Report';
$lang->weekly->builtInCategoryList['week']      = 'Weekly Report';
$lang->weekly->builtInCategoryList['day']       = 'Daily Report';
$lang->weekly->builtInCategoryList['milestone'] = 'Milestone Report';

$lang->weekly->reportHelpNotice = <<<EOD
<h2>PV — Planned Value </h2>
Calculation logic:
<br />1) Sum the estimated hours of tasks whose planned start and end dates both fall within this week’s start and end dates.
<br />2) Sum the estimated hours of tasks whose planned start and end dates are both before this week’s start date.
<br />3) Sum the estimated hours of tasks whose planned start date is earlier than this week’s start date, and the planned end date is between this week’s start and end dates.
<br />4) For tasks whose planned start date is after this week’s start date but before this week’s end date, and planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from planned start to this week’s end date).
<br />5) For tasks whose planned start date equals this week’s start date and planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from planned start to this week’s end date).
<br />6) For tasks whose planned start date is earlier than this week’s start date and planned end date equals this week’s end date, sum the estimated hours.
<br />7) For tasks whose planned start date is earlier than this week’s start date and planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from this week’s start to this week’s end date).

<p><strong>Scope of Calculation:</strong></p>
1) This week’s start date: Monday 00:00:00; the end date is determined based on working days and holidays.
<br />2) To avoid double counting, only sub‑tasks are included; parent tasks are excluded.
<br />3) Deleted tasks are excluded.
<br />4) Canceled tasks are excluded.
<br />5) Deleted executions are excluded.
<br />6) If the task has no planned start date, use the planned start date of its parent phase.
<br />7) If the task has no planned end date, use the planned finish date of its parent phase.
<br />8) The calculation considers only working days.

<h2>EV — Earned Value</h2>
Calculation logic:
<br />1) For tasks marked as “Completed,” sum the estimated hours.
<br />2) For tasks marked as “Closed” with the close reason “Completed,” sum the estimated hours.
<br />3) For tasks “Doing” or “On Hold,” sum (Estimated Hours × Completion Percentage).

<p><strong>Scope of Calculation:</strong></p>
1) Tasks that have non‑zero spent hours before this week’s end date.
<br />2) To avoid double counting, only sub‑tasks are included; parent tasks are excluded.
<br />3) Deleted tasks are excluded.
<br />4) Canceled tasks are excluded.
<br />5) Deleted executions are excluded.
<br />6) Completion Percentage = Cost Hours ÷ (Cost Hours + Hours Left).

<h2>AC — Actual Cost</h2>
Calculation logic:
<br />1) Sum all spent hours before this week’s end date.

<p><strong>Scope of Calculation:</strong></p>
1) Include spent hours from all work items: tasks, stories, bugs, test cases, builds, test requests, issues, risks, documents, and reviews.
<br />2) To avoid double counting, only sub‑tasks are included; parent tasks are excluded.
<br />3) Include spent hours from deleted tasks, stories, bugs, test cases, builds, test requests, issues, risks, documents, and reviews.
<br />4) Include spent hours from deleted executions of tasks, stories, bugs, test cases, builds, and documents.
<br />5) Include spent hours of canceled tasks, issues, and risks.

<h2>SV(%) — Schedule Variance </h2>
Formula: SV(%) = −1 × (1 − (EV / PV))%

<h2>CV(%) — Cost Variance </h2>
Formula: CV(%) = −1 × (1 − (EV / AC))%;
EOD;

$lang->weekly->blockHelpNotice = '<h2>Progress This Week</h2>
Calculation logic: 
<br />1) Project Progress = (Cost Hours on Tasks ÷ (Cost Hours on Tasks + Remaining Task Hours)) × 100%

<p><strong>Scope of Calculation:</strong></p>
1) Only task work‑hour data is included.
<br />2) To avoid double counting, only sub‑tasks are included; parent tasks are excluded.
<br />3) Include spent hours of canceled tasks.
<br />4) Exclude spent hours of deleted tasks.
<br />5) Exclude spent hours of deleted executions.
<br />6) Exclude remaining hours of canceled tasks.
<br />7) Exclude remaining hours of deleted executions.

<h2>PV — Planned Value</h2>
Calculation logic:
<br />1) Sum the estimated hours of tasks whose planned start and end dates both fall within this week’s start and end dates.
<br />2) Sum the estimated hours of tasks whose planned start and end dates are both before this week’s start date.
<br />3) Sum the estimated hours of tasks whose planned start date is earlier than this week’s start date, and planned end date is between this week’s start and end dates.
<br />4) For tasks whose planned start date is after this week’s start date but before this week’s end date, and whose planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from planned start to this week’s end date).
<br />5) For tasks whose planned start date equals this week’s start date and whose planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from planned start to this week’s end date).  
<br />6) For tasks whose planned start date is earlier than this week’s start date and whose planned end date equals this week’s end date, sum the estimated hours.
<br />7) For tasks whose planned start date is earlier than this week’s start date and whose planned end date is after this week’s end date, sum (Estimated Hours ÷ Task Duration in days) × (Days from this week’s start to this week’s end date).

<p><strong>Scope of Calculation:</strong></p>
1) Weekly start date: Monday 00:00:00. The end date is determined based on working days and public holidays.
<br />2) To avoid double counting, include only sub‑tasks and exclude parent tasks.
<br />3) Exclude deleted tasks.
<br />4) Exclude canceled tasks.
<br />5) Exclude deleted executions.
<br />6) If a task has no planned start date, use the planned start date of its parent phase.
<br />7) If a task has no planned end date, use the planned finish date of its parent phase.
<br />8) The calculation considers only working days.

<h2>EV — Earned Value </h2>
Calculation logic:
<br />1) For tasks marked as “Completed,” sum the estimated hours.
<br />2) For tasks marked as “Closed” with the close reason “Completed,” sum the estimated hours.
<br />3) For tasks in “Doing” or “On Hold” status, sum (Estimated Hours × Completion Percentage).

<p><strong>Scope of Calculation:</strong></p>
1) Include tasks with non‑zero spent hours before this week’s end date.
<br />2) To avoid double counting, include only sub‑tasks and exclude parent tasks.
<br />3) Exclude deleted tasks.
<br />4) Exclude canceled tasks.
<br />5) Exclude deleted executions.
<br />6) Completion Percentage = Cost Hours ÷ (Cost Hours + Hours Left).

<h2>AC — Actual Cost </h2>
Calculation logic:
<br />1) Sum all spent hours before this week’s end date.

<p><strong>Scope of Calculation:</strong></p>
1) Include spent hours from tasks, stories, bugs, test cases, builds, test requests, issues, risks, documents, and reviews.
<br />2) To avoid double counting, include only sub‑tasks and exclude parent tasks.
<br />3) Include spent hours from deleted tasks, stories, bugs, test cases, builds, test requests, issues, risks, documents, and reviews.
<br />4) Include spent hours from deleted executions of tasks, stories, bugs, test cases, builds, and documents.
<br />5) Include spent hours of canceled tasks, issues, and risks.

<h2>SV(%) — Schedule Variance</h2>
Formula: SV(%) = −1 × (1 − (EV ÷ PV))%

<h2>CV(%) — Cost Variance</h2>
Formula: CV(%) = −1 × (1 − (EV ÷ AC))%';

$lang->weekly->builtinRawContent = '{"type":"page","meta":{"id":"mKJhETwxpP","title":"Project Weekly Report Template","createDate":1758524215597,"tags":[]},"blocks":{"type":"block","id":"leP1pQM_0N","flavour":"affine:page","version":2,"props":{"title":{"$blocksuite:internal:text$":true,"delta":[{"insert":"Project Weekly Report Template"}]}},"children":[{"type":"block","id":"cDel0u6OKK","flavour":"affine:note","version":1,"props":{"xywh":"[0,0,498,92]","background":"--affine-note-background-white","index":"a0","lockedBySelf":false,"hidden":false,"displayMode":"both","edgeless":{"style":{"borderRadius":8,"borderSize":4,"borderStyle":"none","shadowType":"--affine-note-shadow-box"}}},"children":[{"type":"block","id":"57JpxtRtgl","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[{"insert":" ","attributes":{"holder":{"id":"-bGoKXonda","name":"weekly_term","text":"Report Duration","hint":"Filter: Date Range is within this week.","data":{"type":"weekly_term","blockID":1538,"hint":"Filter: Date Range is within this week.","text":"Report Duration"}}}},{"insert":"Weekly Report"},{"insert":" ","attributes":{"holder":{"id":"ZZ2iJ1NbSm","name":"property_name","text":"Project Name","hint":"Project Name"}}},{"insert":"by project manager"},{"insert":" ","attributes":{"holder":{"id":"No8p_noVvo","name":"property_PM","text":"Manager","hint":"Manager"}}},{"insert":"Managed, participants"},{"insert":" ","attributes":{"holder":{"id":"yMx6IXU_PN","name":"weekly_staff","text":"Participants","hint":"Filter: Data Range is within this week","data":{"type":"weekly_staff","blockID":1539,"hint":"Filter: Data Range is within this week","text":"Participants"}}}}]},"collapsed":false},"children":[]},{"type":"block","id":"u_7TkQplvX","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_progress_summary}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_progress_summary&blockID=__TML_ZENTAOCHART__{project_progress_summary}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Project Progress"}},"children":[]},{"type":"block","id":"JF8LjhZ00l","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_finished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_finished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_finished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Completed Tasks Overview"}},"children":[]},{"type":"block","id":"vLxMdWbsaL","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_unfinished}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_unfinished&blockID=__TML_ZENTAOCHART__{task_basicStatistic_unfinished}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Uncompleted Tasks Overview"}},"children":[]},{"type":"block","id":"2kIWtGbWIc","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{task_basicStatistic_workplan}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=task_basicStatistic_workplan&blockID=__TML_ZENTAOCHART__{task_basicStatistic_workplan}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Work Plan"}},"children":[]},{"type":"block","id":"YQQsS51bpa","flavour":"affine:embed-zui-custom","version":1,"props":{"index":"a0","xywh":"[0,0,0,0]","lockedBySelf":false,"rotate":0,"content":{"exportUrl":"exportZentaoChart___TML_ZENTAOCHART__{project_basicStatistic_workload}","fetcher":[{"module":"reporttemplate","method":"ajaxZentaoChart","params":"type=project_basicStatistic_workload&blockID=__TML_ZENTAOCHART__{project_basicStatistic_workload}"}],"clearBeforeLoad":false,"isTemplate":true,"title":"Planned Value Summary"}},"children":[]},{"type":"block","id":"woAbzWK8vw","flavour":"affine:paragraph","version":1,"props":{"align":"left","type":"text","text":{"$blocksuite:internal:text$":true,"delta":[]},"collapsed":false},"children":[]}]}]}}';
