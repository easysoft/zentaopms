#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

zdTable('config')->gen(20);

/**

title=测试 settingModel->updateItem.php();
cid=1
pid=1

*/

$setting = new settingTest();

r($setting->updateItemTest('key2', 'value20'))                              && p()        && e('0');                              //测试更新错误数据
r($setting->updateItemTest('system.story.section1.key1', 'value10'))        && p('value') && e('value10');                        //测试更新已存在数据
r($setting->updateItemTest('system.story.section1.key100@rnd', 'value100')) && p('vision,key,value') && e('rnd,key100,value100'); //测试更新不存在数据
