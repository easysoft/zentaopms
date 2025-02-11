<?php
$lang->metric->common             = "Metric";
$lang->metric->metric             = "Metric";
$lang->metric->name               = "Metric name";
$lang->metric->stage              = "Stage";
$lang->metric->scope              = "Metric scope";
$lang->metric->object             = "Metric object";
$lang->metric->purpose            = "Metric purpose";
$lang->metric->dateType           = "Date Type";
$lang->metric->unit               = "Metric unit";
$lang->metric->alias              = "Metric alias";
$lang->metric->code               = "Metric code";
$lang->metric->desc               = "Metric desc";
$lang->metric->formula            = "Formula";
$lang->metric->when               = "Collection Method";
$lang->metric->createdBy          = "Created By";
$lang->metric->implement          = "Implement";
$lang->metric->delist             = "delist";
$lang->metric->implementedBy      = "Implemented By";
$lang->metric->offlineBy          = "Offline By";
$lang->metric->lastEdited         = "Last Edited";
$lang->metric->value              = "Value";
$lang->metric->date               = "Date";
$lang->metric->metricData         = "Metric Data";
$lang->metric->system             = "System";
$lang->metric->weekCell           = "%s,Week %s";
$lang->metric->weekS              = "Week %s";
$lang->metric->create             = "Create " . $this->lang->metric->common;
$lang->metric->edit               = "Edit " . $this->lang->metric->common;
$lang->metric->view               = 'View' . $this->lang->metric->common;
$lang->metric->afterCreate        = "After Saving";
$lang->metric->definition         = "Definition";
$lang->metric->declaration        = "Definition";
$lang->metric->customUnit         = "Custom Unit";
$lang->metric->delist             = "Delist";
$lang->metric->preview            = "Preview";
$lang->metric->metricList         = "Metric List";
$lang->metric->manage             = "Manage";
$lang->metric->exitManage         = "Exit manage";
$lang->metric->filters            = 'Filter Settings';
$lang->metric->details            = 'Details';
$lang->metric->remove             = 'Remove';
$lang->metric->zAnalysis          = 'Z Analysis';
$lang->metric->sqlStatement       = "SQL Statement";
$lang->metric->other              = "Other";
$lang->metric->collectType        = 'Collect Type';
$lang->metric->oldMetricInfo      = 'Measurement Info';
$lang->metric->collectConf        = 'Collect Configure';
$lang->metric->verifyFile         = 'Verify File';
$lang->metric->verifyResult       = 'Verify Result';
$lang->metric->publish            = 'Publish';
$lang->metric->moveFailTip        = 'Failed to move the metric file.';
$lang->metric->checkFile          = 'Check whether the metric file exists in the directory';
$lang->metric->deleteFile         = 'Please contact the administrator to delete';
$lang->metric->selectCount        = '%s metrics';
$lang->metric->testMetric         = 'Test Metric';
$lang->metric->calcTime           = 'Calculate Time';
$lang->metric->to                 = 'to';
$lang->metric->year               = 'Year';
$lang->metric->month              = 'Month';
$lang->metric->week               = 'Week';
$lang->metric->day                = 'Date';
$lang->metric->nodate             = 'Collection date';
$lang->metric->implementType      = 'Implement Type';
$lang->metric->recalculate        = 'Recalculate all metrics';
$lang->metric->setting            = 'Settings';
$lang->metric->recalculateAll     = 'Recalculate all metrics';
$lang->metric->recalculateHistory = 'Recalculate history metrics';
$lang->metric->startRecalculate   = 'Start Recalculate';
$lang->metric->recalculateAction  = 'Recalculate';
$lang->metric->recalculateBtnText = 'Recalculate';
$lang->metric->exit               = 'Exit';
$lang->metric->zentaoPath         = '【ZenTao Path】';

$lang->metric->yearFormat      = 'Year %s';
$lang->metric->weekFormat      = 'Week %s';
$lang->metric->monthDayFormat  = '%s-%s';
$lang->metric->yearMonthFormat = '%s-%s';

