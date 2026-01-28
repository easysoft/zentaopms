#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildNodeAssociationData();
timeout=0
cid=15819

- 执行convertTest模块的buildNodeAssociationDataTest方法，参数是array 属性source_node_id @1001
- 执行convertTest模块的buildNodeAssociationDataTest方法，参数是array 属性source_node_entity @project
- 执行convertTest模块的buildNodeAssociationDataTest方法，参数是array 属性sink_node_id @3002
- 执行convertTest模块的buildNodeAssociationDataTest方法，参数是array 属性sink_node_entity @testcase
- 执行convertTest模块的buildNodeAssociationDataTest方法，参数是array 属性association_type @parent-child

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTaoTest();

// 4. 测试步骤
r($convertTest->buildNodeAssociationDataTest(array('sourceNodeId' => '1001', 'sourceNodeEntity' => 'issue', 'sinkNodeId' => '1002', 'sinkNodeEntity' => 'issue', 'associationType' => 'depends'))) && p('source_node_id') && e('1001');
r($convertTest->buildNodeAssociationDataTest(array('sourceNodeId' => '', 'sourceNodeEntity' => 'project', 'sinkNodeId' => '2001', 'sinkNodeEntity' => '', 'associationType' => 'blocks'))) && p('source_node_entity') && e('project');
r($convertTest->buildNodeAssociationDataTest(array('sourceNodeId' => '3001', 'sourceNodeEntity' => 'story', 'sinkNodeId' => '3002', 'sinkNodeEntity' => 'task', 'associationType' => 'relates'))) && p('sink_node_id') && e('3002');
r($convertTest->buildNodeAssociationDataTest(array('sourceNodeId' => '4001', 'sourceNodeEntity' => 'bug', 'sinkNodeId' => '4002', 'sinkNodeEntity' => 'testcase', 'associationType' => 'duplicate'))) && p('sink_node_entity') && e('testcase');
r($convertTest->buildNodeAssociationDataTest(array('sourceNodeId' => '5001', 'sourceNodeEntity' => 'issue-type', 'sinkNodeId' => '5002', 'sinkNodeEntity' => 'custom-field', 'associationType' => 'parent-child'))) && p('association_type') && e('parent-child');