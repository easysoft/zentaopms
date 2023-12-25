#!/usr/bin/env php
<?php

/**

title=productTao->buildExecutionPairs();
cid=0

- 开启含有项目名称选项，检查敏捷项目。 @敏捷项目1/迭代5
- 开启含有项目名称选项，检查不启用迭代的项目。 @敏捷项目1(不启用迭代的项目)
- 开启含有项目名称选项，检查包含子阶段的项目。 @瀑布项目2/阶段10/阶段16
- 开启含有项目名称选项，检查无子阶段的项目。 @瀑布项目3/阶段24
- 开启含有项目名称选项，检查看板项目。 @看板项目4/看板28
- 不开启含有项目名称选项，检查敏捷项目。 @/迭代5
- 不开启含有项目名称选项，检查不启用迭代的项目。 @敏捷项目1(不启用迭代的项目)
- 不开启含有项目名称选项，检查包含子阶段的项目。 @/阶段10/阶段16
- 不开启含有项目名称选项，检查无子阶段的项目。 @/阶段24
- 不开启含有项目名称选项，检查看板项目。 @/看板28
- 开启stagefliter模式，过滤request,design,review属性的阶段，检查request的阶段是否存在。 @1
- 开启stagefliter模式，过滤request,design,review属性的阶段，检查dev的阶段是否存在。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('project')->config('execution')->gen(32);
$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('101-150');
$projectProduct->product->range('1');
$projectProduct->gen(28);

$product = new productTest('admin');

$executions = $product->buildExecutionPairsTest('', true);
r($executions[101]) && p() && e('敏捷项目1/迭代5');             // 开启含有项目名称选项，检查敏捷项目。
r($executions[104]) && p() && e('敏捷项目1(不启用迭代的项目)'); // 开启含有项目名称选项，检查不启用迭代的项目。
r($executions[112]) && p() && e('瀑布项目2/阶段10/阶段16');     // 开启含有项目名称选项，检查包含子阶段的项目。
r($executions[120]) && p() && e('瀑布项目3/阶段24');            // 开启含有项目名称选项，检查无子阶段的项目。
r($executions[124]) && p() && e('看板项目4/看板28');            // 开启含有项目名称选项，检查看板项目。

$executions = $product->buildExecutionPairsTest('', false);
r($executions[101]) && p() && e('/迭代5');                      // 不开启含有项目名称选项，检查敏捷项目。
r($executions[104]) && p() && e('敏捷项目1(不启用迭代的项目)'); // 不开启含有项目名称选项，检查不启用迭代的项目。
r($executions[112]) && p() && e('/阶段10/阶段16');              // 不开启含有项目名称选项，检查包含子阶段的项目。
r($executions[120]) && p() && e('/阶段24');                     // 不开启含有项目名称选项，检查无子阶段的项目。
r($executions[124]) && p() && e('/看板28');                     // 不开启含有项目名称选项，检查看板项目。

$executions = $product->buildExecutionPairsTest('stagefilter', false);
r(!isset($executions[112])) && p() && e('1'); // 开启stagefliter模式，过滤request,design,review属性的阶段，检查request的阶段是否存在。
r(isset($executions[114]))  && p() && e('1'); // 开启stagefliter模式，过滤request,design,review属性的阶段，检查dev的阶段是否存在。
