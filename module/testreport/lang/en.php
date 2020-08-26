<?php
$lang->testreport->common       = 'Testing Report';
$lang->testreport->browse       = 'Testing Reports';
$lang->testreport->create       = 'Create Report';
$lang->testreport->edit         = 'Edit Report';
$lang->testreport->delete       = 'Delete Report';
$lang->testreport->export       = 'Export';
$lang->testreport->exportAction = 'Export Report';
$lang->testreport->view         = 'Report Detail';
$lang->testreport->recreate     = 'ReCreate';

$lang->testreport->title       = 'Title';
$lang->testreport->product     = $lang->productCommon;
$lang->testreport->bugTitle    = 'Bug';
$lang->testreport->storyTitle  = 'Story';
$lang->testreport->project     = 'Project';
$lang->testreport->testtask    = 'Test Build';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Begin&End';
$lang->testreport->owner       = 'Owner';
$lang->testreport->members     = 'Users';
$lang->testreport->begin       = 'Begin';
$lang->testreport->end         = 'End';
$lang->testreport->stories     = 'Story Tested';
$lang->testreport->bugs        = 'Bug Tested';
$lang->testreport->builds      = 'Build Info';
$lang->testreport->goal        = 'Project Goal';
$lang->testreport->cases       = 'Case';
$lang->testreport->bugInfo     = 'Bug Distribution';
$lang->testreport->report      = 'Summary';
$lang->testreport->legacyBugs  = 'Left Bugs';
$lang->testreport->createdBy   = 'CreatedBy';
$lang->testreport->createdDate = 'CreatedDate';
$lang->testreport->objectID    = 'Object';
$lang->testreport->objectType  = 'Object Type';
$lang->testreport->profile     = 'Profile';
$lang->testreport->value       = 'Value';
$lang->testreport->none        = 'None';
$lang->testreport->all         = 'All Reports';
$lang->testreport->deleted     = 'Deleted';
$lang->testreport->selectTask  = 'Create report by request';

$lang->testreport->legendBasic       = 'Basic Info';
$lang->testreport->legendStoryAndBug = 'Test Scope';
$lang->testreport->legendBuild       = 'Test Rounds';
$lang->testreport->legendCase        = 'Linked Cases';
$lang->testreport->legendLegacyBugs  = 'Left Bugs';
$lang->testreport->legendReport      = 'Report';
$lang->testreport->legendComment     = 'Summary';
$lang->testreport->legendMore        = 'More';

$lang->testreport->bugSeverityGroups   = 'Bug Severity Distribution';
$lang->testreport->bugTypeGroups       = 'Bug Type Distribution';
$lang->testreport->bugStatusGroups     = 'Bug Status Distribution';
$lang->testreport->bugOpenedByGroups   = 'Bug ReportedBy Distribution';
$lang->testreport->bugResolvedByGroups = 'Bug ResolvedBy Distribution';
$lang->testreport->bugResolutionGroups = 'Bug Resolution Distribution';
$lang->testreport->bugModuleGroups     = 'Bug Module Distribution';
$lang->testreport->legacyBugs          = 'Left Bugs';
$lang->testreport->bugConfirmedRate    = 'Confirmed-Bug Rate (Resolution is fixed or postponed / status is resolved or closed)';
$lang->testreport->bugCreateByCaseRate = 'Bug-Reported-in-Case Rate (Bugs reported in cases / New added bugs)';

$lang->testreport->caseSummary    = 'Total <strong>%s</strong> cases. <strong>%s</strong> cases run. <strong>%s</strong> results generated. <strong>%s</strong> cases failed.';
$lang->testreport->buildSummary   = 'Tested <strong>%s</strong> builds.';
$lang->testreport->confirmDelete  = 'Do you want to delete this report?';
$lang->testreport->moreNotice     = 'More features can be extended with reference to the ZenTao extension manual, or you can contact us at renee@easysoft.ltd for customization.';
$lang->testreport->exportNotice   = "Exported By <a href='https://www.zentao.net' target='_blank' style='color:grey'>ZenTao</a>";
$lang->testreport->noReport       = "No report has been generated. Please check it later.";
$lang->testreport->foundBugTip    = "Bugs found in this build period and the affected build is in this test period.";
$lang->testreport->legacyBugTip   = "Active bugs, or bugs that are not resolved in the test period.";
$lang->testreport->fromCaseBugTip = "Bugs found from the running of cases in the test period.";
$lang->testreport->errorTrunk     = "You cannot create a Testing report for the trunk. Please modify the linked build!";
$lang->testreport->noTestTask     = "No test requests for this {$lang->productCommon}, so no reports can be generated. Please go to {$lang->productCommon} which has test requests and then generate the report.";
$lang->testreport->noObjectID     = "No test request or {$lang->projectCommon} is selected, so no report can be generated.";
$lang->testreport->moreProduct    = "Testing reports can only be generated for the same {$lang->productCommon}.";
$lang->testreport->hiddenCase     = "Hide %s use cases";

$lang->testreport->bugSummary = <<<EOD
Total <strong>%s</strong> Bugs reported <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs remained unresolved <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs found from the running of cases<a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='Resolution is resolved or delayed / status is resolved or closed'><i class='icon-help'></i></a>: <strong>%s</strong>，Bugs-reported-from-cases rate<a data-toggle='tooltip' class='text-warning' title='Bugs created from cases / bugs'><i class='icon-help'></i></a>: <strong>%s</strong>
EOD;
