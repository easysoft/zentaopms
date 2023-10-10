#!/usr/bin/env php
<?php
/**

title=测试 releaseModel->getBugForExport();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/release.class.php';

zdTable('bug')->config('bug')->gen(10);
zdTable('user')->gen(5);
su('admin');

$conditions[0] = 'id in (1, 2, 3, 4, 5)';
$conditions[1] = 'product=1';
$conditions[2] = 'title like "%1%" and product=1';
$types         = array('bug', 'leftBug');
$orderBy       = 'id DESC';

$releaseTester = new releaseTest();
r($releaseTester->getBugForExportTest($types[0], $conditions[0], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取Bug列表信息
r($releaseTester->getBugForExportTest($types[0], $conditions[1], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取Bug列表信息
r($releaseTester->getBugForExportTest($types[0], $conditions[2], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取Bug列表信息
r($releaseTester->getBugForExportTest($types[1], $conditions[0], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取遗留的Bug列表信息
r($releaseTester->getBugForExportTest($types[1], $conditions[1], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取遗留的Bug列表信息
r($releaseTester->getBugForExportTest($types[1], $conditions[2], $orderBy)) && p('1:title') && e('Bug1'); // 根据条件获取遗留的Bug列表信息
