#!/usr/bin/env php
<?php

/**

title=测试 jobZen::reponseAfterCreateEdit();
timeout=0
cid=0

- 测试无错误且repoID为0时,返回成功结果
 - 属性result @success
 - 属性message @保存成功
- 测试无错误且repoID为100时,返回成功结果并跳转到指定repo
 - 属性result @success
 - 属性message @保存成功
- 测试GitLab引擎有server错误时,转换为repo错误属性result @fail
- 测试GitLab引擎同时有server和pipeline错误时,server和pipeline错误被移除属性result @fail
- 测试Jenkins引擎有server错误时,转换为jkServer错误属性result @fail
- 测试Jenkins引擎有pipeline错误时,转换为jkTask错误属性result @fail
- 测试Jenkins引擎同时有server和pipeline错误时,错误字段正确转换属性result @fail
- 测试无错误且使用post的repo参数时,load链接使用post的repo值
 - 属性result @success
 - 属性message @保存成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$jobTest = new jobZenTest();

r($jobTest->reponseAfterCreateEditTest(0, array(), '', 0)) && p('result,message') && e('success,保存成功'); // 测试无错误且repoID为0时,返回成功结果
r($jobTest->reponseAfterCreateEditTest(100, array(), '', 0)) && p('result,message') && e('success,保存成功'); // 测试无错误且repoID为100时,返回成功结果并跳转到指定repo
r($jobTest->reponseAfterCreateEditTest(0, array('server' => array('error1')), 'gitlab', 0)) && p('result') && e('fail'); // 测试GitLab引擎有server错误时,转换为repo错误
r($jobTest->reponseAfterCreateEditTest(0, array('server' => array('error1'), 'pipeline' => array('error2')), 'gitlab', 0)) && p('result') && e('fail'); // 测试GitLab引擎同时有server和pipeline错误时,server和pipeline错误被移除
r($jobTest->reponseAfterCreateEditTest(0, array('server' => array('error1')), 'jenkins', 0)) && p('result') && e('fail'); // 测试Jenkins引擎有server错误时,转换为jkServer错误
r($jobTest->reponseAfterCreateEditTest(0, array('pipeline' => array('error2')), 'jenkins', 0)) && p('result') && e('fail'); // 测试Jenkins引擎有pipeline错误时,转换为jkTask错误
r($jobTest->reponseAfterCreateEditTest(0, array('server' => array('error1'), 'pipeline' => array('error2')), 'jenkins', 0)) && p('result') && e('fail'); // 测试Jenkins引擎同时有server和pipeline错误时,错误字段正确转换
r($jobTest->reponseAfterCreateEditTest(0, array(), '', 50)) && p('result,message') && e('success,保存成功'); // 测试无错误且使用post的repo参数时,load链接使用post的repo值