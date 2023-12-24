#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

/**

title=测试 treeModel->buildMenuQuery();
timeout=0
cid=1

- 测试查询root 1 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '1' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 2 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '2' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 3 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '3' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 41 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 42 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '42' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 43 type story 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '43' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 101 type task 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '101' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 102 type task 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '102' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 103 type task 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '103' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 41 type story startModule 1821的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

- 测试查询root 41 type story startModule 0 branch 1 的查询语句 @SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  (branch  = '0' OR `branch`  = '1') AND  `deleted`  = '0' ORDER BY `grade` desc,`order`

*/

$root        = array(1, 2, 3, 41, 42, 43, 101, 102, 103);
$type        = array('story', 'task');
$startModule = array(1821, 1982);
$branch      = 1;

$tree = new treeTest();

r($tree->buildMenuQueryTest($root[0], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '1' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                 // 测试查询root 1 type story 的查询语句
r($tree->buildMenuQueryTest($root[1], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '2' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                 // 测试查询root 2 type story 的查询语句
r($tree->buildMenuQueryTest($root[2], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '3' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                 // 测试查询root 3 type story 的查询语句
r($tree->buildMenuQueryTest($root[3], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 41 type story 的查询语句
r($tree->buildMenuQueryTest($root[4], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '42' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 42 type story 的查询语句
r($tree->buildMenuQueryTest($root[5], $type[0]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '43' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 43 type story 的查询语句
r($tree->buildMenuQueryTest($root[6], $type[1]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '101' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 101 type task 的查询语句
r($tree->buildMenuQueryTest($root[7], $type[1]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '102' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 102 type task 的查询语句
r($tree->buildMenuQueryTest($root[8], $type[1]))                           && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '103' AND  `type` = 'task' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 103 type task 的查询语句
r($tree->buildMenuQueryTest($root[3], $type[0], $startModule[0]))          && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  `deleted`  = '0' ORDER BY `grade` desc,`order`");                // 测试查询root 41 type story startModule 1821的查询语句
r($tree->buildMenuQueryTest($root[3], $type[0], $startModule[1], $branch)) && p() && e("SELECT * FROM `zt_module` WHERE 1=1  AND  `root`  = '41' AND  `type` = 'story' AND  (branch  = '0' OR `branch`  = '1') AND  `deleted`  = '0' ORDER BY `grade` desc,`order`"); // 测试查询root 41 type story startModule 0 branch 1 的查询语句