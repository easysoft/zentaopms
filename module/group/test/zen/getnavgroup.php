#!/usr/bin/env php
<?php

/**

title=测试 groupZen::getNavGroup();
timeout=0
cid=0

- 步骤1:检查my分组包含effort第my条的effort属性 @effort
- 步骤2:检查my分组包含score第my条的score属性 @score
- 步骤3:检查product分组包含story第product条的story属性 @story
- 步骤4:检查execution分组包含task第execution条的task属性 @task
- 步骤5:验证testcase映射为case第qa条的case属性 @case
- 步骤6:检查project分组包含design第project条的design属性 @design
- 步骤7:检查doc分组包含api第doc条的api属性 @api
- 步骤8:检查admin分组包含group第admin条的group属性 @group

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$groupTest = new groupZenTest();

r($groupTest->getNavGroupTest()) && p('my:effort') && e('effort'); // 步骤1:检查my分组包含effort
r($groupTest->getNavGroupTest()) && p('my:score') && e('score'); // 步骤2:检查my分组包含score
r($groupTest->getNavGroupTest()) && p('product:story') && e('story'); // 步骤3:检查product分组包含story
r($groupTest->getNavGroupTest()) && p('execution:task') && e('task'); // 步骤4:检查execution分组包含task
r($groupTest->getNavGroupTest()) && p('qa:case') && e('case'); // 步骤5:验证testcase映射为case
r($groupTest->getNavGroupTest()) && p('project:design') && e('design'); // 步骤6:检查project分组包含design
r($groupTest->getNavGroupTest()) && p('doc:api') && e('api'); // 步骤7:检查doc分组包含api
r($groupTest->getNavGroupTest()) && p('admin:group') && e('group'); // 步骤8:检查admin分组包含group