#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(2);
zenData('user')->gen(2);

su('admin');

/**

title=bugModel->createBugFromGitlabIssue ();
cid=15351

- 测试正常的创建来源于gitlab issue的bug的title属性title @问题1

- 测试正常的创建来源于gitlab issue的bug的execution属性execution @1

- 测试正常的创建来源于gitlab issue的bug的pri属性pri @3

- 测试正常的创建来源于gitlab issue的bug的severity属性severity @3

- 测试创建没有标题 来源于gitlab issue的异常bug第title条的0属性 @『Bug标题』不能为空。

- 测试短时间内重复创建来源于gitlab issue的bug属性task @0

*/

$executionID = 1;

$bug1 = new stdclass();
$bug1->title     = '问题1';
$bug1->execution = $executionID;

$bug2 = new stdclass();
$bug2->title     = '问题2';
$bug2->execution = $executionID;

$bug3 = new stdclass();
$bug3->title     = '问题3';
$bug3->execution = $executionID;

$bug4 = new stdclass();
$bug4->title     = '问题4';
$bug4->execution = $executionID;

$bug5 = new stdclass();
$bug5->title     = '';
$bug5->execution = $executionID;

$bug=new bugModelTest();
r($bug->createBugFromGitlabIssueTest($bug1, $executionID))    && p('title')     && e('问题1');                 // 测试正常的创建来源于gitlab issue的bug的title
r($bug->createBugFromGitlabIssueTest($bug2, $executionID))    && p('execution') && e('1');                     // 测试正常的创建来源于gitlab issue的bug的execution
r($bug->createBugFromGitlabIssueTest($bug3, $executionID))    && p('pri')       && e('3');                     // 测试正常的创建来源于gitlab issue的bug的pri
r($bug->createBugFromGitlabIssueTest($bug4, $executionID))    && p('severity')  && e('3');                     // 测试正常的创建来源于gitlab issue的bug的severity
r($bug->createBugFromGitlabIssueTest($bug5, $executionID))    && p('title:0')   && e('『Bug标题』不能为空。'); // 测试创建没有标题 来源于gitlab issue的异常bug
r($bug->createBugFromGitlabIssueTest($bug1, $executionID))    && p('task')      && e('0');                     // 测试短时间内重复创建来源于gitlab issue的bug