$lang->metric->tableHeader = array();
$lang->metric->tableHeader['project']   = 'Project';
$lang->metric->tableHeader['product']   = 'Product';
$lang->metric->tableHeader['execution'] = 'Execution';
$lang->metric->tableHeader['dept']      = 'Dept';
$lang->metric->tableHeader['user']      = 'Name';
$lang->metric->tableHeader['program']   = 'Program';

$lang->metric->placeholder = new stdclass();
$lang->metric->placeholder->select    = "Please select";
$lang->metric->placeholder->project   = "All Projects";
$lang->metric->placeholder->product   = "All Products";
$lang->metric->placeholder->execution = "All Executions";
$lang->metric->placeholder->dept      = "All Departments";
$lang->metric->placeholder->user      = "All Users";
$lang->metric->placeholder->program   = "All Program";

$lang->metric->query = new stdclass();
$lang->metric->query->action = 'Query';

$lang->metric->calcTypeList = array();
$lang->metric->calcTypeList['cron']      = 'Snapshot';
$lang->metric->calcTypeList['inference'] = 'Recalculate';

$lang->metric->calcTitleList = array();
$lang->metric->calcTitleList['cron']      = '%user% create snapshots at %date%';
$lang->metric->calcTitleList['inference'] = '%user% recalculate at %date%';

$lang->metric->query->scope = array();
$lang->metric->query->scope['project']   = 'Project';
$lang->metric->query->scope['product']   = 'Product';
$lang->metric->query->scope['execution'] = 'Execution';
$lang->metric->query->scope['dept']      = 'Dept';
$lang->metric->query->scope['user']      = 'User';
$lang->metric->query->scope['program']   = 'Program';

$lang->metric->query->yearLabels = array();
$lang->metric->query->yearLabels['3']   = '3 years';
$lang->metric->query->yearLabels['5']   = '5 years';
$lang->metric->query->yearLabels['10']  = '10 years';
$lang->metric->query->yearLabels['all'] = 'All';

$lang->metric->query->monthLabels = array();
$lang->metric->query->monthLabels['6']   = '6 months';
$lang->metric->query->monthLabels['12']  = '12 months';
$lang->metric->query->monthLabels['24']  = '24 months';
$lang->metric->query->monthLabels['36']  = '36 months';

$lang->metric->query->weekLabels = array();
$lang->metric->query->weekLabels['4']  = '4 weeks';
$lang->metric->query->weekLabels['8']  = '8 weeks';
$lang->metric->query->weekLabels['12'] = '12 weeks';
$lang->metric->query->weekLabels['16'] = '16 weeks';

$lang->metric->query->dayLabels = array();
$lang->metric->query->dayLabels['7']  = '7 days';
$lang->metric->query->dayLabels['14'] = '14 days';
$lang->metric->query->dayLabels['21'] = '21 days';
$lang->metric->query->dayLabels['28'] = '28 days';

$lang->metric->viewType = new stdclass();
$lang->metric->viewType->single   = 'Single view';
$lang->metric->viewType->multiple = 'Multiple view';

$lang->metric->descTip            = 'Enter the meaning, purpose, and impact of the metric';
$lang->metric->definitionTip      = 'Enter the calculation rules and filtering conditions of the metric';
$lang->metric->aliasTip           = 'Displayed in the metrics library as alias';
$lang->metric->collectConfText    = "%s %s %s";
$lang->metric->dirNotExist        = "Failed to create a directory. Please create a directory: <strong>%s</strong>.";
$lang->metric->fileMoveFail       = "File copy failed, please copy <strong>%s</strong> to <strong>%s</strong>.";
$lang->metric->emptyCollect       = 'There are no collect metrics at this time.';
$lang->metric->maxSelect          = 'A maximum of %s metrics can be selected';
$lang->metric->maxSelectTip       = 'Multiple metrics can be selected across a range, up to a maximum of %s.';
$lang->metric->upgradeTip         = 'This measure is supported by an earlier version. If you want to edit it, reconfigure it according to the rules for configuring the measure of the latest version. Also note that the new version of the measure no longer supports the SQL editor and cannot be referenced by the report template for the time being. Check whether you need to edit it.';
$lang->metric->saveSqlMeasSuccess = "Query success，result is：%s";
$lang->metric->monthText          = "%s day";
$lang->metric->errorDateRange     = "The start date cannot be longer than the end date";
$lang->metric->errorCalcTimeRange = "The start time of collection cannot be later than the end time of collection";
$lang->metric->updateTimeTip      = "Update snapshot time：%s";
$lang->metric->builtinMetric      = "Built-in Metric, can not delist";

