#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(500);

/**

title=测试 kanbanModel->setWIP();
timeout=0
cid=1

- 测试设置看板列401的在制品限制属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列402的在制品限制
 - 属性name @进行中
 - 属性limit @100
- 测试设置看板列404的在制品限制
 - 属性name @已关闭
 - 属性limit @100
- 测试设置看板列405的在制品限制
 - 属性name @未开始
 - 属性limit @100
- 测试设置看板列403的在制品限制属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列406的在制品限制 子列未设置在制品数量属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列407的在制品限制
 - 属性name @已完成
 - 属性limit @100
- 测试设置看板列408的在制品限制
 - 属性name @已关闭
 - 属性limit @100
- 测试设置看板列406的在制品限制 父列小于子列属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列406的在制品限制属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列408的在制品限制 子列大于父列属性limit @子列在制品数量之和不能大于父列的在制品数量
- 测试设置看板列401的在制品限制 非正整数属性limit @在制品数量必须是正整数。

*/

$columnIDList = array('401', '402', '403', '404', '405', '406', '407', '408');
$limitList    = array('-1', '0', '100', '150', '220');

$kanban = new kanbanTest();

r($kanban->setWIPTest($columnIDList[0], $limitList[0], -1))  && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列401的在制品限制
r($kanban->setWIPTest($columnIDList[1], $limitList[2], 0))   && p('name,limit') && e('进行中,100');                                 // 测试设置看板列402的在制品限制
r($kanban->setWIPTest($columnIDList[3], $limitList[2], -1))  && p('name,limit') && e('已关闭,100');                                 // 测试设置看板列404的在制品限制
r($kanban->setWIPTest($columnIDList[4], $limitList[2], 0))   && p('name,limit') && e('未开始,100');                                 // 测试设置看板列405的在制品限制
r($kanban->setWIPTest($columnIDList[2], $limitList[4], -1))  && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列403的在制品限制
r($kanban->setWIPTest($columnIDList[5], $limitList[4], -1))  && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列406的在制品限制 子列未设置在制品数量
r($kanban->setWIPTest($columnIDList[6], $limitList[2], -1))  && p('name,limit') && e('已完成,100');                                 // 测试设置看板列407的在制品限制
r($kanban->setWIPTest($columnIDList[7], $limitList[2], 0))   && p('name,limit') && e('已关闭,100');                                 // 测试设置看板列408的在制品限制
r($kanban->setWIPTest($columnIDList[5], $limitList[3], 0))   && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列406的在制品限制 父列小于子列
r($kanban->setWIPTest($columnIDList[5], $limitList[4], 0))   && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列406的在制品限制
r($kanban->setWIPTest($columnIDList[7], $limitList[0], -1))  && p('limit')      && e('子列在制品数量之和不能大于父列的在制品数量'); // 测试设置看板列408的在制品限制 子列大于父列
r($kanban->setWIPTest($columnIDList[0], $limitList[1], 0))   && p('limit')      && e('在制品数量必须是正整数。');                   // 测试设置看板列401的在制品限制 非正整数