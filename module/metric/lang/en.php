<?php
$lang->metric->common        = "Metric";
$lang->metric->name          = "Metric Name";
$lang->metric->stage         = "Stage";
$lang->metric->scope         = "Metric Scope";
$lang->metric->object        = "Metric Object";
$lang->metric->purpose       = "Metric Purpose";
$lang->metric->unit          = "Metric Unit";
$lang->metric->code          = "Metric Code";
$lang->metric->desc          = "Metric Description";
$lang->metric->definition    = "Definition";
$lang->metric->formula       = "Formula";
$lang->metric->when          = "Collection Method";
$lang->metric->createdBy     = "Created By";
$lang->metric->implementedBy = "Implemented By";
$lang->metric->offlineBy     = "Offline By";
$lang->metric->lastEdited    = "Last Edited";
$lang->metric->value         = "Value";
$lang->metric->date          = "Date";
$lang->metric->metricData    = "Metric Data";
$lang->metric->system        = "system";
$lang->metric->create        = "Create " . $this->lang->metric->common;
$lang->metric->afterCreate   = "After Create";
$lang->metric->definition    = "Definition";
$lang->metric->customUnit    = "Custom";

$lang->metric->descTip       = 'Please enter the meaning, purpose and use of the measurement item';
$lang->metric->definitionTip = 'Please enter the calculation rules and filtering conditions of the measurement items';

$lang->metric->noDesc    = "No description";
$lang->metric->noFormula = "No formula";
$lang->metric->noCalc    = "Metric's PHP algorithm is not implemented yet";

$lang->metric->legendBasicInfo  = 'Basic info';
$lang->metric->legendCreateInfo = 'Create and edit info';

$lang->metric->confirmDelete = "Are you sure you want to delete?";

$lang->metric->browseAction = 'Metric List';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "Unpublished";
$lang->metric->stageList['released'] = "Published";

$lang->metric->unitList = array();
$lang->metric->unitList['single'] = 'Single';
$lang->metric->unitList['hour']   = 'Hour';
$lang->metric->unitList['day']    = 'Day';
$lang->metric->unitList['manday'] = 'Man Day';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = 'Year';
$lang->metric->dateList['month'] = 'Month';
$lang->metric->dateList['week']  = 'Week';
$lang->metric->dateList['day']   = 'Day';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "Scale Estimation";
$lang->metric->purposeList['time']  = "Time Control";
$lang->metric->purposeList['cost']  = "Cost Calculation";
$lang->metric->purposeList['hour']  = "Hour Tracking";
$lang->metric->purposeList['qc']    = "Quality Control";
$lang->metric->purposeList['rate']  = "Efficiency Improvement";

$lang->metric->scopeList = array();
$lang->metric->scopeList['system']    = "System";
$lang->metric->scopeList['program']   = "Program";
$lang->metric->scopeList['product']   = "Product";
$lang->metric->scopeList['project']   = "Project";
$lang->metric->scopeList['execution'] = "Execution";
$lang->metric->scopeList['dept']      = "Team";
$lang->metric->scopeList['user']      = "User";
$lang->metric->scopeList['code']      = "Code base";
$lang->metric->scopeList['pipeline']  = "Pipeline";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']     = "Program";
$lang->metric->objectList['line']        = "Product Line";
$lang->metric->objectList['product']     = "Product";
$lang->metric->objectList['project']     = "Project";
$lang->metric->objectList['productplan'] = "Product Plan";
$lang->metric->objectList['execution']   = "Execution";
$lang->metric->objectList['release']     = "Release";
$lang->metric->objectList['story']       = "Development Story";
$lang->metric->objectList['requirement'] = "User Requirement";
$lang->metric->objectList['task']        = "Task";
$lang->metric->objectList['bug']         = "Bug";
$lang->metric->objectList['case']        = "Testcase";
$lang->metric->objectList['user']        = "User";
$lang->metric->objectList['effort']      = "Effort";
$lang->metric->objectList['doc']         = "Doc";
if($config->edition != 'open')
{
    $lang->metric->objectList['feedback']    = "Feedback";
    $lang->metric->objectList['risk']        = "Risk";
    $lang->metric->objectList['issue']       = "Issue";
}
