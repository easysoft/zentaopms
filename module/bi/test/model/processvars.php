#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

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

- 测试第3条sql filter 0 emptyValue true @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

- 测试第3条sql filter 0 emptyValue false @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

- 测试第3条sql filter 1 emptyValue true @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

- 测试第3条sql filter 1 emptyValue false @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

- 测试第3条sql filter 2 emptyValue true @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

- 测试第3条sql filter 2 emptyValue false @SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id

*/

$bi = new biTest();

$sqls = array();

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id
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
r($bi->processVarsTest($sqls[2], $filters[0], $emptyValue[0])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 0 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[0], $emptyValue[1])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 0 emptyValue false
r($bi->processVarsTest($sqls[2], $filters[1], $emptyValue[0])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 1 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[1], $emptyValue[1])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 1 emptyValue false
r($bi->processVarsTest($sqls[2], $filters[2], $emptyValue[0])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 2 emptyValue true
r($bi->processVarsTest($sqls[2], $filters[2], $emptyValue[1])) && p() && e("SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id"); // 测试第3条sql filter 2 emptyValue false
