#!/usr/bin/env php
<?php

/**

title=测试 docModel->moveLib();
timeout=0
cid=16143

- 数据为空，libID=0时，检查返回结果 @0
- 数据为空，libID=1时，检查返回结果 @0
- 数据不为空，libID=0时，检查返回结果 @0
- 传入错误数据，检查返回结果 @1
- 未修改文档空间，检查返回结果 @1
- 传入正确数据，检查返回结果 @1
- 检查移动后的文档数据
 - 属性type @custom
 - 属性parent @7
- 传入移动到团队空间的数据，检查返回结果 @1
- 检查移动后的文档数据
 - 属性type @custom
 - 属性parent @7
- 传入移动到我的空间的数据，检查返回结果 @1
- 检查移动后的文档数据
 - 属性type @mine
 - 属性parent @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('doclib')->loadYaml('doclib')->gen(30);

global $tester;
$tester->loadModel('doc');

$mineLibID   = 11;
$customLibID = 6;

$emptyData  = new stdclass();
$customData = new stdclass();
$mineData   = new stdclass();
$errorData  = new stdclass();

$customData->space = 'custom.7';
$mineData->space   = 'mine.0';
$errorData->space  = 'project.1';

/* Empty module object. */
r((int)$tester->doc->moveLib(0, clone $emptyData))  && p() && e('0'); // 数据为空，libID=0时，检查返回结果
r((int)$tester->doc->moveLib(1, clone $emptyData))  && p() && e('0'); // 数据为空，libID=1时，检查返回结果
r((int)$tester->doc->moveLib(0, clone $customData)) && p() && e('0'); // 数据不为空，libID=0时，检查返回结果

/* Parent module object. */
r((int)$tester->doc->moveLib($mineLibID, clone $errorData))  && p() && e('1'); // 传入错误数据，检查返回结果
r((int)$tester->doc->moveLib($mineLibID, clone $mineData))   && p() && e('1'); // 未修改文档空间，检查返回结果
r((int)$tester->doc->moveLib($mineLibID, clone $customData)) && p() && e('1'); // 传入正确数据，检查返回结果
r($tester->doc->getLibByID($mineLibID)) && p('type,parent') && e('custom,7');  // 检查移动后的文档数据

r((int)$tester->doc->moveLib($customLibID, clone $customData)) && p() && e('1'); // 传入移动到团队空间的数据，检查返回结果
r($tester->doc->getLibByID($customLibID)) && p('type,parent') && e('custom,7');  // 检查移动后的文档数据
r((int)$tester->doc->moveLib($customLibID, clone $mineData)) && p() && e('1');   // 传入移动到我的空间的数据，检查返回结果
r($tester->doc->getLibByID($customLibID)) && p('type,parent') && e('mine,0');    // 检查移动后的文档数据