$lang->metric->noDesc    = "No description available";
$lang->metric->noFormula = "No calculation rules available";
$lang->metric->noCalc    = "The PHP algorithm for this metric has not been implemented yet";
$lang->metric->noSQL     = "No SQL";

$lang->metric->noData              = "No data available.";
$lang->metric->noDataBeforeCollect = "No data is available until the time for data collection.";
$lang->metric->noDataAfterCollect  = "No data is available after the time for data collection.";

$lang->metric->legendBasicInfo  = 'Basic Information';
$lang->metric->legendCreateInfo = 'Creation and Editing Information';

$lang->metric->confirmDelete       = "Are you sure you want to delete?";
$lang->metric->confirmDelist       = "Are you sure you want to delist?";
$lang->metric->confirmDelistInUsed = "This metric has been referenced by the large screen. Are you sure you want to take it down?";
$lang->metric->confirmRecalculate  = "Recalculation results may overwrite existing metric records. Do you want to continue?";
$lang->metric->notExist            = "The measure does not exist";

$lang->metric->browse          = 'Browse Metrics';
$lang->metric->browseAction    = 'Metric List';
$lang->metric->viewAction      = 'View Metric';
$lang->metric->editAction      = 'Edit Metric';
$lang->metric->implementAction = 'Implement Metric';
$lang->metric->deleteAction    = 'Delete Metric';
$lang->metric->delistAction    = 'Delist Metric';
$lang->metric->detailsAction   = 'Metric Detail';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "Not Released";
$lang->metric->stageList['released'] = "Released";

$lang->metric->featureBar['browse']['all']      = 'All';
$lang->metric->featureBar['browse']['wait']     = 'Not Released';
$lang->metric->featureBar['browse']['released'] = 'Released';

$lang->metric->featureBar['preview']['project']   = 'Project';
$lang->metric->featureBar['preview']['product']   = 'Product';
$lang->metric->featureBar['preview']['execution'] = 'Execution';
$lang->metric->featureBar['preview']['user']      = 'Individual';
$lang->metric->featureBar['preview']['program']   = 'Program';
$lang->metric->featureBar['preview']['system']    = 'System';

$lang->metric->more        = 'More';
$lang->metric->collect     = 'My Collect';
$lang->metric->collectStar = 'Collect';

$lang->metric->oldMetric      = new stdclass();
$lang->metric->oldMetric->sql = 'SQL';
$lang->metric->oldMetric->tip = 'This is the implementation of the old metric';

$lang->metric->oldMetric->dayNames = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');

$lang->metric->moreSelects = array();

$lang->metric->unitList = array();
$lang->metric->unitList['count']      = 'Count';
$lang->metric->unitList['measure']    = 'Man-hour';
$lang->metric->unitList['hour']       = 'Hour';
$lang->metric->unitList['day']        = 'Day';
$lang->metric->unitList['manday']     = 'Man-day';
$lang->metric->unitList['percentage'] = 'Percentage';
$lang->metric->unitList['times']      = 'Times';
$lang->metric->unitList['people']     = 'People';
$lang->metric->unitList['row']        = 'Row';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']      = 'Back to List Page';
$lang->metric->afterCreateList['implement'] = 'Implement Metric';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = 'Year';
$lang->metric->dateList['month'] = 'Month';
$lang->metric->dateList['week']  = 'Week';
$lang->metric->dateList['day']   = 'Day';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "Scale Estimation";
$lang->metric->purposeList['time']  = "Time Control";
$lang->metric->purposeList['cost']  = "Cost Calculation";
$lang->metric->purposeList['hour']  = "Hourly Statistics";
$lang->metric->purposeList['qc']    = "Quality Control";
$lang->metric->purposeList['rate']  = "Enhanced Efficiency";
$lang->metric->purposeList['other'] = "Other";

