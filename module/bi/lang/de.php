<?php
$lang->bi->binNotExists        = 'The DuckDB binary does not exist.';
$lang->bi->tmpPermissionDenied = 'The DuckDB tmp directory has no permissions, you need to change the permissions for the directory "%s". The <br /> command is: <br />chmod 777 -R %s.';

$lang->bi->acl = 'Access Control';

$lang->bi->driver = 'Driver';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->query      = 'Query';
$lang->bi->sqlQuery   = 'SQL statements query';
$lang->bi->sqlBuilder = 'SQL builder';
$lang->bi->dictionary = 'Data Dictionary';

$lang->bi->toggleSqlText    = 'Write SQL statements by hand';
$lang->bi->toggleSqlBuilder = 'SQL builder';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = 'Select tables';
$lang->bi->builderStepList['field'] = 'Select fields';
$lang->bi->builderStepList['func']  = 'add function field';
$lang->bi->builderStepList['where'] = 'Add where';
$lang->bi->builderStepList['query'] = 'Add query filter';
$lang->bi->builderStepList['group'] = 'set group by';

$lang->bi->stepTableTitle = 'Select the data table to query';
$lang->bi->stepTableTip   = 'Select the data table to query, which specifies which table or tables you want to retrieve data from.';
$lang->bi->changeModeTip  = "This switch will clear the current builder configuration and echo the built SQL statement into the handwritten SQL statement. You can't switch back to SQL Builder mode. Do you want to continue?";
$lang->bi->modeDisableTip = 'Hand-written SQL statements are more flexible, and there is no support for switching back to SQL builder mode';

$lang->bi->fromTable     = 'Main table';
$lang->bi->leftTable     = 'Left join';
$lang->bi->joinCondition = 'Condition';
$lang->bi->joinTable     = '%s';
$lang->bi->of            = 'Of';
$lang->bi->do            = 'Do';
$lang->bi->set           = 'Set';
$lang->bi->funcAs        = 'calculate，rename result as';
$lang->bi->enable        = 'Enable';
$lang->bi->previewSql    = 'Preview SQL statement';
$lang->bi->addFunc       = 'Add function';
$lang->bi->emptyFuncs    = 'Empty function。';
$lang->bi->addWhere      = 'Add group';
$lang->bi->emptyWheres   = 'Empty where。';
$lang->bi->checkAll      = 'Check All';
$lang->bi->cancelAll     = 'Cancel All';
$lang->bi->groupField    = 'Group field';
$lang->bi->aggField      = 'Aggregate field';
$lang->bi->allFields     = 'Selected/Function field';
$lang->bi->addQuery      = 'Add a dynamic query filter';
$lang->bi->emptyQuerys   = 'Empty dynamic query filter.';
$lang->bi->emptySelect   = 'Select at least one field.';
$lang->bi->length        = 'Length';

$lang->bi->allFieldsTip  = 'Selected and function field is already checked.';
$lang->bi->groupFieldTip = 'Group by the result with group field.';
$lang->bi->aggFieldTip   = 'The aggregation function operation is configured for the aggregation field, so as to obtain the summary data under different groups.';

$lang->bi->aggTipA = 'For %s';
$lang->bi->aggTipB = 'calculate, Rename to %s';

$lang->bi->fieldTypeList = array();
$lang->bi->fieldTypeList['string'] = 'String';
$lang->bi->fieldTypeList['number'] = 'Number';
$lang->bi->fieldTypeList['date']   = 'Date';
$lang->bi->fieldTypeList['option'] = 'Option';
$lang->bi->fieldTypeList['object'] = 'Object';

$lang->bi->aggList = array();
$lang->bi->aggList['count']         = 'Count';
$lang->bi->aggList['countdistinct'] = 'Count Distinct';
$lang->bi->aggList['avg']           = 'Average';
$lang->bi->aggList['sum']           = 'Sum';
$lang->bi->aggList['max']           = 'Max';
$lang->bi->aggList['min']           = 'Min';

