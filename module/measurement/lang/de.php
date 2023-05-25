<?php
$lang->measurement->common       = 'Measurement';
$lang->measurement->setTips      = 'Interval Reminder';
$lang->measurement->scope        = 'Scope';
$lang->measurement->object       = 'Object';
$lang->measurement->purpose      = 'Purpose';
$lang->measurement->code         = 'Code';
$lang->measurement->order        = 'Order';
$lang->measurement->returns      = 'Return Type';
$lang->measurement->definition   = 'Measurement Definition';
$lang->measurement->name         = 'Measurement Name';
$lang->measurement->type         = 'Measurement Type';
$lang->measurement->params       = 'Parameter Settings';
$lang->measurement->basicMeas    = 'Basic Measurement';
$lang->measurement->deriveMeas   = 'Derived Measurement';
$lang->measurement->measList     = 'Measurement List';
$lang->measurement->reportList   = 'Report List';
$lang->measurement->saveReport   = 'Save Report';
$lang->measurement->saveReportAB = 'Save Report';
$lang->measurement->test         = 'Test Measurement';
$lang->measurement->batchEdit    = 'Batch Edit';
$lang->measurement->sqlBuilder   = 'SQL Builder';
$lang->measurement->template     = 'Report template';
$lang->measurement->model        = 'Project Model';

$lang->measurement->modelList['waterfall'] = 'Waterfall';
$lang->measurement->modelList['scrum']     = 'Scrum';

$lang->measurement->report = new stdclass;
$lang->measurement->report->name        = 'Report name';
$lang->measurement->report->program     = 'project';
$lang->measurement->report->product     = $lang->productCommon;
$lang->measurement->report->project     = 'stage';
$lang->measurement->report->createdBy   = 'CreatedBy';
$lang->measurement->report->createdDate = 'CreatedDate';

$lang->measurement->searchMeas         = 'Search Measurement';
$lang->measurement->designPHP          = 'Design PHP';
$lang->measurement->designSQL          = 'Design SQL';
$lang->measurement->initCrontabQueue   = 'Initialize Crontab queue';
$lang->measurement->execCrontabQueue   = 'Execute Crontab queue';
$lang->measurement->saveSqlMeasSuccess = 'Query successful, test result: %s';

$lang->measurement->actionConfig = "Action Config";
$lang->measurement->moduleName   = 'Module Name';
$lang->measurement->actionName   = 'Action Name';
$lang->measurement->cycleConfig  = "Cycle Config";
$lang->measurement->execTime     = "Execution Time";
$lang->measurement->cycleDay     = 'Day';
$lang->measurement->cycleWeek    = 'Week';
$lang->measurement->cycleMonth   = 'Month';
$lang->measurement->every        = 'Interval';
$lang->measurement->dayNames     = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Saturday');

$lang->measurement->cycleType['day']   = 'Every day %s';
$lang->measurement->cycleType['week']  = 'Weekly %s';
$lang->measurement->cycleType['month'] = 'Monthly %s';

$lang->measurement->scopeList[''] = '';
$lang->measurement->scopeList['project'] = 'project';
$lang->measurement->scopeList['product'] = $lang->productCommon;
$lang->measurement->scopeList['sprint']  = 'stage';

$lang->measurement->purposeList[''] = '';
$lang->measurement->purposeList['scale']    = 'Scale';
$lang->measurement->purposeList['duration'] = 'Duration';
$lang->measurement->purposeList['workload'] = 'Workload';
$lang->measurement->purposeList['cost']     = 'Cost';
$lang->measurement->purposeList['quality']  = 'Quality';

$lang->measurement->objectList[''] = '';
$lang->measurement->objectList['staff']       = 'Staff';
$lang->measurement->objectList['finance']     = 'Task';
$lang->measurement->objectList['case']        = 'Case';
$lang->measurement->objectList['bug']         = 'Bug';
$lang->measurement->objectList['review']      = 'Review';
$lang->measurement->objectList['stage']       = 'Stage';
$lang->measurement->objectList['program']     = 'Project';
$lang->measurement->objectList['softRequest'] = 'Story';
$lang->measurement->objectList['userRequest'] = 'Requirements';

