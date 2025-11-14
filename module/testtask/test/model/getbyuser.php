#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->loadYaml('product')->gen(2);
zenData('project')->loadYaml('project')->gen(5);
zenData('build')->loadYaml('build')->gen(1);
zenData('testtask')->loadYaml('testtask')->gen(24);

/**

title=测试 testtaskModel->getByUser();
timeout=0
cid=19167

- 执行testtask模块的getByUser方法，参数是''  @0
- 执行testtask模块的getByUser方法，参数是'admin'  @0
- 执行$tasks @5
- 获取 ID 为 8 的测试单的详细信息。
 - 第8条的product属性 @1
 - 第8条的productName属性 @正常产品1
 - 第8条的project属性 @1
 - 第8条的projectName属性 @项目1
 - 第8条的execution属性 @5
 - 第8条的executionName属性 @执行4
 - 第8条的executionMultiple属性 @1
 - 第8条的build属性 @1
 - 第8条的buildName属性 @项目1版本1
 - 第8条的id属性 @8
 - 第8条的name属性 @测试单8
 - 第8条的owner属性 @user3
 - 第8条的desc属性 @这是测试单描述8
 - 第8条的status属性 @doing
 - 第8条的auto属性 @no
- 获取 ID 为 7 的测试单的详细信息。
 - 第7条的product属性 @1
 - 第7条的productName属性 @正常产品1
 - 第7条的project属性 @1
 - 第7条的projectName属性 @项目1
 - 第7条的execution属性 @4
 - 第7条的executionName属性 @执行3
 - 第7条的executionMultiple属性 @1
 - 第7条的build属性 @1
 - 第7条的buildName属性 @项目1版本1
 - 第7条的id属性 @7
 - 第7条的name属性 @测试单7
 - 第7条的owner属性 @user3
 - 第7条的desc属性 @这是测试单描述7
 - 第7条的status属性 @doing
 - 第7条的auto属性 @no
- 获取 ID 为 6 的测试单的详细信息。
 - 第6条的product属性 @1
 - 第6条的productName属性 @正常产品1
 - 第6条的project属性 @1
 - 第6条的projectName属性 @项目1
 - 第6条的execution属性 @3
 - 第6条的executionName属性 @执行2
 - 第6条的executionMultiple属性 @1
 - 第6条的build属性 @1
 - 第6条的buildName属性 @项目1版本1
 - 第6条的id属性 @6
 - 第6条的name属性 @测试单6
 - 第6条的owner属性 @user3
 - 第6条的desc属性 @这是测试单描述6
 - 第6条的status属性 @doing
 - 第6条的auto属性 @no
- 执行$tasks @10
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @5
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @5
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @5
- 获取 ID 为 12 的测试单的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的projectName属性 @项目1
 - 第12条的execution属性 @5
 - 第12条的executionName属性 @执行4
 - 第12条的executionMultiple属性 @1
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目1版本1
 - 第12条的id属性 @12
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user4
 - 第12条的desc属性 @这是测试单描述12
 - 第12条的status属性 @wait
 - 第12条的auto属性 @no
- 获取 ID 为 13 的测试单的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的projectName属性 @项目1
 - 第13条的execution属性 @2
 - 第13条的executionName属性 @执行1
 - 第13条的executionMultiple属性 @1
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目1版本1
 - 第13条的id属性 @13
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的desc属性 @这是测试单描述13
 - 第13条的status属性 @doing
 - 第13条的auto属性 @no
- 获取 ID 为 14 的测试单的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的projectName属性 @项目1
 - 第14条的execution属性 @3
 - 第14条的executionName属性 @执行2
 - 第14条的executionMultiple属性 @1
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目1版本1
 - 第14条的id属性 @14
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user4
 - 第14条的desc属性 @这是测试单描述14
 - 第14条的status属性 @doing
 - 第14条的auto属性 @no
- 执行$tasks @10
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @10
- 获取 ID 为 12 的测试单的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的projectName属性 @项目1
 - 第12条的execution属性 @5
 - 第12条的executionName属性 @执行4
 - 第12条的executionMultiple属性 @1
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目1版本1
 - 第12条的id属性 @12
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user4
 - 第12条的desc属性 @这是测试单描述12
 - 第12条的status属性 @wait
 - 第12条的auto属性 @no
- 获取 ID 为 13 的测试单的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的projectName属性 @项目1
 - 第13条的execution属性 @2
 - 第13条的executionName属性 @执行1
 - 第13条的executionMultiple属性 @1
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目1版本1
 - 第13条的id属性 @13
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的desc属性 @这是测试单描述13
 - 第13条的status属性 @doing
 - 第13条的auto属性 @no
- 获取 ID 为 14 的测试单的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的projectName属性 @项目1
 - 第14条的execution属性 @3
 - 第14条的executionName属性 @执行2
 - 第14条的executionMultiple属性 @1
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目1版本1
 - 第14条的id属性 @14
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user4
 - 第14条的desc属性 @这是测试单描述14
 - 第14条的status属性 @doing
 - 第14条的auto属性 @no
- 获取 ID 为 15 的测试单的详细信息。
 - 第15条的product属性 @1
 - 第15条的productName属性 @正常产品1
 - 第15条的project属性 @1
 - 第15条的projectName属性 @项目1
 - 第15条的execution属性 @4
 - 第15条的executionName属性 @执行3
 - 第15条的executionMultiple属性 @1
 - 第15条的build属性 @1
 - 第15条的buildName属性 @项目1版本1
 - 第15条的id属性 @15
 - 第15条的name属性 @测试单15
 - 第15条的owner属性 @user4
 - 第15条的desc属性 @这是测试单描述15
 - 第15条的status属性 @doing
 - 第15条的auto属性 @no
- 执行$tasks @9
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @9
- 获取 ID 为 12 的测试单的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的projectName属性 @项目1
 - 第12条的execution属性 @5
 - 第12条的executionName属性 @执行4
 - 第12条的executionMultiple属性 @1
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目1版本1
 - 第12条的id属性 @12
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user4
 - 第12条的desc属性 @这是测试单描述12
 - 第12条的status属性 @wait
 - 第12条的auto属性 @no
- 获取 ID 为 13 的测试单的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的projectName属性 @项目1
 - 第13条的execution属性 @2
 - 第13条的executionName属性 @执行1
 - 第13条的executionMultiple属性 @1
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目1版本1
 - 第13条的id属性 @13
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的desc属性 @这是测试单描述13
 - 第13条的status属性 @doing
 - 第13条的auto属性 @no
- 获取 ID 为 14 的测试单的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的projectName属性 @项目1
 - 第14条的execution属性 @3
 - 第14条的executionName属性 @执行2
 - 第14条的executionMultiple属性 @1
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目1版本1
 - 第14条的id属性 @14
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user4
 - 第14条的desc属性 @这是测试单描述14
 - 第14条的status属性 @doing
 - 第14条的auto属性 @no
- 执行$tasks @1
- 获取 ID 为 20 的测试单的详细信息。
 - 第20条的product属性 @1
 - 第20条的productName属性 @正常产品1
 - 第20条的project属性 @1
 - 第20条的projectName属性 @项目1
 - 第20条的execution属性 @5
 - 第20条的executionName属性 @执行4
 - 第20条的executionMultiple属性 @1
 - 第20条的build属性 @1
 - 第20条的buildName属性 @项目1版本1
 - 第20条的id属性 @20
 - 第20条的name属性 @测试单20
 - 第20条的owner属性 @user4
 - 第20条的desc属性 @这是测试单描述20
 - 第20条的status属性 @done
 - 第20条的auto属性 @no
- 执行$tasks @1
- 获取 ID 为 20 的测试单的详细信息。
 - 第20条的product属性 @1
 - 第20条的productName属性 @正常产品1
 - 第20条的project属性 @1
 - 第20条的projectName属性 @项目1
 - 第20条的execution属性 @5
 - 第20条的executionName属性 @执行4
 - 第20条的executionMultiple属性 @1
 - 第20条的build属性 @1
 - 第20条的buildName属性 @项目1版本1
 - 第20条的id属性 @20
 - 第20条的name属性 @测试单20
 - 第20条的owner属性 @user4
 - 第20条的desc属性 @这是测试单描述20
 - 第20条的status属性 @done
 - 第20条的auto属性 @no
- 执行$tasks @5
- 获取 ID 为 24 的测试单的详细信息。
 - 第24条的product属性 @1
 - 第24条的productName属性 @正常产品1
 - 第24条的project属性 @1
 - 第24条的projectName属性 @项目1
 - 第24条的execution属性 @5
 - 第24条的executionName属性 @执行4
 - 第24条的executionMultiple属性 @1
 - 第24条的build属性 @1
 - 第24条的buildName属性 @项目1版本1
 - 第24条的id属性 @24
 - 第24条的name属性 @测试单24
 - 第24条的owner属性 @user4
 - 第24条的desc属性 @这是测试单描述24
 - 第24条的status属性 @blocked
 - 第24条的auto属性 @no
- 获取 ID 为 23 的测试单的详细信息。
 - 第23条的product属性 @1
 - 第23条的productName属性 @正常产品1
 - 第23条的project属性 @1
 - 第23条的projectName属性 @项目1
 - 第23条的execution属性 @4
 - 第23条的executionName属性 @执行3
 - 第23条的executionMultiple属性 @1
 - 第23条的build属性 @1
 - 第23条的buildName属性 @项目1版本1
 - 第23条的id属性 @23
 - 第23条的name属性 @测试单23
 - 第23条的owner属性 @user4
 - 第23条的desc属性 @这是测试单描述23
 - 第23条的status属性 @blocked
 - 第23条的auto属性 @no
- 获取 ID 为 22 的测试单的详细信息。
 - 第22条的product属性 @1
 - 第22条的productName属性 @正常产品1
 - 第22条的project属性 @1
 - 第22条的projectName属性 @项目1
 - 第22条的execution属性 @3
 - 第22条的executionName属性 @执行2
 - 第22条的executionMultiple属性 @1
 - 第22条的build属性 @1
 - 第22条的buildName属性 @项目1版本1
 - 第22条的id属性 @22
 - 第22条的name属性 @测试单22
 - 第22条的owner属性 @user4
 - 第22条的desc属性 @这是测试单描述22
 - 第22条的status属性 @blocked
 - 第22条的auto属性 @no
- 执行$tasks @5
- 获取 ID 为 12 的测试单的详细信息。
 - 第12条的product属性 @1
 - 第12条的productName属性 @正常产品1
 - 第12条的project属性 @1
 - 第12条的projectName属性 @项目1
 - 第12条的execution属性 @5
 - 第12条的executionName属性 @执行4
 - 第12条的executionMultiple属性 @1
 - 第12条的build属性 @1
 - 第12条的buildName属性 @项目1版本1
 - 第12条的id属性 @12
 - 第12条的name属性 @测试单12
 - 第12条的owner属性 @user4
 - 第12条的desc属性 @这是测试单描述12
 - 第12条的status属性 @wait
 - 第12条的auto属性 @no
- 获取 ID 为 13 的测试单的详细信息。
 - 第13条的product属性 @1
 - 第13条的productName属性 @正常产品1
 - 第13条的project属性 @1
 - 第13条的projectName属性 @项目1
 - 第13条的execution属性 @2
 - 第13条的executionName属性 @执行1
 - 第13条的executionMultiple属性 @1
 - 第13条的build属性 @1
 - 第13条的buildName属性 @项目1版本1
 - 第13条的id属性 @13
 - 第13条的name属性 @测试单13
 - 第13条的owner属性 @user4
 - 第13条的desc属性 @这是测试单描述13
 - 第13条的status属性 @doing
 - 第13条的auto属性 @no
- 获取 ID 为 14 的测试单的详细信息。
 - 第14条的product属性 @1
 - 第14条的productName属性 @正常产品1
 - 第14条的project属性 @1
 - 第14条的projectName属性 @项目1
 - 第14条的execution属性 @3
 - 第14条的executionName属性 @执行2
 - 第14条的executionMultiple属性 @1
 - 第14条的build属性 @1
 - 第14条的buildName属性 @项目1版本1
 - 第14条的id属性 @14
 - 第14条的name属性 @测试单14
 - 第14条的owner属性 @user4
 - 第14条的desc属性 @这是测试单描述14
 - 第14条的status属性 @doing
 - 第14条的auto属性 @no
- 执行$tasks @1
- 获取 ID 为 20 的测试单的详细信息。
 - 第20条的product属性 @1
 - 第20条的productName属性 @正常产品1
 - 第20条的project属性 @1
 - 第20条的projectName属性 @项目1
 - 第20条的execution属性 @5
 - 第20条的executionName属性 @执行4
 - 第20条的executionMultiple属性 @1
 - 第20条的build属性 @1
 - 第20条的buildName属性 @项目1版本1
 - 第20条的id属性 @20
 - 第20条的name属性 @测试单20
 - 第20条的owner属性 @user4
 - 第20条的desc属性 @这是测试单描述20
 - 第20条的status属性 @done
 - 第20条的auto属性 @no
- 执行$tasks @1
- 获取 ID 为 20 的测试单的详细信息。
 - 第20条的product属性 @1
 - 第20条的productName属性 @正常产品1
 - 第20条的project属性 @1
 - 第20条的projectName属性 @项目1
 - 第20条的execution属性 @5
 - 第20条的executionName属性 @执行4
 - 第20条的executionMultiple属性 @1
 - 第20条的build属性 @1
 - 第20条的buildName属性 @项目1版本1
 - 第20条的id属性 @20
 - 第20条的name属性 @测试单20
 - 第20条的owner属性 @user4
 - 第20条的desc属性 @这是测试单描述20
 - 第20条的status属性 @done
 - 第20条的auto属性 @no

*/

