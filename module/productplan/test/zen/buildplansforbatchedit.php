#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildPlansForBatchEdit();
timeout=0
cid=0

- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData1
 - 第1条的begin属性 @2024-01-01
 - 第1条的end属性 @2024-06-30
 - 第1条的status属性 @doing
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData2 第begin条的0属性 @『计划3』的开始时间不能大于结束时间。
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData3
 - 第4条的begin属性 @2030-01-01
 - 第4条的end属性 @2030-01-01
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData4 第begin条的0属性 @『计划11』的开始时间 2024-01-01 不能小于父计划的开始时间 2024-03-01。
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData5 第end条的0属性 @『计划12』的结束时间 2024-12-31 不能大于父计划的结束时间 2024-10-31。
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData6
 - 第7条的begin属性 @2024-03-15
 - 第7条的end属性 @2024-09-15
 - 第7条的status属性 @wait
- 执行productplanTest模块的buildPlansForBatchEditTest方法，参数是$postData7 第8条的branch属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('productplan')->loadYaml('productplan', false, 2)->gen(20);

su('admin');

$productplanTest = new productplanZenTest();

// 测试场景1: 正常批量编辑计划
$postData1 = array(
    'planIDList' => array(1, 2),
    'id' => array(1 => 1, 2 => 2),
    'title' => array(1 => '计划1', 2 => '计划2'),
    'begin' => array(1 => '2024-01-01', 2 => '2024-02-01'),
    'end' => array(1 => '2024-06-30', 2 => '2024-07-31'),
    'status' => array(1 => 'doing', 2 => 'wait'),
    'branch' => array(1 => '1', 2 => '2')
);
r($productplanTest->buildPlansForBatchEditTest($postData1)) && p('1:begin,end,status') && e('2024-01-01,2024-06-30,doing');

// 测试场景2: 开始日期大于结束日期
$postData2 = array(
    'planIDList' => array(3),
    'id' => array(3 => 3),
    'title' => array(3 => '计划3'),
    'begin' => array(3 => '2024-12-31'),
    'end' => array(3 => '2024-01-01'),
    'status' => array(3 => 'wait'),
    'branch' => array(3 => '0')
);
r($productplanTest->buildPlansForBatchEditTest($postData2)) && p('begin:0') && e('『计划3』的开始时间不能大于结束时间。');

// 测试场景3: 未来日期标记
$postData3 = array(
    'planIDList' => array(4),
    'id' => array(4 => 4),
    'title' => array(4 => '计划4'),
    'begin' => array(4 => ''),
    'end' => array(4 => ''),
    'status' => array(4 => 'wait'),
    'branch' => array(4 => '0'),
    'future' => array(4 => '1')
);
r($productplanTest->buildPlansForBatchEditTest($postData3)) && p('4:begin,end') && e('2030-01-01,2030-01-01');

// 测试场景4: 子计划开始日期早于父计划
$postData4 = array(
    'planIDList' => array(5, 11),
    'id' => array(5 => 5, 11 => 11),
    'title' => array(5 => '计划5', 11 => '子计划11'),
    'begin' => array(5 => '2024-03-01', 11 => '2024-01-01'),
    'end' => array(5 => '2024-12-31', 11 => '2024-08-31'),
    'status' => array(5 => 'wait', 11 => 'wait'),
    'branch' => array(5 => '0', 11 => '0')
);
r($productplanTest->buildPlansForBatchEditTest($postData4)) && p('begin:0') && e('『计划11』的开始时间 2024-01-01 不能小于父计划的开始时间 2024-03-01。');

// 测试场景5: 子计划结束日期晚于父计划
$postData5 = array(
    'planIDList' => array(6, 12),
    'id' => array(6 => 6, 12 => 12),
    'title' => array(6 => '计划6', 12 => '子计划12'),
    'begin' => array(6 => '2024-03-01', 12 => '2024-04-01'),
    'end' => array(6 => '2024-10-31', 12 => '2024-12-31'),
    'status' => array(6 => 'wait', 12 => 'wait'),
    'branch' => array(6 => '0', 12 => '0')
);
r($productplanTest->buildPlansForBatchEditTest($postData5)) && p('end:0') && e('『计划12』的结束时间 2024-12-31 不能大于父计划的结束时间 2024-10-31。');

// 测试场景6: 空字段使用旧值
$postData6 = array(
    'planIDList' => array(7),
    'id' => array(7 => 7),
    'title' => array(7 => '计划7'),
    'begin' => array(7 => ''),
    'end' => array(7 => ''),
    'status' => array(7 => ''),
    'branch' => array(7 => '0')
);
r($productplanTest->buildPlansForBatchEditTest($postData6)) && p('7:begin,end,status') && e('2024-03-15,2024-09-15,wait');

// 测试场景7: 分支字段为空设置为0
$postData7 = array(
    'planIDList' => array(8),
    'id' => array(8 => 8),
    'title' => array(8 => '计划8'),
    'begin' => array(8 => '2024-05-01'),
    'end' => array(8 => '2024-11-30'),
    'status' => array(8 => 'doing'),
    'branch' => array(8 => '')
);
r($productplanTest->buildPlansForBatchEditTest($postData7)) && p('8:branch') && e('0');