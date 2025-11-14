#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectrelease.unittest.class.php';

zenData('release')->gen(20);
zenData('product')->gen(20);
zenData('user')->gen(1);

su('admin');

/**

title=测试 projectreleaseModel->getList();
cid=17970

- 查询projectID正常存在, type为 all 排序 id_asc 的发布 @9,19

- 查询projectID正常存在, type为 all 排序 id_desc 的发布 @19,9

- 查询projectID正常存在, type为 normal 排序 id_asc 的发布 @9,19

- 查询projectID正常存在, type为 normal 排序 id_desc 的发布 @19,9

- 查询projectID正常存在,type为terminate的发布 @0
- 查询projectID正常存在,type为''的发布 @0
- 查询projectID正常存在的发布 @9,19

- 查询projectID不存在,type为all的发布 @0
- 查询projectID为空,type为all的发布 @8,18

*/

$projectID = array(11, 1000, 0);
$type      = array('all', 'normal', 'terminate', '');
$orderBy   = array('t1.id_asc', 't1.id_desc');

$projectrelease = new projectreleaseTest();

r($projectrelease->getListTest($projectID[0], $type[0], $orderBy[0])) && p() && e('9,19'); // 查询projectID正常存在, type为 all 排序 id_asc 的发布
r($projectrelease->getListTest($projectID[0], $type[0], $orderBy[1])) && p() && e('19,9'); // 查询projectID正常存在, type为 all 排序 id_desc 的发布
r($projectrelease->getListTest($projectID[0], $type[1], $orderBy[0])) && p() && e('9,19'); // 查询projectID正常存在, type为 normal 排序 id_asc 的发布
r($projectrelease->getListTest($projectID[0], $type[1], $orderBy[1])) && p() && e('19,9'); // 查询projectID正常存在, type为 normal 排序 id_desc 的发布

r($projectrelease->getListTest($projectID[0], $type[2])) && p() && e('0');    // 查询projectID正常存在,type为terminate的发布
r($projectrelease->getListTest($projectID[0], $type[3])) && p() && e('0');    // 查询projectID正常存在,type为''的发布
r($projectrelease->getListTest($projectID[0]))           && p() && e('9,19'); // 查询projectID正常存在的发布
r($projectrelease->getListTest($projectID[1], $type[0])) && p() && e('0');    // 查询projectID不存在,type为all的发布
r($projectrelease->getListTest($projectID[2], $type[0])) && p() && e('8,18'); // 查询projectID为空,type为all的发布
