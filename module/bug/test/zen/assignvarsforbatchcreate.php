#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignVarsForBatchCreate();
timeout=0
cid=0

- 执行bugTest模块的assignVarsForBatchCreateTest方法，参数是$product1, $project1, $bugImagesFile1
 - 属性customFields @12
 - 属性titles @0
 - 属性hasBranch @0
 - 属性hasKanbanExecution @0
- 执行bugTest模块的assignVarsForBatchCreateTest方法，参数是$product2, $project1, $bugImagesFile1
 - 属性customFields @13
 - 属性titles @0
 - 属性hasBranch @1
 - 属性hasKanbanExecution @0
- 执行bugTest模块的assignVarsForBatchCreateTest方法，参数是$product1, $project2, $bugImagesFile1
 - 属性customFields @12
 - 属性titles @0
 - 属性hasBranch @0
 - 属性hasKanbanExecution @1
- 执行bugTest模块的assignVarsForBatchCreateTest方法，参数是$product1, $project1, $bugImagesFile2
 - 属性customFields @12
 - 属性titles @3
 - 属性hasBranch @0
 - 属性hasKanbanExecution @0
- 执行bugTest模块的assignVarsForBatchCreateTest方法，参数是$product2, $project2, $bugImagesFile2
 - 属性customFields @13
 - 属性titles @3
 - 属性hasBranch @1
 - 属性hasKanbanExecution @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$product1 = new stdClass();
$product1->id   = 1;
$product1->name = 'Normal Product';
$product1->type = 'normal';

$product2 = new stdClass();
$product2->id   = 2;
$product2->name = 'Branch Product';
$product2->type = 'branch';

$project1 = new stdClass();
$project1->id    = 1;
$project1->name  = 'Sprint Project';
$project1->model = 'scrum';

$project2 = new stdClass();
$project2->id    = 2;
$project2->name  = 'Kanban Project';
$project2->model = 'kanban';

$bugImagesFile1 = array();

$bugImagesFile2 = array(
    'image1.png' => array('title' => 'Bug Screenshot 1'),
    'image2.png' => array('title' => 'Bug Screenshot 2'),
    'image3.png' => array('title' => 'Bug Screenshot 3'),
);

$bugTest = new bugZenTest();

r($bugTest->assignVarsForBatchCreateTest($product1, $project1, $bugImagesFile1)) && p('customFields,titles,hasBranch,hasKanbanExecution') && e('12,0,0,0');
r($bugTest->assignVarsForBatchCreateTest($product2, $project1, $bugImagesFile1)) && p('customFields,titles,hasBranch,hasKanbanExecution') && e('13,0,1,0');
r($bugTest->assignVarsForBatchCreateTest($product1, $project2, $bugImagesFile1)) && p('customFields,titles,hasBranch,hasKanbanExecution') && e('12,0,0,1');
r($bugTest->assignVarsForBatchCreateTest($product1, $project1, $bugImagesFile2)) && p('customFields,titles,hasBranch,hasKanbanExecution') && e('12,3,0,0');
r($bugTest->assignVarsForBatchCreateTest($product2, $project2, $bugImagesFile2)) && p('customFields,titles,hasBranch,hasKanbanExecution') && e('13,3,1,1');