$lang->bi->whereGroupTitle  = 'The %s group';
$lang->bi->addWhereGroup    = 'Add group';
$lang->bi->removeWhereGroup = 'Delete group';

$lang->bi->selectTableTip = 'Select table';
$lang->bi->selectFieldTip = 'Select field';
$lang->bi->selectFuncTip  = 'Select function';
$lang->bi->selectInputTip = 'Input something';

$lang->bi->funcList = array();
$lang->bi->funcList['date']  = 'Date';
$lang->bi->funcList['month'] = 'Month';
$lang->bi->funcList['year']  = 'Year';

$lang->bi->whereOperatorList = array();
$lang->bi->whereOperatorList['and'] = 'AND';
$lang->bi->whereOperatorList['or']  = 'OR';

$lang->bi->whereItemOperatorList = array();
$lang->bi->whereItemOperatorList['=']     = '=';
$lang->bi->whereItemOperatorList['!=']    = '!=';
$lang->bi->whereItemOperatorList['>']     = '>';
$lang->bi->whereItemOperatorList['>=']    = '>=';
$lang->bi->whereItemOperatorList['<']     = '<';
$lang->bi->whereItemOperatorList['<=']    = '<=';
$lang->bi->whereItemOperatorList['in']    = 'IN';
$lang->bi->whereItemOperatorList['notIn'] = 'NOT IN';
$lang->bi->whereItemOperatorList['like']  = 'LIKE';

$lang->bi->queryFilterFormHeader = array();
$lang->bi->queryFilterFormHeader['table']   = 'Select table';
$lang->bi->queryFilterFormHeader['field']   = 'Select field';
$lang->bi->queryFilterFormHeader['name']    = 'Filter name';
$lang->bi->queryFilterFormHeader['type']    = 'Filter type';
$lang->bi->queryFilterFormHeader['default'] = 'Default value';

$lang->bi->emptyError     = 'Can not be empty';
$lang->bi->duplicateError = 'Duplicate';
$lang->bi->noSql          = 'No SQL.';

$lang->bi->stepFieldTitle = 'Select a field in the lookup table';
$lang->bi->stepFieldTip   = 'The fields in the select query table are used to get the required data from the selected query table.';
$lang->bi->leftTableTip   = 'In SQL, a Left join is a table-to-table join that returns all the rows in the left table and the matching rows in the right table. The left join combines data from two tables based on the specified criteria, where the left table is the main table of the query and the right table is the table to be joined. See the specific table query common way: left join.';

$lang->bi->stepFuncTitle = 'New function fields';
$lang->bi->stepFuncTip   = 'You can set functions on the fields in the query table to add a new column of the data you want to the query result.';

$lang->bi->stepWhereTitle = 'Add deterministic query conditions';
$lang->bi->stepWhereTip   = '1.Query criteria are used to filter the data that does not meet the requirements. You can add query criteria as needed to get the corresponding query results. 2.Use =,! For =, >, >=, <, <=, and fuzzy matching (like) condition symbols, enter the corresponding condition value in the input box to the right of the symbol. 3.When using the include (in) condition symbol, please enter one or more condition values in the input box to the right of the symbol, separated by English commas, for example: task type include (in) development, test.';

$lang->bi->stepQueryTitle = 'Add a dynamic query filter';
$lang->bi->stepQueryTip   = 'Adding a dynamic query filter. The dynamic query filter is a filtering method that implements dynamic queries by inserting variables in the SQL. The result filter configured in the third step is to further filter the SQL query results.';

$lang->bi->stepGroupTitle = 'Set up groups and aggregate';
$lang->bi->stepGroupTip   = 'Setting grouping is used to group query results according to specified column values, and apply aggregate functions to other grouped data to obtain summary information.';
$lang->bi->emptyGroups    = 'After enabling "Set group and aggregation", the system will automatically display your selected query field and the newly added function field here; <br />You can set the grouping field in turn, as well as any other fields that need to be aggregated.';
