#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->processRules();
timeout=0
cid=1

- 测试规则属性storyReg @((Story) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性taskReg @((Task) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性bugReg @((Bug) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性costReg @(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性leftReg @(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性startTaskReg @(Start) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性effortTaskReg @(Effort) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性finishTaskReg @(Finish) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性resolveBugReg @(Fix) *((Bug) *((\#)[0-9]+((\,)[0-9]+)*))
- 规则次数 @9

*/

$repo = $tester->loadModel('repo');

$result = $repo->processRules();
r($result) && p('storyReg', ';')      && e('((Story) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($result) && p('taskReg', ';')       && e('((Task) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($result) && p('bugReg', ';')        && e('((Bug) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($result) && p('costReg', ';')       && e('(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($result) && p('leftReg', ';')       && e('(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($result) && p('startTaskReg', ';')  && e('(Start) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($result) && p('effortTaskReg', ';') && e('(Effort) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($result) && p('finishTaskReg', ';') && e('(Finish) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($result) && p('resolveBugReg', ';') && e('(Fix) *((Bug) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r(count($result)) && p() && e('9'); //规则次数
