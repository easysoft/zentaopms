<?php
/**
 * The pivot module French file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: fr.php 5080 2013-07-10 00:46:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->pivot->index        = 'Home';
$lang->pivot->list         = 'Liste';
$lang->pivot->preview      = 'View pivot';
$lang->pivot->create       = 'Create pivot';
$lang->pivot->edit         = 'Edit pivot';
$lang->pivot->browse       = 'Browse pivot';
$lang->pivot->delete       = 'Delete pivot';
$lang->pivot->design       = 'Design pivot';
$lang->pivot->export       = 'Export pivot';
$lang->pivot->query        = 'Query';
$lang->pivot->browseAction = 'Design in Pivot Table';

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
$lang->pivot->varCode     = 'Var Code';
$lang->pivot->varLabel    = 'Var Label';
$lang->pivot->monopolize  = 'Self';
$lang->pivot->varNameTip  = 'Input letters';
$lang->pivot->item        = 'Eintrag';
$lang->pivot->percent     = '%';
$lang->pivot->undefined   = 'Undefiniert';
$lang->pivot->project     = $lang->projectCommon;
$lang->pivot->PO          = 'PO';

$lang->pivot->showOriginItem = 'Show origin item';

$lang->pivot->showOriginPlaceholder = new stdclass();
$lang->pivot->showOriginPlaceholder->slice    = 'No need to configure';
$lang->pivot->showOriginPlaceholder->calcMode = 'No need to configure';
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

$lang->pivot->assign['noassign'] = 'Nicht zugeordnet';
$lang->pivot->assign['assign']   = 'Zugeordnet';

$lang->pivot->singleColor[] = 'F6BD0F';

$lang->pivot->projectDeviation = "{$lang->execution->common}abweichungsbericht";
$lang->pivot->productSummary   = $lang->productCommon . ' Zusammenfassung';
$lang->pivot->bugCreate        = 'Bug gemeldet';
$lang->pivot->bugAssign        = 'Bug zugeordnet';
$lang->pivot->workload         = 'Team Arbeitslast ';
$lang->pivot->workloadAB       = 'Arbeitslast';
$lang->pivot->bugOpenedDate    = 'Bug gemeldet am';
$lang->pivot->beginAndEnd      = ' : von';
$lang->pivot->begin            = 'Begin';
$lang->pivot->end              = 'End';
$lang->pivot->dept             = 'Abteilung';
$lang->pivot->deviationChart   = "{$lang->projectCommon} Deviation Chart";

$lang->pivotList = new stdclass();
$lang->pivotList->product = new stdclass();
$lang->pivotList->project = new stdclass();
$lang->pivotList->test    = new stdclass();
$lang->pivotList->staff   = new stdclass();

$lang->pivotList->product->lists[10] = $lang->productCommon . ' Zusammenfassung|pivot|productsummary';
$lang->pivotList->project->lists[10] = "{$lang->execution->common} Abweichnung|pivot|projectdeviation";
$lang->pivotList->test->lists[10]    = 'Bugs gemeldet|pivot|bugcreate';
$lang->pivotList->test->lists[13]    = 'Bugs zugeordnet|pivot|bugassign';
$lang->pivotList->staff->lists[10]   = 'Team Arbeitslast|pivot|workload';

$lang->pivot->id            = 'ID';
$lang->pivot->execution     = $lang->executionCommon;
$lang->pivot->product       = $lang->productCommon;
$lang->pivot->user          = 'Name';
$lang->pivot->bug           = 'Bug';
$lang->pivot->task          = 'Aufgabe';
$lang->pivot->estimate      = 'Schätzung(h)';
$lang->pivot->consumed      = 'Verbraucht';
$lang->pivot->remain        = 'Rest';
$lang->pivot->deviation     = 'Abweichung';
$lang->pivot->deviationRate = 'Abweichungsrate';
$lang->pivot->total         = 'Summe';
$lang->pivot->to            = 'bis';
$lang->pivot->taskTotal     = "Summe Aufgaben";
$lang->pivot->manhourTotal  = "Summe Stunden";
$lang->pivot->validRate     = "Gültigkeitsrate";
$lang->pivot->validRateTips = "Lösung ist gelöst/verschoben oder der Status ist gelöst/geschlossen.";
$lang->pivot->unplanned     = 'Ungeplant';
$lang->pivot->workhour      = 'Stunden/Tag';
$lang->pivot->diffDays      = 'Manntage';

$lang->pivot->typeList['default'] = 'Standard';
$lang->pivot->typeList['pie']     = 'Torte';
$lang->pivot->typeList['bar']     = 'Balken';
$lang->pivot->typeList['line']    = 'Linien';

$lang->pivot->conditions    = 'Filter:';
$lang->pivot->closedProduct = 'Geschlossen ' . $lang->productCommon;
$lang->pivot->overduePlan   = 'Fälligkeit Plan';

$lang->pivot->idAB         = 'ID';
$lang->pivot->bugTitle     = 'Bug-Name';
$lang->pivot->taskName     = 'Aufgabe-Name';
$lang->pivot->todoName     = 'Todo-Name';
$lang->pivot->testTaskName = 'Testaufgabe-Name';
$lang->pivot->deadline     = 'Fällig';

$lang->pivot->deviationDesc = 'According to the Closed Execution Deviation Rate = ((Total Cost - Total Estimate) / Total Estimate), the Deviation Rate is n/a when the Total Estimate is 0.';
$lang->pivot->workloadDesc  = 'Workload = the total left hours of all tasks of the user / selected days * hours per day. For example: the begin and end date is January 1st to January 7th, and the total work days is 5 days, 8 hours per day. The Work load is all unfinished tasks assigned to this user to be finished in 5 days, 8 hours per day.';

$lang->pivot->featureBar = array();
$lang->pivot->featureBar['preview'] = array();

$lang->pivot->showProduct = 'All' . $lang->productCommon . 'Statistics';
$lang->pivot->showProject = 'All' . $lang->projectCommon . 'Statistics';

$lang->pivot->columnIndex  = 'col.%s';
$lang->pivot->deleteColumn = 'Are you sure to delete this column?';
$lang->pivot->addColumn    = 'Add column';
$lang->pivot->sliceField   = 'Select slice field';
$lang->pivot->calcMode     = 'Calculate value';
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

$lang->pivot->confirmDelete = 'Do you want to delete this pivot?';
$lang->pivot->nameEmpty     = '『Name』should not be blank';

$lang->pivot->noPivotTip      = 'After you save the Settings, you can display the Pivot table';
$lang->pivot->noQueryTip      = 'No filter.';
$lang->pivot->noPivot         = 'No Pivot';
$lang->pivot->dataError       = '"%s" is not valid';
$lang->pivot->noChartSelected = 'Please select one pivot.';
$lang->pivot->beginGtEnd      = 'Begin time should not be >= end time.';
$lang->pivot->resetSettings   = 'The configuration of the query data has been modified, requiring redesign of the pivot, whether to continue.';
$lang->pivot->clearSettings   = 'The configuration of the query data has been modified, whether to clear the pivot and save.';
$lang->pivot->draftSave       = 'Released content is edited, will be overwritten, whether to continue?';
$lang->pivot->cannotAddQuery  = 'Result filter has been added, query filter cannot be added.';
$lang->pivot->cannotAddResult = 'Query filter has been added, result filter cannot be added.';

$lang->pivot->confirm = new stdclass();
$lang->pivot->confirm->design  = 'This pivot is referenced by a published screen. Do you want to continue?';
$lang->pivot->confirm->publish = 'This pivot is referenced by a published screen and will be displayed as a modified pivot after publication. Do you want to continue?';
$lang->pivot->confirm->draft   = 'This pivot is referenced by a published screen and will be displayed as prompts on the large screen after being saved as a draft. Do you want to continue?';
$lang->pivot->confirm->delete  = 'This pivot is referenced by a published screen and will be displayed as prompts on the large screen after deletion. Do you want to continue?';

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
$lang->pivot->designStepNav['1'] = 'Query Data';
$lang->pivot->designStepNav['2'] = 'Design pivot';
$lang->pivot->designStepNav['3'] = 'Set Filter';
$lang->pivot->designStepNav['4'] = 'Release';

$lang->pivot->nextButton = array();
$lang->pivot->nextButton['1'] = 'To Design';
$lang->pivot->nextButton['2'] = 'To Configurate';
$lang->pivot->nextButton['3'] = 'To Release';
$lang->pivot->nextButton['4'] = 'Release';

$lang->pivot->displayList = array();
$lang->pivot->displayList['value']   = 'Display value';
$lang->pivot->displayList['percent'] = 'Percentage';

$lang->pivot->typeOptions = array();
$lang->pivot->typeOptions['user']      = 'User';
$lang->pivot->typeOptions['product']   = 'Product';
$lang->pivot->typeOptions['project']   = 'Project';
$lang->pivot->typeOptions['execution'] = 'Execution';
$lang->pivot->typeOptions['dept']      = 'Dept';

$lang->pivot->step2 = new stdclass();
$lang->pivot->step2->group       = 'Row Grouping';
$lang->pivot->step2->summary     = 'Summary Setting';
$lang->pivot->step2->column      = 'Column Settings';
$lang->pivot->step2->groupTip    = 'Select Field';
$lang->pivot->step2->groupNum    = array('One', 'Two', 'Three');
$lang->pivot->step2->selectField = 'Select field';
$lang->pivot->step2->selectStat  = 'Selective statistical method';
$lang->pivot->step2->add         = 'Add';
$lang->pivot->step2->delete      = 'Delete';
$lang->pivot->step2->groupField  = 'Grouping field';
$lang->pivot->step2->columnField = 'Field';
$lang->pivot->step2->calcMode    = 'Calc Mode';

$lang->pivot->step2->moreThanOne = 'Select at least one group field.';
$lang->pivot->step2->summaryTip  = 'After this parameter is selected, you can configure Row Grouping, Column Settings, and Show column totals';
$lang->pivot->step2->groupsTip   = 'By selecting a grouping field, the data of the SQL query is grouped and displayed hierarchically in the pivot table.';
$lang->pivot->step2->columnsTip  = 'Add 1 column to the pivot table and set it.';

$lang->pivot->step2->columnTotal    = 'Show column totals';
$lang->pivot->step2->columnTotalTip = 'Add a row to display the summary data for each column.';
$lang->pivot->step2->total          = 'Total';

$lang->pivot->step2->columnTotalList = array();
$lang->pivot->step2->columnTotalList['noShow'] = 'No Show';
$lang->pivot->step2->columnTotalList['sum']    = 'Sum';

$lang->pivot->step2->sliceFieldList = array();
$lang->pivot->step2->sliceFieldList['noSlice'] = 'No slicing';

$lang->pivot->step2->showModeList = array();
$lang->pivot->step2->showModeList['default'] = 'Default (numeric value)';
$lang->pivot->step2->showModeList['total']   = 'Percentage of Total';
$lang->pivot->step2->showModeList['row']     = 'Percentage of row totals';
$lang->pivot->step2->showModeList['column']  = 'Column Total Percentage';

$lang->pivot->step2->showTotalList = array();
$lang->pivot->step2->showTotalList['noShow'] = 'Do not display';
$lang->pivot->step2->showTotalList['sum']    = 'Sum';

$lang->pivot->step2->statList = array();
$lang->pivot->step2->statList['']         = '';
$lang->pivot->step2->statList['count']    = 'Count';
$lang->pivot->step2->statList['distinct'] = 'Count After Distinct';
$lang->pivot->step2->statList['avg']      = 'Average';
$lang->pivot->step2->statList['sum']      = 'Sum';
$lang->pivot->step2->statList['max']      = 'Max';
$lang->pivot->step2->statList['min']      = 'Min';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = 'Monday';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = 'Sunday';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = 'Begin Month';
$lang->datepicker->dpText->TEXT_MONTH_END   = 'End Month';
