#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

zdTable('userquery')->gen(10);

/**

title=测试 searchModel->setSearchParams();
timeout=0
cid=1

- queryID 为 0 时，返回 1 = 1 @1 = 1
- 查询ID为1的搜索条件名称及查询数量 @(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'

*/

$search = new searchTest();

$queryIDList = array('0', '1', '2', '3', '4', '5', '6');
$module = 'task';

r($search->setQueryTest($module, $queryIDList[0])) && p('') && e('1 = 1'); // queryID 为 0 时，返回 1 = 1
r($search->setQueryTest($module, $queryIDList[1])) && p('') && e("(( 1   AND `name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); //查询ID为1的搜索条件名称及查询数量