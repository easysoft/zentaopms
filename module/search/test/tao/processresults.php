#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processResults();
timeout=0
cid=18339

- 执行search模块的processResultsTest方法，参数是$results1, $objectList1, $words1 第0条的title属性 @<span class='text-danger'>test </span> <span class='text-danger'>bug </span> title
- 执行search模块的processResultsTest方法，参数是$results2, $objectList2, $words2 第0条的title属性 @~~
- 执行search模块的processResultsTest方法，参数是$results3, $objectList3, $words3 第0条的title属性 @normal <span class='text-danger'>project </span> title
- 执行search模块的processResultsTest方法，参数是$results4, $objectList4, $words4 第0条的title属性 @<span class='text-danger'>测试 </span>产品标题
- 执行search模块的processResultsTest方法，参数是$results5, $objectList5, $words5 第0条的summary属性 @This is <span class='text-danger'>document </span> content for testing summary generation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 准备测试数据
zenData('searchdict')->gen(0);

su('admin');

$search = new searchTest();

// 测试步骤1：测试关键词标记功能
$results1 = array();
$record1 = new stdClass();
$record1->objectType = 'bug';
$record1->objectID   = 1;
$record1->title      = 'test bug title';
$record1->content    = 'this is test content with bug keyword';
$results1[] = $record1;

$objectList1 = array();
$words1 = 'test bug';

r($search->processResultsTest($results1, $objectList1, $words1)) && p('0:title') && e("<span class='text-danger'>test </span> <span class='text-danger'>bug </span> title");

// 测试步骤2：测试空内容处理
$results2 = array();
$record2 = new stdClass();
$record2->objectType = 'story';
$record2->objectID   = 2;
$record2->title      = '';
$record2->content    = '';
$results2[] = $record2;

$objectList2 = array(
    'story' => array(
        2 => (object)array('id' => 2, 'type' => 'story', 'lib' => '')
    )
);
$words2 = 'empty';

r($search->processResultsTest($results2, $objectList2, $words2)) && p('0:title') && e('~~');

// 测试步骤3：测试HTML转义处理
$results3 = array();
$record3 = new stdClass();
$record3->objectType = 'project';
$record3->objectID   = 3;
$record3->title      = 'normal project title';
$record3->content    = 'project content with keywords';
$results3[] = $record3;

$objectList3 = array(
    'project' => array(
        3 => (object)array('id' => 3, 'model' => 'scrum')
    )
);
$words3 = 'project';

r($search->processResultsTest($results3, $objectList3, $words3)) && p('0:title') && e("normal <span class='text-danger'>project </span> title");

// 测试步骤4：测试中文关键词标记
$results4 = array();
$record4 = new stdClass();
$record4->objectType = 'product';
$record4->objectID   = 4;
$record4->title      = '测试产品标题';
$record4->content    = '这是一个测试产品的内容描述';
$results4[] = $record4;

$objectList4 = array();
$words4 = '测试';

r($search->processResultsTest($results4, $objectList4, $words4)) && p('0:title') && e("<span class='text-danger'>测试 </span>产品标题");

// 测试步骤5：测试摘要生成功能
$results5 = array();
$record5 = new stdClass();
$record5->objectType = 'doc';
$record5->objectID   = 5;
$record5->title      = 'document title';
$record5->content    = 'This is document content for testing summary generation';
$results5[] = $record5;

$objectList5 = array(
    'doc' => array(
        5 => (object)array('id' => 5, 'assetLib' => '', 'assetLibType' => '')
    )
);
$words5 = 'document';

r($search->processResultsTest($results5, $objectList5, $words5)) && p('0:summary') && e("This is <span class='text-danger'>document </span> content for testing summary generation");