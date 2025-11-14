#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(5);

/**

title=测试 projectModel->buildBatchUpdateProjects();
timeout=0
cid=17886

- 测试空数据 @0
- 测试构造项目ID为1、2、3的更新数据
 - 第1条的name属性 @更新敏捷项目1
 - 第1条的PM属性 @admin
- 测试构造项目ID为1、2、3的更新数据
 - 第2条的name属性 @更新瀑布项目2
 - 第2条的PM属性 @admin
- 测试构造项目ID为1、2、3的更新数据
 - 第3条的name属性 @更新看板项目3
 - 第3条的PM属性 @admin
- 测试构造项目ID为1、2、3的更新数据
 - 第4条的name属性 @更新敏捷项目4
 - 第4条的PM属性 @admin

*/

$projectTester = new projectTest();

r($projectTester->buildBatchUpdateProjectsTest(array()))        && p()            && e('0');                   // 测试空数据
r($projectTester->buildBatchUpdateProjectsTest(array(1)))       && p('1:name,PM') && e('更新敏捷项目1,admin'); // 测试构造项目ID为1、2、3的更新数据
r($projectTester->buildBatchUpdateProjectsTest(array(1, 2)))    && p('2:name,PM') && e('更新瀑布项目2,admin'); // 测试构造项目ID为1、2、3的更新数据
r($projectTester->buildBatchUpdateProjectsTest(array(1, 2, 3))) && p('3:name,PM') && e('更新看板项目3,admin'); // 测试构造项目ID为1、2、3的更新数据
r($projectTester->buildBatchUpdateProjectsTest(array(4)))       && p('4:name,PM') && e('更新敏捷项目4,admin'); // 测试构造项目ID为1、2、3的更新数据
