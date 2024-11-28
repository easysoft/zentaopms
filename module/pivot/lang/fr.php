<?php
/**
 * The pivot module French file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: de.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->pivot->index        = 'Home';
$lang->pivot->list         = 'Liste';
$lang->pivot->preview      = 'View pivot table';
$lang->pivot->create       = 'Create pivot table';
$lang->pivot->edit         = 'Edit pivot table';
$lang->pivot->browse       = 'Browse pivot table';
$lang->pivot->delete       = 'Delete pivot table';
$lang->pivot->design       = 'Design pivot table';
$lang->pivot->export       = 'Export pivot table';
$lang->pivot->query        = 'Query';
$lang->pivot->browseAction = 'Design in Pivot Table';
$lang->pivot->designAB     = 'Design';
$lang->pivot->exportType   = 'Export Type';
$lang->pivot->exportRange  = 'Export Range';
$lang->pivot->story        = 'Story';
$lang->pivot->clear        = 'Clear';
$lang->pivot->keep         = 'Keep';
$lang->pivot->new          = 'New';
$lang->pivot->newVersion   = 'New version';
$lang->pivot->version      = 'Version';
$lang->pivot->viewVersion  = 'View version';

$lang->pivot->accessDenied  = 'You do not have access to this pivot';
$lang->pivot->acl = 'Access Control';
$lang->pivot->aclList['open']    = 'Public (with pivot view permissions and dimension permissions can access it)';
$lang->pivot->aclList['private'] = 'Private (Only creators and whitelisted users with dimension permissions can access it)';

$lang->pivot->otherLang = new stdclass();
$lang->pivot->otherLang->product       = 'Product';
$lang->pivot->otherLang->productStatus = 'Product Status';
$lang->pivot->otherLang->productType   = 'Product type';

$lang->pivot->cancelAndBack = 'Cancel save and back';

$lang->pivot->deleteTip         = 'Are you sure you want to delete it ?';
$lang->pivot->disableVersionTip = 'The pivot table in draft mode does not support the version list popup at the moment.';

$lang->pivot->rangeList['current'] = 'Current Page';
$lang->pivot->rangeList['all']     = 'All Data';

$lang->pivot->id          = 'ID';
$lang->pivot->name        = 'Name';
$lang->pivot->dataset     = 'Dataset';
$lang->pivot->dataview    = 'Data';
$lang->pivot->type        = 'Type';
$lang->pivot->config      = 'Config parameter';
$lang->pivot->desc        = 'Description';
$lang->pivot->xaxis       = 'Xaxis';
$lang->pivot->yaxis       = 'Yaxis';
$lang->pivot->group       = 'Group';
$lang->pivot->metric      = 'Metric';
$lang->pivot->column      = 'Column';
$lang->pivot->field       = 'Related Field';
$lang->pivot->operator    = 'Operator';
$lang->pivot->orderby     = 'Order By';
$lang->pivot->order       = 'Order';
$lang->pivot->add         = 'Add';
$lang->pivot->valOrAgg    = 'Value/Aggregate';
$lang->pivot->value       = 'Value';
$lang->pivot->agg         = 'Aggregate';
$lang->pivot->display     = 'Display Name';
$lang->pivot->filterValue = 'Filter value';
$lang->pivot->save        = 'Save';
$lang->pivot->cancel      = 'Cancel';
$lang->pivot->run         = 'RUN QUERY';
$lang->pivot->must        = 'Please select';
$lang->pivot->split       = 'Split';
$lang->pivot->chooseField = 'Choose field';
$lang->pivot->aggType     = 'Aggregate type';
$lang->pivot->other       = 'Other';
$lang->pivot->publish     = 'Release';
$lang->pivot->draft       = 'Save as draft';
$lang->pivot->draftIcon   = 'draft';
$lang->pivot->nextStep    = 'Next step';
$lang->pivot->saveSetting = 'Save setting';
$lang->pivot->add         = 'Add';
$lang->pivot->baseSetting = 'Base setting';
$lang->pivot->setLang     = 'Set Langs';
$lang->pivot->toDesign    = 'To Design';
$lang->pivot->toPreview   = 'Exit Design';
$lang->pivot->variable    = 'Variable';
$lang->pivot->varCode     = 'Code';
$lang->pivot->varLabel    = 'Var Label';
$lang->pivot->monopolize  = 'Self';
$lang->pivot->varNameTip  = 'Input letters';
$lang->pivot->item        = 'Eintrag';
$lang->pivot->percent     = '%';
$lang->pivot->undefined   = 'Undefiniert';
$lang->pivot->project     = $lang->projectCommon;
$lang->pivot->PO          = 'PO';
$lang->pivot->showPivot   = 'Show Pivot table';
$lang->pivot->showOrigin  = 'Show Origin Data';
$lang->pivot->empty       = 'Empty';

$lang->pivot->showOriginItem = 'Show origin item';
$lang->pivot->recTotalTip    = '<strong> %s </strong> items in total';
$lang->pivot->recPerPageTip  = " <strong>%s</strong> per page";

$lang->pivot->showOriginPlaceholder = new stdclass();
$lang->pivot->showOriginPlaceholder->slice    = 'No need to configure';
$lang->pivot->showOriginPlaceholder->stat     = 'No need to configure';
$lang->pivot->showOriginPlaceholder->showMode = 'No need to configure';

$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';
$lang->pivot->colors[] = 'AFD8F8';

$lang->pivot->assign['noassign'] = 'Unassigned';
$lang->pivot->assign['assign']   = 'Assigned';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common} Deviation";
$lang->pivot->productSummary   = $lang->productCommon . ' Summary';
$lang->pivot->bugCreate        = 'Bug Reported Summary';
$lang->pivot->bugAssign        = 'Bug Assigned Summary';
$lang->pivot->workload         = 'Team Workload Summary';
$lang->pivot->workloadAB       = 'Workload';
$lang->pivot->bugOpenedDate    = 'Bug reported from';
$lang->pivot->beginAndEnd      = ' From';
$lang->pivot->begin            = ' Begin';
$lang->pivot->end              = ' End';
$lang->pivot->dept             = 'Department';
$lang->pivot->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . ' Summary|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common} Deviation|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bug Reported Summary|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bug Assigned Summary|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Team Workload Summary|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->executionCommon;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'User';
$lang->pivot->bugTotal      = 'Bug';
$lang->pivot->task          = 'Task';
$lang->pivot->estimate      = 'Estimates';
$lang->pivot->consumed      = 'Cost';
$lang->pivot->remain        = 'Left';
$lang->pivot->deviation     = 'Deviation';
$lang->pivot->deviationRate = 'Deviation Rate';
$lang->pivot->total         = 'Total';
$lang->pivot->to            = 'to';
$lang->pivot->taskTotal     = "Total Tasks";
$lang->pivot->manhourTotal  = "Total Hours";
$lang->pivot->validRate     = "Valid Rate";
$lang->pivot->validRateTips = "Resolution is Resolved/Postponed or status is Resolved/Closed.";
$lang->pivot->unplanned     = 'Unplanned';
$lang->pivot->workday       = 'Hours/Day';
$lang->pivot->diffDays      = 'days';

$lang->pivot->typeList['default'] = 'Default';
$lang->pivot->typeList['pie']     = 'Pie';
$lang->pivot->typeList['bar']     = 'Bar';
$lang->pivot->typeList['line']    = 'Line';

$lang->pivot->conditions    = 'Filter by:';
$lang->pivot->closedProduct = 'Closed ' . $lang->productCommon . 's';
$lang->pivot->overduePlan   = 'Expired Plans';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug Name';
$lang->pivot->taskName     = 'Task Name';
$lang->pivot->todoName     = 'Todo Name';
$lang->pivot->testTaskName = 'Request Name';
$lang->pivot->deadline     = 'Deadline';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day. For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview'] = array();

$lang->pivot->moreSelects['preview'] = array();
$lang->pivot->moreSelects['preview']['more'] = array();

$lang->pivot->showProduct = 'All' . $lang->productCommon . 'Statistics';
$lang->pivot->showProject = 'All' . $lang->projectCommon . 'Statistics';

$lang->pivot->columnIndex  = 'col.%s';
$lang->pivot->deleteColumn = 'Are you sure to delete this column?';
$lang->pivot->addColumn    = 'Add column';
$lang->pivot->slice        = 'Select slice field';
$lang->pivot->stat         = 'Calculate value';
$lang->pivot->showMode     = 'Display value';
$lang->pivot->showTotal    = 'Display row sum';
$lang->pivot->colLabel     = '{$stat} of {$field}';
$lang->pivot->colShowMode  = ' (%s)';

$lang->pivot->errorList = new stdclass();
$lang->pivot->errorList->cantequal = '%s can not be equal to the %s, please redesign';

$lang->pivot->pie = new stdclass();
$lang->pivot->pie->group  = 'Section group';
$lang->pivot->pie->metric = 'Section numeric';
$lang->pivot->pie->stat   = 'Aggregate type';

$lang->pivot->cluBarX = new stdclass();
$lang->pivot->cluBarX->xaxis = 'Xaxis';
$lang->pivot->cluBarX->yaxis = 'Yaxis';
$lang->pivot->cluBarX->stat  = 'Aggregate type';

$lang->pivot->cluBarY = new stdclass();
$lang->pivot->cluBarY->yaxis = 'Xaxis';
$lang->pivot->cluBarY->xaxis = 'Yaxis';
$lang->pivot->cluBarY->stat  = 'Aggregate type';

$lang->pivot->radar = new stdclass();
$lang->pivot->radar->xaxis = 'Dimension';
$lang->pivot->radar->yaxis = 'Polar axis';

$lang->pivot->line = $lang->pivot->cluBarX;

$lang->pivot->stackedBar  = $lang->pivot->cluBarX;
$lang->pivot->stackedBarY = $lang->pivot->cluBarY;

$lang->pivot->browseGroup = 'Manage Group';
$lang->pivot->allGroup    = 'All Group';
$lang->pivot->noGroup     = 'No Group';
$lang->pivot->groupName   = 'GroupName';
$lang->pivot->manageGroup = 'Manage Group';
$lang->pivot->dragAndSort = 'Drag to order';
$lang->pivot->editGroup   = 'Edit Group';
$lang->pivot->deleteGroup = 'Delete Group';
$lang->pivot->childTitle  = 'Child Group';

$lang->pivot->filter          = 'Filter';
$lang->pivot->resultFilter    = 'Result Filter';
$lang->pivot->queryFilter     = 'Query Filter';
$lang->pivot->noName          = 'Unnamed';
$lang->pivot->filterName      = 'Name';
$lang->pivot->showAs          = 'Show as';
$lang->pivot->default         = 'Default';
$lang->pivot->unlimited       = 'Unlimited';
$lang->pivot->colon           = 'To';
$lang->pivot->legendBasicInfo = 'Basic info';
$lang->pivot->legendConfig    = 'Global Setting';

$lang->pivot->fieldTypeList = array();
$lang->pivot->fieldTypeList['input']    = 'Text';
$lang->pivot->fieldTypeList['date']     = 'Date';
$lang->pivot->fieldTypeList['datetime'] = 'Time';
$lang->pivot->fieldTypeList['select']   = 'Dropdown';

$lang->pivot->count      = 'Count';
$lang->pivot->project    = 'Project';
$lang->pivot->customer   = 'Customer';
$lang->pivot->cusBuild   = 'Customer Build';
$lang->pivot->period     = 'Period';
$lang->pivot->purpose    = 'Purpose';
$lang->pivot->stage      = 'Stage';
$lang->pivot->users      = 'Users';
$lang->pivot->testtasks  = 'Testtasks';
$lang->pivot->comment    = 'Comment';
$lang->pivot->major      = 'Major';
$lang->pivot->conclusion = 'Conclusion';
$lang->pivot->result     = 'Result';
$lang->pivot->caseCount  = 'Cases';
$lang->pivot->runCount   = 'Run';
$lang->pivot->runRate    = 'Run Rate';
$lang->pivot->manpower   = 'Manpower';
$lang->pivot->bugs       = 'Bugs';
$lang->pivot->day        = 'Day';
$lang->pivot->reportDate = 'Report Date';
$lang->pivot->hours      = 'Hours';
$lang->pivot->pastDays   = 'Past Days';

$lang->pivot->saveAsDataview = 'Save as Custom Table';

$lang->pivot->confirmDelete = 'Do you want to delete this pivot table?';
$lang->pivot->confirmLeave  = 'The current step is not saved. Are you sure you want to leave?';
$lang->pivot->nameEmpty     = '『Name』should not be blank';
$lang->pivot->notEmpty      = 'Can\'t be empty.';

$lang->pivot->noPivotTip      = 'After you save the Settings, you can display the pivot table';
$lang->pivot->filterEmptyVal  = 'Use filters to view data';
$lang->pivot->noQueryTip      = 'No filter.';
$lang->pivot->noPivot         = 'No Pivot table';
$lang->pivot->noDrillTip      = 'No Data Drill.';
$lang->pivot->dataError       = '"%s" is not valid';
$lang->pivot->noChartSelected = 'Please select one pivot table.';
$lang->pivot->beginGtEnd      = 'Begin time should not be >= end time.';
$lang->pivot->resetSettings   = 'The configuration of the query data has been modified, requiring redesign of the pivot table, whether to continue.';
$lang->pivot->clearSettings   = 'The configuration of the query data has been modified, whether to clear the pivot table and save.';
$lang->pivot->draftSave       = 'The pivot table has been published and will be in draft state. Do you want to continue?';
$lang->pivot->cannotAddQuery  = 'Result filter has been added, query filter cannot be added.';
$lang->pivot->cannotAddResult = 'Query filter has been added, result filter cannot be added.';
$lang->pivot->cannotNextStep  = 'Please click Query Update data before proceeding to the next step.';
//$lang->pivot->cannotAddDrill  = 'GROUP BY exists in the query statement or filters are configured. Therefore, data cannot be configured to drill down';
$lang->pivot->permissionDenied = 'The %s directory does not have enough permissions. Run chmod 777 %s to change the permissions.';

$lang->pivot->drillModalTip       = <<<EOT
1. Select the columns to be drilled and the target objects to be drilled. The system automatically generates SQL statements based on your selection.
2. Adjust the automatically generated SQL statement according to the query statement displayed in the gray panel below (the SQL query statement in the first step [Query data]), and configure the corresponding query conditions.
3. Click Preview to view the drill-down data for your configuration.
4. Click Save to complete the drill down configuration.
EOT;
$lang->pivot->drillConditionTip = <<<EOT
1.Configure search conditions based on the row group field and slice column field configured in Step 2 and the filter field configured in Step 3.
2.The query result field drop-down list displays the result set field in Query Data in the first step.
EOT;

$lang->pivot->step1QueryTip       = 'For subsequent configuration data, ensure that the id field of the query object is included in the query result set.';
$lang->pivot->drillingTip         = 'The system will automatically configure some drilling columns for you and display them here. You can check and adjust them, or add data drilling configuration for other columns.';
$lang->pivot->queryConditionTip   = 'According to the SQL in Step 1, adjust the query conditions in drill down; Ensure that the id field of the query object is included in the query result set to display the drill down content correctly.';
$lang->pivot->drillSQLTip         = 'You can copy the query from step 1 on the right here and modify it';
$lang->pivot->drillResultEmptyTip = 'Click the "preview" button, you can view the drill down results here.';
$lang->pivot->previewResultTip    = 'The drill results will display the object-related fields, and only 10 rows of data are displayed by default.';
$lang->pivot->emptyDrillTip       = 'Empty drill data';

$lang->pivot->emptyGroupError       = 'The group cannot be empty.';
$lang->pivot->emptyColumnFieldError = 'Column fields cannot be null.';
$lang->pivot->emptyColumnStatError  = 'The calculation mode cannot be null.';

$lang->pivot->confirm = new stdclass();
$lang->pivot->confirm->design  = 'This pivot table is referenced by a published screen. Do you want to continue?';
$lang->pivot->confirm->publish = 'This pivot table is referenced by a published screen and will be displayed as a modified pivot table after publication. Do you want to continue?';
$lang->pivot->confirm->draft   = 'This pivot table is referenced by a published screen and will be displayed as prompts on the large screen after being saved as a draft. Do you want to continue?';
$lang->pivot->confirm->delete  = 'This pivot table is referenced by a published screen and will be displayed as prompts on the large screen after deletion. Do you want to continue?';

$lang->pivot->orderList = array();
$lang->pivot->orderList['asc']  = 'ASC';
$lang->pivot->orderList['desc'] = 'DESC';

$lang->pivot->typeList = array();
$lang->pivot->typeList['pie']        = 'Pie';
$lang->pivot->typeList['line']       = 'Line';
$lang->pivot->typeList['radar']      = 'Radar';
$lang->pivot->typeList['cluBarY']    = 'clustered Bar Y';
$lang->pivot->typeList['stackedBarY'] = 'stacked Bar Y';
$lang->pivot->typeList['cluBarX']    = 'clustered Bar X';
$lang->pivot->typeList['stackedBar'] = 'stacked Bar';

$lang->pivot->aggList = array();
$lang->pivot->aggList['count'] = 'COUNT';
$lang->pivot->aggList['avg']   = 'AVG';
$lang->pivot->aggList['sum']   = 'SUM';

$lang->pivot->operatorList = array();
$lang->pivot->operatorList['=']       = '=';
$lang->pivot->operatorList['!=']      = '!=';
$lang->pivot->operatorList['<']       = '<';
$lang->pivot->operatorList['>']       = '>';
$lang->pivot->operatorList['<=']      = '<=';
$lang->pivot->operatorList['>=']      = '>=';
$lang->pivot->operatorList['in']      = 'IN';
$lang->pivot->operatorList['notin']   = 'NOT IN';
$lang->pivot->operatorList['notnull'] = 'IS NOT NULL';
$lang->pivot->operatorList['null']    = 'IS NULL';

$lang->pivot->dateList = array();
$lang->pivot->dateList['day']   = 'DAY';
$lang->pivot->dateList['week']  = 'WEEK';
$lang->pivot->dateList['month'] = 'MONTH';
$lang->pivot->dateList['year']  = 'YEAR';

$lang->pivot->designStepNav = array();
$lang->pivot->designStepNav['query']   = 'Query Data';
$lang->pivot->designStepNav['design']  = 'Design table';
$lang->pivot->designStepNav['drill']   = 'Data Drilling';
$lang->pivot->designStepNav['filter']  = 'Set Filter';
$lang->pivot->designStepNav['publish'] = 'Release';

$lang->pivot->nextButton = array();
$lang->pivot->nextButton['query']   = 'To Design';
$lang->pivot->nextButton['design']  = 'To Drilling';
$lang->pivot->nextButton['drill']   = 'To Configurate';
$lang->pivot->nextButton['filter']  = 'To Release';
$lang->pivot->nextButton['publish'] = 'Release';

$lang->pivot->displayList = array();
$lang->pivot->displayList['value']   = 'Display value';
$lang->pivot->displayList['percent'] = 'Percentage';

$lang->pivot->typeOptions = array();
$lang->pivot->typeOptions['user']      = 'User';
$lang->pivot->typeOptions['product']   = 'Product';
$lang->pivot->typeOptions['project']   = 'Project';
$lang->pivot->typeOptions['execution'] = 'Execution';
$lang->pivot->typeOptions['dept']      = 'Dept';

$lang->pivot->stepDesign = new stdclass();
$lang->pivot->stepDesign->group       = 'Row Grouping';
$lang->pivot->stepDesign->summary     = 'Summary Setting';
$lang->pivot->stepDesign->column      = 'Column Settings';
$lang->pivot->stepDesign->groupTip    = 'Select Field';
$lang->pivot->stepDesign->groupNum    = array('One', 'Two', 'Three');
$lang->pivot->stepDesign->selectField = 'Select field';
$lang->pivot->stepDesign->selectStat  = 'Selective statistical method';
$lang->pivot->stepDesign->add         = 'Add';
$lang->pivot->stepDesign->delete      = 'Delete';
$lang->pivot->stepDesign->groupField  = 'Grouping field';
$lang->pivot->stepDesign->columnField = 'Field';
$lang->pivot->stepDesign->stat        = 'Calc Mode';

$lang->pivot->stepDesign->moreThanOne = 'Select at least one group field.';
$lang->pivot->stepDesign->summaryTip  = 'After this parameter is selected, you can configure Row Grouping, Column Settings, and Show column totals';
$lang->pivot->stepDesign->groupsTip   = 'By selecting a grouping field, the data of the SQL query is grouped and displayed hierarchically in the pivot table.';
$lang->pivot->stepDesign->columnsTip  = 'Add 1 column to the pivot table and set it.';

$lang->pivot->stepDesign->columnTotal    = 'Show column totals';
$lang->pivot->stepDesign->columnCalc     = 'Summary calculation';
$lang->pivot->stepDesign->columnPosition = 'Summary position';
$lang->pivot->stepDesign->columnTotalTip = 'Add a row to display the summary data for each column.';
$lang->pivot->stepDesign->total          = 'Total';

$lang->pivot->stepDesign->columnPositionList = array();
$lang->pivot->stepDesign->columnPositionList['bottom'] = 'Display on table bottom';
$lang->pivot->stepDesign->columnPositionList['row']    = 'Display on row group';
$lang->pivot->stepDesign->columnPositionList['all']    = 'Display on row group and table bottom';

$lang->pivot->stepDesign->columnTotalList = array();
$lang->pivot->stepDesign->columnTotalList['noShow'] = 'No Show';
$lang->pivot->stepDesign->columnTotalList['sum']    = 'Sum';

$lang->pivot->stepDesign->sliceFieldList = array();
$lang->pivot->stepDesign->sliceFieldList['noSlice'] = 'No slicing';

$lang->pivot->stepDesign->showModeList = array();
$lang->pivot->stepDesign->showModeList['default'] = 'Default (numeric value)';
$lang->pivot->stepDesign->showModeList['total']   = 'Percentage of Total';
$lang->pivot->stepDesign->showModeList['row']     = 'Percentage of row totals';
$lang->pivot->stepDesign->showModeList['column']  = 'Column Total Percentage';

$lang->pivot->stepDesign->showTotalList = array();
$lang->pivot->stepDesign->showTotalList['noShow'] = 'Do not display';
$lang->pivot->stepDesign->showTotalList['sum']    = 'Sum';

$lang->pivot->stepDesign->statList = array();
$lang->pivot->stepDesign->statList['']         = '';
$lang->pivot->stepDesign->statList['count']    = 'Count';
$lang->pivot->stepDesign->statList['distinct'] = 'Count After Distinct';
$lang->pivot->stepDesign->statList['avg']      = 'Average';
$lang->pivot->stepDesign->statList['sum']      = 'Sum';
$lang->pivot->stepDesign->statList['max']      = 'Max';
$lang->pivot->stepDesign->statList['min']      = 'Min';

$lang->pivot->stepDrill = new stdclass();
$lang->pivot->stepDrill->drill       = 'Data Drill';
$lang->pivot->stepDrill->addDrill    = 'Data Drill';
$lang->pivot->stepDrill->drillView   = 'Drill View';
$lang->pivot->stepDrill->fieldEmpty  = 'Field can not be empty';
$lang->pivot->stepDrill->objectEmpty = 'Object can not be empty';
$lang->pivot->stepDrill->drillEmpty  = 'Drill Field can not be empty';
$lang->pivot->stepDrill->queryEmpty  = 'Query Field can not be empty';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = 'Monday';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = 'Sunday';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = 'Begin Month';
$lang->datepicker->dpText->TEXT_MONTH_END   = 'End Month';

$lang->pivot->drill = new stdclass();
$lang->pivot->drill->common           = 'Data Drill';
$lang->pivot->drill->drillCondition   = 'Drill Condition';
$lang->pivot->drill->drillResult      = 'Drill Result';
$lang->pivot->drill->selectField      = 'Select Drill Field';
$lang->pivot->drill->selectObject     = 'Select Link Object';
$lang->pivot->drill->setCondition     = 'Set Drill Condition';
$lang->pivot->drill->equal            = '=';
$lang->pivot->drill->inDrillField     = 'Drill table field';
$lang->pivot->drill->inQueryField     = 'Query field';
$lang->pivot->drill->preview          = 'Preview';
$lang->pivot->drill->save             = 'Save';
$lang->pivot->drill->drillFieldText   = "%s(%s).%s";
$lang->pivot->drill->storyName        = 'Story Name';
$lang->pivot->drill->releaseStories   = "Finished Stories";
$lang->pivot->drill->productName      = "Product Name";
$lang->pivot->drill->activatedBug     = "Activated Bug Count";
$lang->pivot->drill->auto             = "Auto";
$lang->pivot->drill->designChangedTip = 'Setting columns field change, please check';

$lang->pivot->versionNumber   = 'Version';
$lang->pivot->versionDesc     = 'Desc';
$lang->pivot->tipNewVersion   = 'Has New Version!';
$lang->pivot->checkNewVersion = 'Click to check';
$lang->pivot->tipVersions     = 'This is the pivot of this version. You can click the button to switch version. the pivot and designer config will be changed after switch version.';
$lang->pivot->tipNoVersions   = 'There are no other versions of this pivot';
$lang->pivot->switchTo        = 'Switch To This Version';
$lang->pivot->noDesc          = 'No Description';
