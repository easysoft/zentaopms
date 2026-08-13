#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->processRules();
timeout=0
cid=18090

- 测试规则属性storyReg @((Story) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性taskReg @((Task) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性bugReg @((Bug) *((\#)[0-9]+((\,)[0-9]+)*))
- 测试规则属性costReg @(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性leftReg @(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性startTaskReg @(Start) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性effortTaskReg @(Effort) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性finishTaskReg @(Finish) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))
- 测试规则属性resolveBugReg @(Fix) *((Bug) *((\#)[0-9]+((\,)[0-9]+)*))
- 规则次数 @10

*/

$repoTest = new repoModelTest();
r($repoTest->processRulesTest()) && p('storyReg', ';')      && e('((Story) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($repoTest->processRulesTest()) && p('taskReg', ';')       && e('((Task) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($repoTest->processRulesTest()) && p('bugReg', ';')        && e('((Bug) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($repoTest->processRulesTest()) && p('costReg', ';')       && e('(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($repoTest->processRulesTest()) && p('leftReg', ';')       && e('(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($repoTest->processRulesTest()) && p('startTaskReg', ';')  && e('(Start) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($repoTest->processRulesTest()) && p('effortTaskReg', ';') && e('(Effort) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h)).*(Left) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($repoTest->processRulesTest()) && p('finishTaskReg', ';') && e('(Finish) *((Task) *((\#)[0-9]+((\,)[0-9]+)*)).*(Cost) *((\:)([0-9]+(\.?[0-9]+)?)(h))'); //测试规则
r($repoTest->processRulesTest()) && p('resolveBugReg', ';') && e('(Fix) *((Bug) *((\#)[0-9]+((\,)[0-9]+)*))'); //测试规则
r($repoTest->processRulesCountTest()) && p() && e('10'); //规则次数
