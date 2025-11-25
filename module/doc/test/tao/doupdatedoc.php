#!/usr/bin/env php
<?php

/**

title=测试 docModel->doUpdateDoc();
cid=16167

- 修改文档库ID属性lib @2
- 修改所属目录属性module @3
- 修改访问控制属性acl @open
- 修改可访问组属性groups @4
- 修改可访问人员属性users @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(10);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->doUpdateDocTest(1, array('lib' => 2)))          && p('lib')    && e('2');     // 修改文档库ID
r($docTester->doUpdateDocTest(2, array('module' => 3)))       && p('module') && e('3');     // 修改所属目录
r($docTester->doUpdateDocTest(3, array('acl' => 'open')))     && p('acl')    && e('open');  // 修改访问控制
r($docTester->doUpdateDocTest(4, array('groups' => 4)))       && p('groups') && e('4');     // 修改可访问组
r($docTester->doUpdateDocTest(5, array('users' => 'admin')))  && p('users')  && e('admin'); // 修改可访问人员