$lang->metric->scopeList = array();
$lang->metric->scopeList['project']   = "Project";
$lang->metric->scopeList['product']   = "Product";
$lang->metric->scopeList['execution'] = "Execution";
$lang->metric->scopeList['user']      = "Individual";
$lang->metric->scopeList['system']    = "System";
$lang->metric->scopeList['other']     = "Other";
$lang->metric->scopeList['program']   = "Program";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']       = "Program Set";
$lang->metric->objectList['line']          = "Product Line";
$lang->metric->objectList['product']       = "Product";
$lang->metric->objectList['project']       = "Project";
$lang->metric->objectList['productplan']   = "Plan";
$lang->metric->objectList['execution']     = "Execution";
$lang->metric->objectList['release']       = "Release";
$lang->metric->objectList['epic']          = $lang->ERCommon;
$lang->metric->objectList['story']         = $lang->SRCommon;
$lang->metric->objectList['requirement']   = $lang->URCommon;
$lang->metric->objectList['task']          = "Task";
$lang->metric->objectList['bug']           = "Bug";
$lang->metric->objectList['case']          = "Test Case";
$lang->metric->objectList['user']          = "User";
$lang->metric->objectList['effort']        = "Effort";
$lang->metric->objectList['doc']           = "Document";
if(in_array($config->edition, array('biz', 'max', 'ipd'))) $lang->metric->objectList['feedback'] = "Feedback";
$lang->metric->objectList['review']        = "Review";
if($config->edition == 'ipd' && $config->vision == 'or') $lang->metric->objectList['demand'] = "Demand";
$lang->metric->objectList['codebase']      = "Repository";
$lang->metric->objectList['pipeline']      = "Pipeline";
$lang->metric->objectList['artifact']      = "Artufact";
$lang->metric->objectList['deployment']    = "Deployment";
$lang->metric->objectList['node']          = "Node";
$lang->metric->objectList['application']   = "Application";
$lang->metric->objectList['cpu']           = "CPU";
$lang->metric->objectList['memory']        = "Memory";
$lang->metric->objectList['commit']        = "Commit";
$lang->metric->objectList['mergeRequest']  = 'Merge Requests';
$lang->metric->objectList['code']          = "Code";
$lang->metric->objectList['vulnerability'] = "Vulnerability";
$lang->metric->objectList['codeAnalysis']  = "Code Analysis";
if(in_array($config->edition, array('biz', 'max', 'ipd'))) $lang->metric->objectList['ticket'] = "Ticket";
if(in_array($config->edition, array('max', 'ipd')))
{
    $lang->metric->objectList['risk']  = "Risk";
    $lang->metric->objectList['issue'] = "Issue";
    $lang->metric->objectList['qa']    = "QA";
}
$lang->metric->objectList['host']  = "Host";
$lang->metric->objectList['other'] = "Other";

$lang->metric->chartTypeList = array();
$lang->metric->chartTypeList['line'] = 'Line';
$lang->metric->chartTypeList['barX'] = 'Bar X';
$lang->metric->chartTypeList['barY'] = 'Bar Y';
$lang->metric->chartTypeList['pie']  = 'Pie';

$lang->metric->dateTypeList['nodate'] = 'No Date';
$lang->metric->dateTypeList['year']   = 'Year';
$lang->metric->dateTypeList['month']  = 'Month';
$lang->metric->dateTypeList['week']   = 'Week';
$lang->metric->dateTypeList['day']    = 'Day';

$lang->metric->filter = new stdclass();
$lang->metric->filter->common  = 'Filter';
$lang->metric->filter->scope   = 'Scope';
$lang->metric->filter->object  = 'Object';
$lang->metric->filter->purpose = 'Purpose';
$lang->metric->filter->clear   = 'Clear All';