$lang->measurement->typeList['basic']  = $lang->measurement->basicMeas;

$lang->measurement->sysData = $lang->measurement->typeList;
$lang->measurement->sysData['report'] = 'A single report';

$lang->measurement->collectTypeList['crontab'] = 'Crontab';
$lang->measurement->collectTypeList['action']  = 'Action';

$lang->measurement->buildinParams = new stdclass;
$lang->measurement->buildinParams->program = 'Project';
$lang->measurement->buildinParams->day     = 'Day';

$lang->measurement->codeExistence    = 'Measurement code: %s already exists.';
$lang->measurement->codeEmpty        = 'The measurement code for ID: %s cannot be empty.';
$lang->measurement->nameEmpty        = 'The measurement name for ID: %s cannot be empty.';
$lang->measurement->unitEmpty        = 'The measurement unit for ID: %s cannot be empty.';
$lang->measurement->definitionEmpty  = 'The measurement definition for ID: %s cannot be empty.';

$lang->measurement->noticeScope      = 'Notice Scope';
$lang->measurement->design           = 'Design';
$lang->measurement->browse           = 'Measurements';
$lang->measurement->browseBasic      = 'Basic Measurement';
$lang->measurement->browseDerivation = 'Derived Measurement';
$lang->measurement->create           = 'Create Measurement';
$lang->measurement->createBasic      = $lang->measurement->create;
$lang->measurement->editBasic        = 'Edit Basic Measurement';
$lang->measurement->editDerivation   = 'Edit Derived Measurement';
$lang->measurement->delete           = 'Delete';
$lang->measurement->deleted          = 'Deleted';
$lang->measurement->collectType      = 'Collect Type';
$lang->measurement->collectConf      = 'Collect Config';
$lang->measurement->collectedBy      = 'CollectedBy';
$lang->measurement->unit             = 'Unit';
$lang->measurement->save             = 'Save';
$lang->measurement->saveSuccess      = 'SaveSuccess';
$lang->measurement->reDesign         = 'The redesign';
$lang->measurement->confirmDelete    = 'Do you want to delete?';
$lang->measurement->options          = 'Options';
$lang->measurement->id               = 'ID';
$lang->measurement->createTemplate   = 'Creating a composite template';
$lang->measurement->createSingle     = 'Creating a single template';
$lang->measurement->editTemplate     = 'Edit Template';
$lang->measurement->viewTemplate     = 'View Template';
$lang->measurement->content          = 'Content';
$lang->measurement->addMeas          = 'Add Measurement';
$lang->measurement->dataSource       = 'Data Source';
$lang->measurement->dataName         = 'Data Name';
$lang->measurement->setSQL           = 'Set SQL';
$lang->measurement->setPHP           = 'Set PHP';
$lang->measurement->callSqlBuilder   = 'Call Sql Builder';
$lang->measurement->query            = 'Query';
$lang->measurement->byQuery          = 'Search';
$lang->measurement->call             = 'Call';
$lang->measurement->queryResult      = 'Query Result:';
$lang->measurement->setParams        = 'Set Params';
$lang->measurement->createdBy        = 'CreatedBy';
$lang->measurement->createdDate      = 'CreatedDate';
$lang->measurement->purpose          = 'Purpose';
$lang->measurement->aim              = 'Aim';
$lang->measurement->analyst          = 'Analyst';
$lang->measurement->analysisMethod   = 'Analysis Method';

$lang->measurement->placeholder = new stdclass();
$lang->measurement->placeholder->sql = 'Please complete the MySQL custom function statement.';
$lang->measurement->placeholder->php = 'Please follow the code required by the system. The class name cannot be changed and must include the GET method.';
$lang->measurement->codeTemplate = <<<EOT
<?php
class %sModel extends model
{
    public function get(\$param1)
    {
        return \$param1 + \$param2;
    }
}
?>
EOT;