global $tester, $app;

$app->user->view->sprints = implode(',', range(1, 10));
$app->rawModule = 'my';
$app->rawMethod = 'testtask';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getByUser(''))      && p() && e(0);
r($testtask->getByUser('admin')) && p() && e(0);

$tasks = $testtask->getByUser('user3');
r(count($tasks)) && p() && e(5);
r($tasks) && p('8:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,8,测试单8,user3,这是测试单描述8,doing,no'); // 获取 ID 为 8 的测试单的详细信息。
r($tasks) && p('7:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,7,测试单7,user3,这是测试单描述7,doing,no'); // 获取 ID 为 7 的测试单的详细信息。
r($tasks) && p('6:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,6,测试单6,user3,这是测试单描述6,doing,no'); // 获取 ID 为 6 的测试单的详细信息。

$tasks = $testtask->getByUser('user4');
r(count($tasks)) && p() && e(10);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager);
r(count($tasks)) && p() && e(5);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc');
r(count($tasks)) && p() && e(5);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc');
r(count($tasks)) && p() && e(5);
r($tasks) && p('12:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('13:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('14:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc');
r(count($tasks)) && p() && e(10);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc');
r(count($tasks)) && p() && e(10);
r($tasks) && p('12:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('13:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('14:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。
r($tasks) && p('15:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,15,测试单15,user4,这是测试单描述15,doing,no'); // 获取 ID 为 15 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc', 'wait');
r(count($tasks)) && p() && e(9);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc', 'wait');
r(count($tasks)) && p() && e(9);
r($tasks) && p('12:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('13:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('14:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_desc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('20:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', null, 'id_asc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('20:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc', 'wait');
r(count($tasks)) && p() && e(5);
r($tasks) && p('24:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,24,测试单24,user4,这是测试单描述24,blocked,no'); // 获取 ID 为 24 的测试单的详细信息。
r($tasks) && p('23:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,4,执行3,1,1,项目1版本1,23,测试单23,user4,这是测试单描述23,blocked,no'); // 获取 ID 为 23 的测试单的详细信息。
r($tasks) && p('22:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,22,测试单22,user4,这是测试单描述22,blocked,no'); // 获取 ID 为 22 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc', 'wait');
r(count($tasks)) && p() && e(5);
r($tasks) && p('12:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,12,测试单12,user4,这是测试单描述12,wait,no');  // 获取 ID 为 12 的测试单的详细信息。
r($tasks) && p('13:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,2,执行1,1,1,项目1版本1,13,测试单13,user4,这是测试单描述13,doing,no'); // 获取 ID 为 13 的测试单的详细信息。
r($tasks) && p('14:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,3,执行2,1,1,项目1版本1,14,测试单14,user4,这是测试单描述14,doing,no'); // 获取 ID 为 14 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_desc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('20:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。

$tasks = $testtask->getByUser('user4', $pager, 'id_asc', 'done');
r(count($tasks)) && p() && e(1);
r($tasks) && p('20:product,productName,project,projectName,execution,executionName,executionMultiple,build,buildName,id,name,owner,desc,status,auto') && e('1,正常产品1,1,项目1,5,执行4,1,1,项目1版本1,20,测试单20,user4,这是测试单描述20,done,no');  // 获取 ID 为 20 的测试单的详细信息。
