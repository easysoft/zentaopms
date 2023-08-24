#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);

/**

title=测试 projectModel->buildBatchUpdateProjects();
timeout=0
cid=1

*/

$projectTester = new project();

r($projectTester->buildBatchUpdateProjectsTest(array()))        && p()            && e('0');                   // 测试空数据
r($projectTester->buildBatchUpdateProjectsTest(array(1, 2, 3))) && p('1:name,PM') && e('更新敏捷项目1,admin'); // 测试构造项目ID为1、2、3的更新数据
