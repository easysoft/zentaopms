<?php
$lang->testreport->common   = 'Test Report';
$lang->testreport->browse   = 'Reports';
$lang->testreport->create   = 'Create';
$lang->testreport->edit     = 'Edit';
$lang->testreport->delete   = 'Delete';
$lang->testreport->export   = 'Export';
$lang->testreport->view     = 'View';
$lang->testreport->recreate = 'Re-Create';

$lang->testreport->title       = 'Title';
$lang->testreport->project     = 'Project';
$lang->testreport->testtask    = 'Test Build';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Start and end';
$lang->testreport->owner       = 'Owner';
$lang->testreport->members     = 'Members';
$lang->testreport->begin       = 'Begin';
$lang->testreport->end         = 'End';
$lang->testreport->stories     = 'Stories of Test';
$lang->testreport->bugs        = 'Bugs';
$lang->testreport->builds      = 'Build Info';
$lang->testreport->goal        = 'Product Goal';
$lang->testreport->cases       = 'Cases';
$lang->testreport->bugInfo     = 'Bug Distribution';
$lang->testreport->report      = 'Report';
$lang->testreport->legacyBugs  = 'Legacy Bugs';
$lang->testreport->createdDate = 'Created Date';
$lang->testreport->objectID    = 'Object';
$lang->testreport->profile     = 'Profile';
$lang->testreport->value       = 'Value';
$lang->testreport->none        = 'None';
$lang->testreport->all         = 'All Report';

$lang->testreport->legendBasic       = 'Basic Info';
$lang->testreport->legendStoryAndBug = 'Stories and Bug of Test';
$lang->testreport->legendBuild       = 'Build Info';
$lang->testreport->legendCase        = 'Information for executing cases';
$lang->testreport->legendLegacyBugs  = 'Legacy Bugs';
$lang->testreport->legendReport      = 'Report';
$lang->testreport->legendComment     = 'Sum up';
$lang->testreport->legendMore        = 'More';

$lang->testreport->bugSeverityGroups   = 'Bug Severity Distribution';
$lang->testreport->bugTypeGroups       = 'Bug Type Distribution';
$lang->testreport->bugStatusGroups     = 'Bug Status Distribution';
$lang->testreport->bugOpenedByGroups   = 'Bug CreatedBy Distribution';
$lang->testreport->bugResolvedByGroups = 'Bug ResolvedBy Distribution';
$lang->testreport->bugResolutionGroups = 'Bug Resolution Distribution';
$lang->testreport->bugModuleGroups     = 'Bug Module Distribution';
$lang->testreport->legacyBugs          = 'Legacy Bugs';
$lang->testreport->bugConfirmedRate    = 'Confirmed Bug Rate (Solution is fixed or postponed / status is resolved or closed)';
$lang->testreport->bugCreateByCaseRate = 'Bug Created in Case Rate (Bugs created in cases / Newly added bugs)';

$lang->testreport->caseSummary    = ' <strong>%s</strong> cases in total, <strong>%s</strong> cases performed, <strong>%s</strong> results generated, <strong>%s</strong> cases failed.';
$lang->testreport->buildSummary   = 'Tested <strong>%s</strong> build.';
$lang->testreport->confirmDelete  = 'Do you want tot delete this report?';
$lang->testreport->moreNotice     = 'More features can be extended with reference to the ZenTao extension mechanism, or you can contact us for customization.';
$lang->testreport->exportNotice   = "Export By <a href='http://www.zentao.net' target='_blank' style='color:grey'>ZenTaoPMS</a>";
$lang->testreport->noReport       = "The report has not yet been generated. Please check it later.";
$lang->testreport->foundBugTip    = "OpenBuild in builds and create time in test time within the range of Bug number.";
$lang->testreport->legacyBugTip   = "The Bug state is activated, or the settling time of Bug is at the end of the test.";
$lang->testreport->fromCaseBugTip = "Bug created after failure of use case in test time range.";
$lang->testreport->errorTrunk     = "The trunk version cannot create a test report. Please modify the associated version!";

$lang->testreport->bugSummary = <<<EOD
<strong>%s</strong> Bug(s) generated in total <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-info-sign'></i></a>,
<strong>%s</strong> Bug(s) remained unresolve <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-info-sign'></i></a>,
<strong>%s</strong> Bug(s) failure of case <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-info-sign></i></a>.
Confirmed bug rate（bug resolution is resolved or delayedg status is resolved or closed): <strong>%s</strong>，Bug created in caserate(Bugs created in cases / Newly generated bugs): <strong>%s</strong>'
EOD;