$lang->metric->filter->clearAction = 'Clear selected %s';
$lang->metric->filter->checkedInfo = 'Selected：Scope(%s)、Object(%s)、Purpose(%s)';
$lang->metric->filter->filterTotal = 'Filter Result(%s)';

$lang->metric->implement = new stdclass();
$lang->metric->implement->common      = "Implement";
$lang->metric->implement->tip         = "Please implement the calculation logic of this metric through PHP.";
$lang->metric->implement->instruction = "Instruction";
$lang->metric->implement->downloadPHP = "Download Metric Template";

$lang->metric->implement->instructionTips = array();
$lang->metric->implement->instructionTips[] = '1.Download the measurement template file and perform coding and development operations on the file. For details, see the operation manual.<a class="btn text-primary ghost" target="_blank" href="https://www.zentao.net/book/zentaopms/1103.html">Manual>></a>';
$lang->metric->implement->instructionTips[] = '2.Please put the developed file in the following directory,<strong>Keep the file name consistent with the measurement code</strong>。<br/> <span class="label code-slate">{tmpRoot}metric</span>';

$lang->metric->verifyCustom = new stdclass();
$lang->metric->verifyCustom->checkCustomCalcExists = array();
$lang->metric->verifyCustom->checkCustomCalcExists['text']       = 'Check if the metric file exists';
$lang->metric->verifyCustom->checkCustomCalcExists['error']      = 'The metric file does not exist';

$lang->metric->verifyCustom->checkCustomCalcSyntax = array();
$lang->metric->verifyCustom->checkCustomCalcSyntax['text']       = 'Check if the metric file syntax is correct';
$lang->metric->verifyCustom->checkCustomCalcSyntax['error']      = 'The metric file syntax is incorrect';

$lang->metric->verifyCustom->checkCustomCalcClassName = array();
$lang->metric->verifyCustom->checkCustomCalcClassName['text']    = 'Check if the metric file class name is correct';
$lang->metric->verifyCustom->checkCustomCalcClassName['error']   = 'The metric file class name is incorrect';

$lang->metric->verifyCustom->checkCustomCalcClassMethod = array();
$lang->metric->verifyCustom->checkCustomCalcClassMethod['text']  = 'Check if the metric file class method is correct';
$lang->metric->verifyCustom->checkCustomCalcClassMethod['error'] = 'The metric file class method is incorrect';

$lang->metric->verifyCustom->checkCustomCalcRuntime = array();
$lang->metric->verifyCustom->checkCustomCalcRuntime['text']      = 'Check if the metric file can be executed';
$lang->metric->verifyCustom->checkCustomCalcRuntime['error']     = '';

$lang->metric->weekList = array();
$lang->metric->weekList['1'] = 'Monday';
$lang->metric->weekList['2'] = 'Tuesday';
$lang->metric->weekList['3'] = 'Wednesday';
$lang->metric->weekList['4'] = 'Thursday';
$lang->metric->weekList['5'] = 'Friday';
$lang->metric->weekList['6'] = 'Saturday';
$lang->metric->weekList['0'] = 'Sunday';

$lang->metric->old = new stdclass();

$lang->metric->old->scopeList = array();
$lang->metric->old->scopeList['project'] = 'project';
$lang->metric->old->scopeList['product'] = $lang->productCommon;
$lang->metric->old->scopeList['sprint']  = 'stage';

$lang->metric->old->purposeList = array();
$lang->metric->old->purposeList['scale']    = 'Scale';
$lang->metric->old->purposeList['duration'] = 'Duration';
$lang->metric->old->purposeList['workload'] = 'Workload';
$lang->metric->old->purposeList['cost']     = 'Cost';
$lang->metric->old->purposeList['quality']  = 'Quality';

