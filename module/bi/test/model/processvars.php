#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=biModel->processVars();
timeout=0
cid=15210

- 测试第1条sql filter 0 emptyValue true @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第1条sql filter 0 emptyValue false @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第1条sql filter 1 emptyValue true @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第1条sql filter 1 emptyValue false @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第1条sql filter 2 emptyValue true @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第1条sql filter 2 emptyValue false @SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 0 emptyValue true @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 0 emptyValue false @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 1 emptyValue true @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 1 emptyValue false @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 2 emptyValue true @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第2条sql filter 2 emptyValue false @SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)

- 测试第3条sql filter 0 emptyValue true @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试第3条sql filter 0 emptyValue false @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试第3条sql filter 1 emptyValue true @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试第3条sql filter 1 emptyValue false @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试第3条sql filter 2 emptyValue true @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试第3条sql filter 2 emptyValue false @SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id

- 测试注入载荷被转义为字面量 @1
- 测试字面量内的同名变量不被替换 @1
- 测试过滤值非标量时拒绝请求 @1

*/

$app->loadLang('bi');

$bi = new biModelTest();

$sqls = array();

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id
EOT;

$filters = $filter = array();
$filter[] = array('field' => '', 'default' => '', 'type' => 'date');
$filter[] = array('field' => '', 'default' => '', 'type' => 'datetime');
$filter[] = array('field' => '', 'default' => '', 'type' => 'multipleselect');
$filter[] = array('field' => '', 'default' => array(1,2), 'type' => 'multipleselect');
$filters[] = $filter;

$filter[] = array('field' => '', 'from' => '', 'default' => '', 'type' => 'date');
$filter[] = array('field' => '', 'from' => '', 'default' => '', 'type' => 'datetime');
$filter[] = array('field' => '', 'from' => '', 'default' => '', 'type' => 'multipleselect');
$filter[] = array('field' => '', 'from' => '', 'default' => array(1,2), 'type' => 'multipleselect');
$filters[] = $filter;

$filter = array();
$filter[] = array('field' => '', 'from' => 'query', 'default' => '', 'type' => 'date');
$filter[] = array('field' => '', 'from' => 'query', 'default' => '', 'type' => 'datetime');
$filter[] = array('field' => '', 'from' => 'query', 'default' => '', 'type' => 'multipleselect');
$filter[] = array('field' => '', 'from' => 'query', 'default' => array(1,2), 'type' => 'multipleselect');
$filters[] = $filter;

$emptyValue = array(true, false);

r($bi->processVarsTest($sqls[0], $filters[0], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 0 emptyValue true
r($bi->processVarsTest($sqls[0], $filters[0], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 0 emptyValue false
r($bi->processVarsTest($sqls[0], $filters[1], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 1 emptyValue true
r($bi->processVarsTest($sqls[0], $filters[1], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 1 emptyValue false
r($bi->processVarsTest($sqls[0], $filters[2], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 2 emptyValue true
r($bi->processVarsTest($sqls[0], $filters[2], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第1条sql filter 2 emptyValue false
r($bi->processVarsTest($sqls[1], $filters[0], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 0 emptyValue true
r($bi->processVarsTest($sqls[1], $filters[0], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 0 emptyValue false
r($bi->processVarsTest($sqls[1], $filters[1], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 1 emptyValue true
r($bi->processVarsTest($sqls[1], $filters[1], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 1 emptyValue false
r($bi->processVarsTest($sqls[1], $filters[2], $emptyValue[0])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 2 emptyValue true
r($bi->processVarsTest($sqls[1], $filters[2], $emptyValue[1])) && p() && e('SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)'); // 测试第2条sql filter 2 emptyValue false
r($bi->processVarsTest($sqls[2], $filters[0], $emptyValue[0])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 0 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[0], $emptyValue[1])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 0 emptyValue false
r($bi->processVarsTest($sqls[2], $filters[1], $emptyValue[0])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 1 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[1], $emptyValue[1])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 1 emptyValue false
r($bi->processVarsTest($sqls[2], $filters[2], $emptyValue[0])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 2 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[2], $emptyValue[1])) && p() && e("SELECT t1.name, t1.`PM`, t1.begin, t1.`realBegan`, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.`realBegan` > '2022-01-01' order by t1.id"); // 测试第3条sql filter 2 emptyValue false

/* 注入载荷在 processVars 中被转义为字面量，无法闭合引号。The injection payload is escaped as a literal in processVars. */
$injectionSQL     = "SELECT * FROM zt_user WHERE status = \$status";
$injectionFilters = array(array('field' => 'status', 'from' => 'query', 'type' => 'select', 'default' => "active' or sleep(3)-- "));
r($bi->processVarsTest($injectionSQL, $injectionFilters, false) === "SELECT * FROM zt_user WHERE status = 'active\\' or sleep(3)-- '") && p() && e(1); // 测试注入载荷被转义为字面量

/* 字符串字面量内的同名变量不再被替换，避免替换结果被再次扫描。The same variable name inside a string literal is kept unchanged. */
$literalSQL = "SELECT * FROM zt_user WHERE status = 'x\$statusx'";
r($bi->processVarsTest($literalSQL, $injectionFilters, false) === "SELECT * FROM zt_user WHERE status = 'x\$statusx'") && p() && e(1); // 测试字面量内的同名变量不被替换

/* 非标量过滤值直接拒绝请求。The request is denied when the filter value is not scalar. */
$nonScalarFilters = array(array('field' => 'status', 'from' => 'query', 'type' => 'select', 'default' => new stdClass()));
r($bi->processVarsTest($injectionSQL, $nonScalarFilters, false) === $lang->bi->errorIllegalFilter['value']) && p() && e(1); // 测试过滤值非标量时拒绝请求
