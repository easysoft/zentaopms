#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('scene')->loadYaml('treescene')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getScenesName();
cid=1

- 获取场景 1 2 3 的名称
 - 属性1 @/这个是测试场景1
 - 属性2 @/这个是测试场景1/这个是测试场景2
 - 属性3 @/这个是测试场景3
- 获取场景 9 10 14 的名称
 - 属性9 @/这个是测试场景9
 - 属性10 @/这个是测试场景10
 - 属性14 @/这个是测试场景11/这个是测试场景12/这个是测试场景13/这个是测试场景14
- 获取场景 15 16 17 的名称
 - 属性15 @/这个是测试场景11/这个是测试场景15
 - 属性16 @/这个是测试场景16
 - 属性17 @/这个是测试场景16/这个是测试场景17
- 获取场景 18 19 20 的名称
 - 属性18 @/这个是测试场景18
 - 属性19 @/这个是测试场景18/这个是测试场景19
 - 属性20 @/这个是测试场景18/这个是测试场景19/这个是测试场景20
- 获取场景 1 2 3 的 全 名称
 - 属性1 @/这个是测试场景1
 - 属性2 @/这个是测试场景1/这个是测试场景2
 - 属性3 @/这个是测试场景3
- 获取场景 9 10 14 的 全 名称
 - 属性9 @/这个是测试场景9
 - 属性10 @/这个是测试场景10
 - 属性14 @/这个是测试场景11/这个是测试场景12/这个是测试场景13/这个是测试场景14
- 获取场景 15 16 17 的 全 名称
 - 属性15 @/这个是测试场景11/这个是测试场景15
 - 属性16 @/这个是测试场景16
 - 属性17 @/这个是测试场景16/这个是测试场景17
- 获取场景 18 19 20 的 全 名称
 - 属性18 @/这个是测试场景18
 - 属性19 @/这个是测试场景18/这个是测试场景19
 - 属性20 @/这个是测试场景18/这个是测试场景19/这个是测试场景20

*/

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();

$sceneList = array(array(1,2,3), array(9,10,14), array(15,16,17), array(18,19,20));
$fullPath  = array(true, false);

$testcase = new testcaseTest();

r($testcase->getScenesNameTest($sceneList[0])) && p('1,2,3')    && e('/这个是测试场景1,/这个是测试场景1/这个是测试场景2,/这个是测试场景3');                                       // 获取场景 1 2 3 的名称
r($testcase->getScenesNameTest($sceneList[1])) && p('9,10,14')  && e('/这个是测试场景9,/这个是测试场景10,/这个是测试场景11/这个是测试场景12/这个是测试场景13/这个是测试场景14');  // 获取场景 9 10 14 的名称
r($testcase->getScenesNameTest($sceneList[2])) && p('15,16,17') && e('/这个是测试场景11/这个是测试场景15,/这个是测试场景16,/这个是测试场景16/这个是测试场景17');                  // 获取场景 15 16 17 的名称
r($testcase->getScenesNameTest($sceneList[3])) && p('18,19,20') && e('/这个是测试场景18,/这个是测试场景18/这个是测试场景19,/这个是测试场景18/这个是测试场景19/这个是测试场景20'); // 获取场景 18 19 20 的名称

r($testcase->getScenesNameTest($sceneList[0], $fullPath[0])) && p('1,2,3')    && e('/这个是测试场景1,/这个是测试场景1/这个是测试场景2,/这个是测试场景3');                                       // 获取场景 1 2 3 的 全 名称
r($testcase->getScenesNameTest($sceneList[1], $fullPath[0])) && p('9,10,14')  && e('/这个是测试场景9,/这个是测试场景10,/这个是测试场景11/这个是测试场景12/这个是测试场景13/这个是测试场景14');  // 获取场景 9 10 14 的 全 名称
r($testcase->getScenesNameTest($sceneList[2], $fullPath[0])) && p('15,16,17') && e('/这个是测试场景11/这个是测试场景15,/这个是测试场景16,/这个是测试场景16/这个是测试场景17');                  // 获取场景 15 16 17 的 全 名称
r($testcase->getScenesNameTest($sceneList[3], $fullPath[0])) && p('18,19,20') && e('/这个是测试场景18,/这个是测试场景18/这个是测试场景19,/这个是测试场景18/这个是测试场景19/这个是测试场景20'); // 获取场景 18 19 20 的 全 名称

r($testcase->getScenesNameTest($sceneList[0], $fullPath[1])) && p('1,2,3')    && e('这个是测试场景1,这个是测试场景2,这个是测试场景3'); // 获取场景 1 2 3 的 非全 名称
r($testcase->getScenesNameTest($sceneList[1], $fullPath[1])) && p('9,10,14')  && e('这个是测试场景9,这个是测试场景10,这个是测试场景14'); // 获取场景 9 10 14 的 非全 名称
r($testcase->getScenesNameTest($sceneList[2], $fullPath[1])) && p('15,16,17') && e('这个是测试场景15,这个是测试场景16,这个是测试场景17'); // 获取场景 15 16 17 的 非全 名称
r($testcase->getScenesNameTest($sceneList[3], $fullPath[1])) && p('18,19,20') && e('这个是测试场景18,这个是测试场景19,这个是测试场景20'); // 获取场景 18 19 20 的 非全 名称
