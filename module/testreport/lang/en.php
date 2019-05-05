<?php
$lang->testreport->common   = 'QA Report';
$lang->testreport->browse   = 'QA Report';
$lang->testreport->create   = 'Create Report';
$lang->testreport->edit     = 'Edit Report';
$lang->testreport->delete   = 'Delete';
$lang->testreport->export   = 'Export';
$lang->testreport->view     = 'Details';
$lang->testreport->recreate = 'Re-Create';

$lang->testreport->title       = 'Title';
$lang->testreport->bugTitle    = 'Bug Title';
$lang->testreport->storyTitle  = 'Story Title';
$lang->testreport->project     = 'Project';
$lang->testreport->testtask    = 'Test Build';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Begin&End';
$lang->testreport->owner       = 'Owner';
$lang->testreport->members     = 'Members';
$lang->testreport->begin       = 'Begin';
$lang->testreport->end         = 'End';
$lang->testreport->stories     = 'Test Story';
$lang->testreport->bugs        = 'Test Bug';
$lang->testreport->builds      = 'Build Info';
$lang->testreport->goal        = 'Project Goal';
$lang->testreport->cases       = 'Case';
$lang->testreport->bugInfo     = 'Bug Distribution';
$lang->testreport->report      = 'Summary';
$lang->testreport->legacyBugs  = 'Legacy Bug';
$lang->testreport->createdDate = 'Created';
$lang->testreport->objectID    = 'Object';
$lang->testreport->profile     = 'Profile';
$lang->testreport->value       = 'Value';
$lang->testreport->none        = 'None';
$lang->testreport->all         = 'All Reports';
$lang->testreport->deleted     = 'Deleted';

$lang->testreport->legendBasic       = 'Basic Info.';
$lang->testreport->legendStoryAndBug = 'Story&Bug';
$lang->testreport->legendBuild       = 'Build Info';
$lang->testreport->legendCase        = 'Linked Case';
$lang->testreport->legendLegacyBugs  = 'Legacy Bug';
$lang->testreport->legendReport      = 'Report';
$lang->testreport->legendComment     = 'Summary';
$lang->testreport->legendMore        = 'More';

$lang->testreport->bugSeverityGroups   = 'Bug Severity Distribution';
$lang->testreport->bugTypeGroups       = 'Bug Type Distribution';
$lang->testreport->bugStatusGroups     = 'Bug Status Distribution';
$lang->testreport->bugOpenedByGroups   = 'Bug ReportedBy Distribution';
$lang->testreport->bugResolvedByGroups = 'Bug SolvedBy Distribution';
$lang->testreport->bugResolutionGroups = 'Bug Solution Distribution';
$lang->testreport->bugModuleGroups     = 'Bug Module Distribution';
$lang->testreport->legacyBugs          = 'Legacy Bugs';
$lang->testreport->bugConfirmedRate    = 'Confirmed Bug Rate (Solution is fixed or postponed / status is solved or closed)';
$lang->testreport->bugCreateByCaseRate = 'Bug Generated in Case Rate (Bugs reported in cases / Newly added bugs)';

$lang->testreport->caseSummary    = ' <strong>%s</strong> cases in Total : <strong>%s</strong> performed, <strong>%s</strong> results, <strong>%s</strong> failed.';
$lang->testreport->buildSummary   = 'Tested <strong>%s</strong> build.';
$lang->testreport->confirmDelete  = 'Do you want to delete this report?';
$lang->testreport->moreNotice     = 'More features can be extended with reference to the ZenTao extension manual, or you can contact us at renee@easysoft.ltd for customization.';
$lang->testreport->exportNotice   = "Exported By <a href='https://www.zentao.net' target='_blank' style='color:grey'>ZenTao</a>";
$lang->testreport->noReport       = "No report has been generated. Please check it later.";
$lang->testreport->foundBugTip    = "Bugs generated in this build and generated in the test period.";
$lang->testreport->legacyBugTip   = "Active bugs, or the solved bugs that is not in the test period.";
$lang->testreport->fromCaseBugTip = "Bugs generated due to the failed case executions in the test period.";
$lang->testreport->errorTrunk     = "You cannot create a Test report for the trunk. Please modify the linked build!";
$lang->testreport->moreProduct    = "Test reports can only be generated for the same product.";

$lang->testreport->bugSummary = <<<EOD
<strong>%s</strong> Bug(s) reported in total <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bug(s) remained unsolved <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bug(s) generated due to the failure of case executions <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='Solution is solved or delayed / status is solved or closed'><i class='icon-help'></i></a>: <strong>%s</strong>，Bugs reported from case rate<a data-toggle='tooltip' class='text-warning' title='Bugs created from cases / bugs'><i class='icon-help'></i></a>: <strong>%s</strong>
EOD;
