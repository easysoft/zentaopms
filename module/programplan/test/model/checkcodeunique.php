#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

function initData()
{
    zdTable('project')->config('project')->gen(5);
}

/**

title=测试 programplanModel->checkCodeUnique();
cid=1
pid=1

*/

initData();

$codes = array();
$codes[0] = array('code1', 'code2', 'code3', 'code4', 'code5');
$codes[1] = array('code6', 'code7');
$codes[2] = array('', 'code6', 'code7');

$excludeList = array('1', '2', '3');

$programplan = new programplanTest();
r($programplan->checkCodeUniqueTest($codes[0]))               && p('code1') && e('code1'); // 检查 'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一性
r($programplan->checkCodeUniqueTest($codes[0], $excludeList)) && p('code4') && e('code4'); // 检查 'code1', 'code2', 'code3', 'code4', 'code5' 在排除了id为'1', '2', '3'外的未删除数据编号是否唯一性
r($programplan->checkCodeUniqueTest($codes[1]))               && p()        && e('1');     // 检查 'code6', 'code7' 在未删除的数据内编号是否唯一性
r($programplan->checkCodeUniqueTest($codes[2]))               && p()        && e('1');     // 检查 '', 'code6', 'code7' 在未删除的数据内编号是否唯一性
