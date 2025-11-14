#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createGroup();
timeout=0
cid=15840

- 执行convertTest模块的createGroupTest方法，参数是'project', '测试项目组', array  @true
- 执行convertTest模块的createGroupTest方法，参数是'product', '测试产品组', array  @true
- 执行convertTest模块的createGroupTest方法，参数是'project', '', array  @true
- 执行convertTest模块的createGroupTest方法，参数是'project', str_repeat  @true
- 执行convertTest模块的createGroupTest方法，参数是'invalid', '测试组', array  @invalid type
- 执行convertTest模块的createGroupTest方法，参数是'project', '带对象列表', array  @true
- 执行convertTest模块的createGroupTest方法，参数是'product', '产品关系测试', 'invalid_array', 6, 2, array  @invalid objectList

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->createGroupTest('project', '测试项目组', array('task', 'story'), 1, 1, array(), array(), array())) && p() && e('true');
r($convertTest->createGroupTest('product', '测试产品组', array('bug', 'build'), 2, 2, array(2 => 1), array(), array())) && p() && e('true');
r($convertTest->createGroupTest('project', '', array(), 3, 3, array(), array(), array())) && p() && e('true');
r($convertTest->createGroupTest('project', str_repeat('长名称测试', 20), array('task'), 4, 4, array(), array(), array())) && p() && e('true');
r($convertTest->createGroupTest('invalid', '测试组', array(), 0, 0, array(), array(), array())) && p() && e('invalid type');
r($convertTest->createGroupTest('project', '带对象列表', array('task', 'story', 'bug'), 5, 1, array(), array(5 => array('task' => array())), array())) && p() && e('true');
r($convertTest->createGroupTest('product', '产品关系测试', 'invalid_array', 6, 2, array(6 => 3), array(), array())) && p() && e('invalid objectList');