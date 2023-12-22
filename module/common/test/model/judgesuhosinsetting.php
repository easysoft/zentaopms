#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::judgeSuhosinSetting();
timeout=0
cid=1

- 查看是否超过控件限制 @0
- 查看是否超过控件限制 @0
- 查看是否超过控件限制 @0
- 查看是否超过控件限制 @1

*/

global $tester;

r($tester->loadModel('common')->judgeSuhosinSetting(10))     && p('') && e('0'); // 查看是否超过控件限制
r($tester->loadModel('common')->judgeSuhosinSetting(100))    && p('') && e('0'); // 查看是否超过控件限制
r($tester->loadModel('common')->judgeSuhosinSetting(1000))   && p('') && e('0'); // 查看是否超过控件限制
r($tester->loadModel('common')->judgeSuhosinSetting(10000))  && p('') && e('1'); // 查看是否超过控件限制