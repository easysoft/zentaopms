#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel->getActions();
timeout=0
cid=17279

- 查询最近两年的动态中序号为0的actionID @93
- 查询最近两年的动态中序号为1的actionID属性1 @62
- 查询最近两年的动态中序号为2的actionID属性2 @31
- 查询最近两年的动态中序号为3的actionID属性3 @92
- 查询最近两年的动态中序号为4的actionID属性4 @61

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$actions = zendata('action');
$actions->date->range('(-30D):1D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$actions->gen('100');
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

$my = new myModelTest();

$actions = $my->getActionsTest();
r($actions) && p('0') && e('93'); // 查询最近两年的动态中序号为0的actionID
r($actions) && p('1') && e('62'); // 查询最近两年的动态中序号为1的actionID
r($actions) && p('2') && e('31'); // 查询最近两年的动态中序号为2的actionID
r($actions) && p('3') && e('92'); // 查询最近两年的动态中序号为3的actionID
r($actions) && p('4') && e('61'); // 查询最近两年的动态中序号为4的actionID
