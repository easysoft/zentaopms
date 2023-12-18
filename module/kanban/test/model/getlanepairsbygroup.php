#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanlane')->gen(10);

/**

title=测试 kanbanModel->getLanePairsByGroup();
timeout=0
cid=1

- 获取泳道组1的泳道 @默认泳道
- 获取泳道组2的泳道 @默认泳道
- 获取泳道组3的泳道 @默认泳道
- 获取泳道组4的泳道 @默认泳道
- 获取泳道组5的泳道 @默认泳道
- 获取不存在泳道组的泳道 @0

*/

$groupIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getLanePairsByGroupTest($groupIDList[0])) && p() && e('默认泳道'); // 获取泳道组1的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[1])) && p() && e('默认泳道'); // 获取泳道组2的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[2])) && p() && e('默认泳道'); // 获取泳道组3的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[3])) && p() && e('默认泳道'); // 获取泳道组4的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[4])) && p() && e('默认泳道'); // 获取泳道组5的泳道
r($kanban->getLanePairsByGroupTest($groupIDList[5])) && p() && e('0');        // 获取不存在泳道组的泳道