#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::moveCardByModal();
timeout=0
cid=0

- 步骤1:获取存在的卡片1的移动视图数据属性regions @1
- 步骤2:获取存在的卡片2的移动视图数据第card条的id属性 @2
- 步骤3:获取存在的卡片3的移动视图数据第card条的name属性 @卡片3
- 步骤4:获取存在的卡片4的移动视图数据第card条的status属性 @doing
- 步骤5:获取不存在的卡片999的移动视图数据属性regions @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zendata('kanbancard')->loadYaml('kanbancard_movecardbymodal', false, 2)->gen(10);
zendata('kanbanregion')->loadYaml('kanbanregion_movecardbymodal', false, 2)->gen(3);
zendata('kanbancell')->loadYaml('kanbancell_movecardbymodal', false, 2)->gen(20);
zendata('kanbanlane')->loadYaml('kanbanlane_movecardbymodal', false, 2)->gen(5);
zendata('kanbancolumn')->loadYaml('kanbancolumn_movecardbymodal', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$kanbanTest = new kanbanZenTest();

// 5. 测试步骤
r($kanbanTest->moveCardByModalTest(1)) && p('regions') && e('1'); // 步骤1:获取存在的卡片1的移动视图数据
r($kanbanTest->moveCardByModalTest(2)) && p('card:id') && e('2'); // 步骤2:获取存在的卡片2的移动视图数据
r($kanbanTest->moveCardByModalTest(3)) && p('card:name') && e('卡片3'); // 步骤3:获取存在的卡片3的移动视图数据
r($kanbanTest->moveCardByModalTest(4)) && p('card:status') && e('doing'); // 步骤4:获取存在的卡片4的移动视图数据
r($kanbanTest->moveCardByModalTest(999)) && p('regions') && e('0'); // 步骤5:获取不存在的卡片999的移动视图数据