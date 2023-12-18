#!/usr/bin/env php
<?php
/**

title=测试 stageModel->setMenu();
cid=1

- 测试传入错误类型 @0
- 设置瀑布模型导航 @stage-settype,stage-plusbrowse
- 设置瀑布模型导航 @stage-settype,stage-plusbrowse
- 设置融合瀑布模型导航 @stage-settype,stage-browse,stage-browse,stage-browseplus
- 设置融合瀑布模型导航 @stage-settype,stage-browse,stage-browse,stage-browseplus

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';

zdTable('user')->gen(5);

$types  = array('scrum', 'waterfall', 'waterfallplus');
$method = array('browse', 'browseplus');

$stageTester = new stageTest();
r($stageTester->setMenuType($types[0], $method[0])) && p()        && e('0');                                                        // 测试传入错误类型
r($stageTester->setMenuType($types[1], $method[0])) && p('', ';') && e('stage-settype,stage-plusbrowse');                           // 设置瀑布模型导航
r($stageTester->setMenuType($types[1], $method[1])) && p('', ';') && e('stage-settype,stage-plusbrowse');                           // 设置瀑布模型导航
r($stageTester->setMenuType($types[2], $method[0])) && p('', ';') && e('stage-settype,stage-browse,stage-browse,stage-browseplus'); // 设置融合瀑布模型导航
r($stageTester->setMenuType($types[2], $method[1])) && p('', ';') && e('stage-settype,stage-browse,stage-browse,stage-browseplus'); // 设置融合瀑布模型导航
