#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
$configTable = zdTable('config');
$configTable->id->range('1');
$configTable->owner->range('system');
$configTable->module->range('common');
$configTable->section->range('global');
$configTable->key->range('syncProduct');
$configTable->key->range('`{"feedback":{},"ticket":{}}`');
$configTable->gen(1);

zdTable('user')->gen(5);
su('admin');

/**

title=测试 docModel->fillDocsInTree();
cid=1
pid=1

测试填充libID 1 文档树第一个节点的文档 >> 目录1:2;子目录1:0;子目录2:0;
测试填充libID 2 文档树第一个节点的文档 >> 目录2:2;子目录3:0;子目录4:0;
测试填充libID 3 文档树第一个节点的文档 >> 目录3:2;子目录5:0;子目录6:0;
测试填充libID 11 文档树第一个节点的文档 >> 目录11:2;子目录21:0;子目录22:0;
测试填充libID 12 文档树第一个节点的文档 >> 目录12:2;子目录23:0;子目录24:0;

*/
$libID = array(1, 2, 3, 11, 12);

global $tester;
$fullTree1 = $tester->loadModel('tree')->getTreeStructure($libID[0], 'doc');
$fullTree2 = $tester->loadModel('tree')->getTreeStructure($libID[1], 'doc');
$fullTree3 = $tester->loadModel('tree')->getTreeStructure($libID[2], 'doc');
$fullTree4 = $tester->loadModel('tree')->getTreeStructure($libID[3], 'doc');
$fullTree5 = $tester->loadModel('tree')->getTreeStructure($libID[4], 'doc');

$doc = new docTest();

r($doc->fillDocsInTreeTest($fullTree1[0], $libID[0])) && p() && e('目录1:2;子目录1:0;子目录2:0;');    // 测试填充libID 1 文档树第一个节点的文档
r($doc->fillDocsInTreeTest($fullTree2[0], $libID[1])) && p() && e('目录2:2;子目录3:0;子目录4:0;');    // 测试填充libID 2 文档树第一个节点的文档
r($doc->fillDocsInTreeTest($fullTree3[0], $libID[2])) && p() && e('目录3:2;子目录5:0;子目录6:0;');    // 测试填充libID 3 文档树第一个节点的文档
r($doc->fillDocsInTreeTest($fullTree4[0], $libID[3])) && p() && e('目录11:2;子目录21:0;子目录22:0;'); // 测试填充libID 11 文档树第一个节点的文档
r($doc->fillDocsInTreeTest($fullTree5[0], $libID[4])) && p() && e('目录12:2;子目录23:0;子目录24:0;'); // 测试填充libID 12 文档树第一个节点的文档
