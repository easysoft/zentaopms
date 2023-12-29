#!/usr/bin/env php
<?php
/**

title=测试programplanModel->updateSubStageAttr();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);

$plan = new programplanTest();
r($plan->updateSubStageAttrTest(1, 'mix'))    && p() && e('1');      // 测试更改 id 为 3 存在子阶段的阶段，attribute 更新值为 mix ，结果为 1
r($plan->updateSubStageAttrTest(5, 'design')) && p() && e(1);        // 测试更改 id 为 5 无子阶段的阶段，更新 attribute 值为 design ，结果为 1
r($plan->updateSubStageAttrTest(2, 'design')) && p() && e('design'); // 测试更改 id 为 2 存在子阶段的阶段，更新 attribute 值为 design ，结果为 design
r($plan->updateSubStageAttrTest(3, 'design')) && p() && e('design'); // 测试更改 id 为 3 存在子阶段，子阶段下也存在子阶段，更新 attribute 值为 design ，结果为 design
