#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::getReleaseRelated();
timeout=0
cid=14953

- 步骤1：正常情况
 - 第0条的0属性 @1
 - 属性1 @11
- 步骤2：正常情况
 - 第0条的0属性 @2
 - 属性1 @18
- 步骤3：不存在ID
 - 第0条的0属性 @0
 - 属性1 @0
- 步骤4：边界值0
 - 第0条的0属性 @0
 - 属性1 @0
- 步骤5：负数ID
 - 第0条的0属性 @0
 - 属性1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('release')->loadYaml('release_getreleaserelated', false, 2)->gen(10);
zenData('build')->loadYaml('build_getreleaserelated', false, 2)->gen(10);

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->getReleaseRelated('release', 1))   && p('0:0;1') && e('1,11');  // 步骤1：正常情况
r($actionTest->getReleaseRelated('release', 8))   && p('0:0;1') && e('2,18');  // 步骤2：正常情况
r($actionTest->getReleaseRelated('release', 999)) && p('0:0;1') && e('0,0');   // 步骤3：不存在ID
r($actionTest->getReleaseRelated('release', 0))   && p('0:0;1') && e('0,0');   // 步骤4：边界值0
r($actionTest->getReleaseRelated('release', -1))  && p('0:0;1') && e('0,0');   // 步骤5：负数ID
