#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen('45');
zenData('branch')->gen('10');
zenData('project')->gen('30');
zenData('story')->gen('10');
zenData('module')->gen('10');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildSearchForm();
cid=18966

- 测试构建产品 0 project 0 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene

- 测试构建产品 1 project 0 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene

- 测试构建产品 1 project 1 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene

- 测试构建产品 1 project 1 module 1 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene

- 测试构建产品 41 project 0 module 0 branch 0 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch

- 测试构建产品 41 project 0 module 0 branch 0 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch

- 测试构建产品 0 project 0 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene
- 测试构建产品 1 project 0 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene
- 测试构建产品 1 project 1 module 0 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene
- 测试构建产品 1 project 1 module 1 branch all 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene
- 测试构建产品 41 project 0 module 0 branch 0 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch
- 测试构建产品 41 project 0 module 0 branch 0 的搜索表单 @title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch

*/

$productID = array(0, 1, 41);
$projectID = array(0, 11);
$moduleID  = array(0, 1);
$branch    = array('all', 0);

$testcase = new testcaseModelTest();

r($testcase->buildSearchFormTest($productID[0], $projectID[0], $moduleID[0], $branch[0])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene');        // 测试构建产品 0 project 0 module 0 branch all 的搜索表单
r($testcase->buildSearchFormTest($productID[1], $projectID[0], $moduleID[0], $branch[0])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene');        // 测试构建产品 1 project 0 module 0 branch all 的搜索表单
r($testcase->buildSearchFormTest($productID[1], $projectID[1], $moduleID[0], $branch[0])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene');        // 测试构建产品 1 project 1 module 0 branch all 的搜索表单
r($testcase->buildSearchFormTest($productID[1], $projectID[1], $moduleID[1], $branch[0])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene');        // 测试构建产品 1 project 1 module 1 branch all 的搜索表单
r($testcase->buildSearchFormTest($productID[2], $projectID[0], $moduleID[0], $branch[0])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch'); // 测试构建产品 41 project 0 module 0 branch 0 的搜索表单
r($testcase->buildSearchFormTest($productID[2], $projectID[0], $moduleID[0], $branch[1])) && p() && e('title,story,id,keywords,lastEditedBy,type,auto,openedBy,status,product,stage,module,pri,lib,lastRunner,lastRunResult,lastRunDate,openedDate,lastEditedDate,scene,branch'); // 测试构建产品 41 project 0 module 0 branch 0 的搜索表单
