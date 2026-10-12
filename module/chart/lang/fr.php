<?php
$lang->chart->preview   = 'View Chart';
$lang->chart->query     = 'Query';
$lang->chart->toDesign  = 'To Design';

$lang->chart->accessDenied  = 'You do not have access to this chart';

$lang->chart->errorIllegalSql = array();
$lang->chart->errorIllegalSql['field']    = 'The chart field is invalid, the request has been denied.';
$lang->chart->errorIllegalSql['operator'] = 'The chart filter operator is invalid, the request has been denied.';
$lang->chart->errorIllegalSql['value']    = 'The chart filter value is invalid, only a number or a quoted string is allowed.';
$lang->chart->errorIllegalSql['agg']      = 'The chart aggregate function is invalid, the request has been denied.';
$lang->chart->errorIllegalSql['date']     = 'The chart date group is invalid, the request has been denied.';
$lang->chart->sqlSensitiveField           = 'Querying sensitive fields such as the password is not allowed, the request has been denied.';
$lang->chart->sqlWildcardUser             = 'Using select * to query the user table is not allowed, the request has been denied.';
$lang->chart->acl = 'Access Control';
$lang->chart->aclList['open']    = 'Public (with chart view permissions and dimension permissions can access it)';
$lang->chart->aclList['private'] = 'Private (Only creators and whitelisted users with dimension permissions can access it)';

$lang->chart->group              = 'Group';
$lang->chart->field              = 'Related Field';
$lang->chart->agg                = 'Aggregate';
$lang->chart->chooseField        = 'Choose field';
$lang->chart->aggType            = 'Aggregate type';
$lang->chart->other              = 'Other';
$lang->chart->unlimited          = 'Unlimited';
$lang->chart->colon              = 'To';
$lang->chart->updateCharts       = 'Update the chart';
$lang->chart->updateCurrentChart = 'Update data in charts';

$lang->chart->fieldTypeList = array();
$lang->chart->fieldTypeList['input']    = 'Text';
$lang->chart->fieldTypeList['date']     = 'Date';
$lang->chart->fieldTypeList['datetime'] = 'Time';
$lang->chart->fieldTypeList['select']   = 'Dropdown';
//$lang->chart->fieldTypeList['condition'] = 'Condition';

$lang->chart->groupWeek = 'Week %s,%s';

$lang->chart->noChart         = 'No Chart';
$lang->chart->noData          = 'No Data';
$lang->chart->noChartSelected = 'Please select one chart.';
$lang->chart->chartMaxChecked = 'You can only select up to %s charts.';

$lang->chart->featureBar = array();
$lang->chart->featureBar['preview'] = array();

$lang->chart->aggList = array();
$lang->chart->aggList['count']    = 'Count';
$lang->chart->aggList['distinct'] = 'Count after distinct';
$lang->chart->aggList['avg']      = 'AVG';
$lang->chart->aggList['sum']      = 'SUM';
$lang->chart->aggList['max']      = 'MAX';
$lang->chart->aggList['min']      = 'MIN';

$lang->chart->typeList = array();
$lang->chart->typeList['pie']         = 'Pie';
$lang->chart->typeList['line']        = 'Line';
$lang->chart->typeList['waterpolo']   = 'Liquid Fill';
$lang->chart->typeList['radar']       = 'Radar';
$lang->chart->typeList['cluBarY']     = 'Clustered Bar Y';
$lang->chart->typeList['stackedBarY'] = 'Stacked Bar Y';
$lang->chart->typeList['cluBarX']     = 'Clustered Bar X';
$lang->chart->typeList['stackedBar']  = 'Stacked Bar X';

$lang->chart->conditionList['eq'] = '=';
