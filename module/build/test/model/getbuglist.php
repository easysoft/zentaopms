#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getBugList();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('bug')->config('bug')->gen(10);
zdTable('user')->gen(5);
su('admin');

$bugIdList = array('', '1,2,3,4,5', '11,12,13,14,15');

global $tester;
$tester->loadModel('build');
r($tester->build->getBugList($bugIdList[0])) && p()          && e('0');    // 测试传入空数组获取bug列表数据
r($tester->build->getBugList($bugIdList[1])) && p('4:title') && e('Bug5'); // 测试传入bugId列表获取bug列表数据
r($tester->build->getBugList($bugIdList[2])) && p()          && e('0');    // 测试传入不存在bugId列表获取bug列表数据
