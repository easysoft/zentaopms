<?php
$lang->testreport->common   = 'Test Report';
$lang->testreport->browse   = 'Test Report';
$lang->testreport->create   = 'Create Report';
$lang->testreport->edit     = 'Edit Report';
$lang->testreport->delete   = 'Delete';
$lang->testreport->export   = 'Export';
$lang->testreport->view     = 'Report Details';
$lang->testreport->recreate = 'Re-Create';

$lang->testreport->title       = 'Title';
$lang->testreport->bugTitle    = 'Bug Title';
$lang->testreport->storyTitle  = 'Story Title';
$lang->testreport->project     = 'Project';
$lang->testreport->testtask    = 'Test Build';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Start&End';
$lang->testreport->owner       = 'Owner';
$lang->testreport->members     = 'Members';
$lang->testreport->begin       = 'Begin';
$lang->testreport->end         = 'End';
$lang->testreport->stories     = 'Stories of Test';
$lang->testreport->bugs        = 'Bugs';
$lang->testreport->builds      = 'Build Info';
$lang->testreport->goal        = 'Project Goal';
$lang->testreport->cases       = 'Cases';
$lang->testreport->bugInfo     = 'Bug Distribution';
$lang->testreport->report      = 'Summary';
$lang->testreport->legacyBugs  = 'Legacy Bugs';
$lang->testreport->createdDate = 'Date';
$lang->testreport->objectID    = 'Object';
$lang->testreport->profile     = 'Profile';
$lang->testreport->value       = 'Value';
$lang->testreport->none        = 'None';
$lang->testreport->all         = 'All Report';
$lang->testreport->deleted     = 'Deleted';

$lang->testreport->legendBasic       = 'Basic Info';
$lang->testreport->legendStoryAndBug = 'Stories and Bugs of Test';
$lang->testreport->legendBuild       = 'Build Info';
$lang->testreport->legendCase        = 'Case executed';
$lang->testreport->legendLegacyBugs  = 'Legacy Bugs';
$lang->testreport->legendReport      = 'Report';
$lang->testreport->legendComment     = 'Sum up';
$lang->testreport->legendMore        = 'More';

$lang->testreport->bugSeverityGroups   = 'Bug Severity Distribution';
$lang->testreport->bugTypeGroups       = 'Bug Type Distribution';
$lang->testreport->bugStatusGroups     = 'Bug Status Distribution';
$lang->testreport->bugOpenedByGroups   = 'Bug Creator Distribution';
$lang->testreport->bugResolvedByGroups = 'Bug Solver Distribution';
$lang->testreport->bugResolutionGroups = 'Bug Resolution Distribution';
$lang->testreport->bugModuleGroups     = 'Bug Module Distribution';
$lang->testreport->legacyBugs          = 'Legacy Bugs';
$lang->testreport->bugConfirmedRate    = 'Confirmed Bug Rate (Solution is fixed or postponed / status is resolved or closed)';
$lang->testreport->bugCreateByCaseRate = 'Bug Created in Case Rate (Bugs created in cases / Newly added bugs)';

$lang->testreport->caseSummary    = ' <strong>%s</strong> cases in Total : <strong>%s</strong> performed, <strong>%s</strong> results, <strong>%s</strong> failed.';
$lang->testreport->buildSummary   = 'Tested <strong>%s</strong> build.';
$lang->testreport->confirmDelete  = 'Do you want tot delete this report?';
$lang->testreport->moreNotice     = 'More features can be extended with reference to the ZenTao extension mechanism, or you can contact us for customization.';
$lang->testreport->exportNotice   = "Export By <a href='http://www.zentao.net' target='_blank' style='color:grey'>ZenTaoPMS</a>";
$lang->testreport->noReport       = "No report has been generated. Please check it later.";
$lang->testreport->foundBugTip    = "Bugs created in this build and created in the test period.";
$lang->testreport->legacyBugTip   = "Active Bugs, or the resolved beyond the test period.";
$lang->testreport->fromCaseBugTip = "Bugs created after case-failure in the test period.";
$lang->testreport->errorTrunk     = "The trunk version cannot create a test report. Please modify the associated version!";
$lang->testreport->moreProduct    = "A test report can only be generated for the same product.";

$lang->testreport->bugSummary = <<<EOD
<strong>%s</strong> Bug(s) generated in total <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-exclamation-sign'></i></a>,
<strong>%s</strong> Bug(s) remained unresolve <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-exclamation-sign'></i></a>,
<strong>%s</strong> Bug(s) failure of case <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-exclamation-sign'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='resolution is resolved or delayedg / status is resolved or closed'><i class='icon-exclamation-sign'></i></a>: <strong>%s</strong>，Bugs created from case Rate<a data-toggle='tooltip' class='text-warning' title='Bugs created from cases / bugs'><i class='icon-exclamation-sign'></i></a>: <strong>%s</strong>
EOD;