$lang->metric->old->objectList = array();
$lang->metric->old->objectList['staff']       = 'Staff';
$lang->metric->old->objectList['finance']     = 'Finance';
$lang->metric->old->objectList['case']        = 'case';
$lang->metric->old->objectList['bug']         = 'Bug';
$lang->metric->old->objectList['review']      = 'Review';
$lang->metric->old->objectList['stage']       = 'Stage';
$lang->metric->old->objectList['program']     = 'Program';
$lang->metric->old->objectList['softRequest'] = 'SoftRequest';
$lang->metric->old->objectList['userRequest'] = 'UserRequest';

$lang->metric->old->collectTypeList = array();
$lang->metric->old->collectTypeList['crontab'] = 'Crontab';
$lang->metric->old->collectTypeList['action']  = 'Action';

$lang->metric->tips = new stdclass();
$lang->metric->tips->nameError               = 'Error in MySQL function name, please check function name.';
$lang->metric->tips->createError             = 'Failed to create a custom function for MySQL. Error message:<br/> %s';
$lang->metric->tips->noticeSelect            = 'SQL statements can only be query statements';
$lang->metric->tips->noticeBlack             = 'SQL contains the disable SQL keyword %s';
$lang->metric->tips->noticeVarName           = 'The variable name is not set';
$lang->metric->tips->noticeVarType           = 'The type of the variable %s is not set';
$lang->metric->tips->noticeShowName          = 'The show name of the variable %s is not set';
$lang->metric->tips->noticeQueryValue        = 'The query value of the variable %s is not set';
$lang->metric->tips->showNameMissed          = 'The show name of the variable %s is not set';
$lang->metric->tips->errorSql                = 'SQL statement error! Error:';
$lang->metric->tips->click2SetParams         = 'Please click on the red variable block to set parameters, and then';
$lang->metric->tips->view                    = 'View';
$lang->metric->tips->click2InsertData        = "Click <span class='ke-icon-holder'></span> to insert a metric or report";
$lang->metric->tips->noticeUnchangeable      = "[Scope], [Object], [Purpose], [Date Type], and [Code] will affect the acquisition of measurement values, cannot be changed after creation.";
$lang->metric->tips->noticeCode              = "The code must be a combination of English letters, numbers or underscores.";
$lang->metric->tips->noticeRecalculate       = "Updating data..., please do not manipulate metric data.";
$lang->metric->tips->noticeRepublish         = "If there are multiple releases, recalculate from before the last release time.";
$lang->metric->tips->banRecalculate          = "The metric has not been published or has no date type";
$lang->metric->tips->noticeDeduplication     = "Deduplication...";
$lang->metric->tips->noticeDoneDeduplication = "Deduplication done";

$lang->metric->tips->noticeRecalculateConfig = array();
$lang->metric->tips->noticeRecalculateConfig['all']    = "The system will recalculate the historical data collected by non-scheduled tasks in published metrics by default.";
$lang->metric->tips->noticeRecalculateConfig['single'] = "The system will recalculate the historical data collected by non-scheduled tasks in this metric by default.";

$lang->metric->tips->noticeRewriteHistoryLib = array();
$lang->metric->tips->noticeRewriteHistoryLib['all']    = "(When checked, the system will recalculate published metric with date type  based on historical data and overwrite all existing metric records)";
$lang->metric->tips->noticeRewriteHistoryLib['single'] = "(When checked, the system will recalculate this metric based on historical data and overwrite all existing metric values)";

$lang->metric->recalculateLog = "%s计算完成";

$lang->metric->param = new stdclass();
$lang->metric->param->varName      = 'Variable Name';
$lang->metric->param->showName     = 'Show Name';
$lang->metric->param->varType      = 'Type';
$lang->metric->param->defaultValue = 'Default Value';
$lang->metric->param->queryValue   = 'Query Value';

$lang->metric->param->typeList['input']   = 'Input';
$lang->metric->param->typeList['date']    = 'Date';
$lang->metric->param->typeList['select']  = 'Select';

$lang->metric->param->options['project'] = $lang->projectCommon . 'List';
$lang->metric->param->options['product'] = $lang->productCommon . 'List';
$lang->metric->param->options['sprint']  = $lang->projectCommon . 'List';