$lang->measurement->sqlTemplate = <<<EOT
CREATE FUNCTION `%s`(%s) RETURNS
BEGIN

END
EOT;

$lang->measurement->param = new stdclass();
$lang->measurement->param->varName      = 'Variable Name';
$lang->measurement->param->showName     = 'Show Name';
$lang->measurement->param->varType      = 'Type';
$lang->measurement->param->defaultValue = 'Default Value';
$lang->measurement->param->queryValue   = 'Query Value';

$lang->measurement->param->typeList['input']   = 'Input';
$lang->measurement->param->typeList['date']    = 'Date';
$lang->measurement->param->typeList['select']  = 'Select';

$lang->measurement->param->options['project'] = 'Project List';
$lang->measurement->param->options['product'] = $lang->productCommon . 'List';
$lang->measurement->param->options['sprint']  = $lang->executionCommon . 'List';

$lang->measurement->tips = new stdclass();
$lang->measurement->tips->nameError        = 'Error in MySQL function name, please check function name.';
$lang->measurement->tips->createError      = 'Failed to create a custom function for MySQL. Error message:<br/> %s';
$lang->measurement->tips->noticeSelect     = 'SQL statements can only be query statements';
$lang->measurement->tips->noticeBlack      = 'SQL contains the disable SQL keyword %s';
$lang->measurement->tips->noticeVarName    = 'The variable name is not set';
$lang->measurement->tips->noticeVarType    = 'The type of the variable %s is not set';
$lang->measurement->tips->noticeShowName   = 'The show name of the variable %s is not set';
$lang->measurement->tips->noticeQueryValue = 'The query value of the variable %s is not set';
$lang->measurement->tips->showNameMissed   = 'The show name of the variable %s is not set';
$lang->measurement->tips->errorSql         = 'SQL statement error! Error:';
$lang->measurement->tips->click2SetParams  = 'Please click on the red variable block to set parameters, and then';
$lang->measurement->tips->view             = 'View';
$lang->measurement->tips->click2InsertData = "Click <span class='ke-icon-holder'></span> to insert a metric or report";

$lang->basicmeas = new stdclass();
$lang->basicmeas->name       = $lang->measurement->name;
$lang->basicmeas->code       = $lang->measurement->code;
$lang->basicmeas->unit       = $lang->measurement->unit;
$lang->basicmeas->definition = $lang->measurement->definition;

$lang->derivemeas = new stdclass();
$lang->derivemeas->name    = $lang->measurement->name;
$lang->derivemeas->purpose = $lang->measurement->purpose;

$lang->meastemplate = new stdclass();
$lang->meastemplate->id          = 'ID';
$lang->meastemplate->single      = 'A Single Template';
$lang->meastemplate->complex     = 'Composite Template';
$lang->meastemplate->name        = 'Template Name';
$lang->meastemplate->desc        = 'Description';
$lang->meastemplate->content     = 'Content';
$lang->meastemplate->createdBy   = 'CreatedBy';
$lang->meastemplate->createdDate = 'CreatedDate';
$lang->meastemplate->addedBy     = 'AddedBy';
$lang->meastemplate->addedDate   = 'AddedDate';

$lang->meastemplate->actions = array();
$lang->measurement->actions[] = 'Before unit testing';
$lang->measurement->actions[] = 'After the test is complete';
$lang->measurement->actions[] = 'Test report review completed';
$lang->measurement->actions[] = 'Test Plan Review';
$lang->measurement->actions[] = 'Milestone report after review';
$lang->measurement->actions[] = 'After Milestone Review';
$lang->measurement->actions[] = 'Quantified project monitoring process';
$lang->measurement->actions[] = 'Upon completion of stories';
$lang->measurement->actions[] = 'After requirements stories';
$lang->measurement->actions[] = 'After compiling the stories specification';
$lang->measurement->actions[] = 'Stories specification review';
$lang->measurement->actions[] = 'After the stories milestone';
$lang->measurement->actions[] = 'After the project plan review';
$lang->measurement->actions[] = 'The project plan review is completed';

$lang->measurement->actions['project.close'] = 'End of the project';
