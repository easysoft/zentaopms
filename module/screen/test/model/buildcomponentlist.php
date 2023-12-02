#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

/**
title=测试 screenModel->buildComponentList();
cid=1
pid=1

测试带有空元素的情况下，生成的组件列表数量是否正确。 >> 1

*/

$screen = new screenTest();

$componentList = $screen->getAllComponent(array('type' => 'line'));

$list = array(current($componentList), '');

r(count($screen->buildComponentListTest($list))) && p('') && e('1'); //测试带有空元素的情况下，生成的组件列表数量是否正确。
