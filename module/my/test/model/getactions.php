#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel->getActions();
timeout=0
cid=1

- 正常查询action @96
- 正常查询action属性1 @64
- 正常查询action属性2 @32
- 正常查询action属性3 @95
- 正常查询action属性4 @63

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zendata('action')->gen('100');
zendata('doc')->gen('0');
zendata('api')->gen('0');
zendata('doclib')->gen('0');
zendata('project')->gen('0');
zendata('product')->gen('0');
zenData('user')->gen('1');

su('admin');

global $lang, $app;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$app->loadLang('action');

$my = new myTest();

$actions = $my->getActionsTest();
r($actions) && p('0') && e('96'); // 正常查询action
r($actions) && p('1') && e('64'); // 正常查询action
r($actions) && p('2') && e('32'); // 正常查询action
r($actions) && p('3') && e('95'); // 正常查询action
r($actions) && p('4') && e('63'); // 正常查询action
