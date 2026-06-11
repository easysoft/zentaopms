#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_provider')->loadYaml('provider', false, 2)->gen(5);

su('admin');

/**

title=测试 providerModel::getPairs();
timeout=0
cid=0

- 步骤1：不传类型时返回全部未删除服务数量 @4
- 步骤2：不传类型时按 ID 倒序返回服务名称
 - 属性5 @Echo
 - 属性3 @Charlie
 - 属性2 @Bravo
 - 属性1 @Alpha
- 步骤3：不传类型时过滤已删除服务
 - 属性4 @~~
- 步骤4：按 GitLab 类型筛选时只返回 Alpha
 - 属性1 @Alpha
- 步骤5：按已删除的 Gogs 类型筛选时返回空结果数量 @0
- 步骤6：按 Jenkins 类型筛选时只返回 Echo
 - 属性6 @Echo

*/

$providerTester = new providerModelTest();
$allPairs       = $providerTester->getPairsTest('');
$gitlabPairs    = $providerTester->getPairsTest('GitLab');
$jenkinsPairs   = $providerTester->getPairsTest('Jenkins');
$gogsPairs      = $providerTester->getPairsTest('Gogs');

r(count($allPairs)) && p()          && e('4');                        // 步骤1：不传类型时返回全部未删除服务数量
r($allPairs)        && p('5,3,2,1') && e('Echo,Charlie,Bravo,Alpha'); // 步骤2：不传类型时按 ID 倒序返回服务名称
r($allPairs)        && p('4')       && e('~~');                       // 步骤3：不传类型时过滤已删除服务
r($gitlabPairs)     && p('1')       && e('Alpha');                    // 步骤4：按 GitLab 类型筛选时只返回 Alpha
r(count($gogsPairs))&& p()          && e('0');                        // 步骤5：按已删除的 Gogs 类型筛选时返回空结果数量
r($jenkinsPairs)    && p('5')       && e('Echo');                     // 步骤6：按 Jenkins 类型筛选时只返回 Echo
