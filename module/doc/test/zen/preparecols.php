#!/usr/bin/env php
<?php

/**

title=测试 docZen::prepareCols();
timeout=0
cid=0

- 执行docTest模块的prepareColsTest方法，参数是$cols1 
 - 第id条的name属性 @id
 - 第id条的id:sortType属性 @~~
 - 第name条的name属性 @name
 - 第name条的name:sortType属性 @~~
 - 第status条的name属性 @status
 - 第status条的status:sortType属性 @~~
 - 属性actions @~~
- 执行docTest模块的prepareColsTest方法，参数是$cols2 
 - 第title条的name属性 @title
 - 第title条的title:sortType属性 @~~
 - 第author条的name属性 @author
 - 第author条的author:sortType属性 @~~
- 执行docTest模块的prepareColsTest方法，参数是$cols3 
 - 第content条的name属性 @content
 - 第content条的content:link属性 @~~
 - 第category条的name属性 @category
 - 第category条的category:nestedToggle属性 @~~
- 执行docTest模块的prepareColsTest方法，参数是$cols4  @0
- 执行docTest模块的prepareColsTest方法，参数是$cols5 
 - 第doc_id条的name属性 @doc_id
 - 第doc_id条的doc_id:sortType属性 @~~
 - 第doc_id条的doc_id:link属性 @~~
 - 第doc_title条的name属性 @doc_title
 - 第doc_title条的doc_title:sortType属性 @~~
 - 第doc_title条的doc_title:nestedToggle属性 @~~
 - 第created_by条的name属性 @created_by
 - 第created_by条的created_by:sortType属性 @~~
 - 属性actions @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

// 步骤1：测试包含actions列的完整列配置
$cols1 = array(
    'id' => array('title' => 'ID', 'width' => 80, 'sortType' => true),
    'name' => array('title' => '名称', 'width' => 200, 'link' => 'view'),
    'status' => array('title' => '状态', 'width' => 100, 'nestedToggle' => true),
    'actions' => array('title' => '操作', 'width' => 120)
);
r($docTest->prepareColsTest($cols1)) && p('id:name,id:sortType;name:name,name:sortType;status:name,status:sortType;actions') && e('id,~~;name,~~;status,~~;~~');

// 步骤2：测试不包含actions列的基本列配置
$cols2 = array(
    'title' => array('title' => '标题', 'width' => 300),
    'author' => array('title' => '作者', 'width' => 100)
);
r($docTest->prepareColsTest($cols2)) && p('title:name,title:sortType;author:name,author:sortType') && e('title,~~;author,~~');

// 步骤3：测试包含link和nestedToggle属性的列配置
$cols3 = array(
    'content' => array('title' => '内容', 'width' => 400, 'link' => 'detail'),
    'category' => array('title' => '分类', 'width' => 150, 'nestedToggle' => true)
);
r($docTest->prepareColsTest($cols3)) && p('content:name,content:link;category:name,category:nestedToggle') && e('content,~~;category,~~');

// 步骤4：测试空的列配置
$cols4 = array();
r($docTest->prepareColsTest($cols4)) && p() && e('0');

// 步骤5：测试复杂的列配置混合场景
$cols5 = array(
    'doc_id' => array('title' => '文档ID', 'width' => 80, 'sortType' => 'desc', 'link' => 'view'),
    'doc_title' => array('title' => '文档标题', 'width' => 250, 'nestedToggle' => false),
    'created_by' => array('title' => '创建者', 'width' => 120),
    'actions' => array('title' => '操作', 'width' => 150, 'sortType' => false)
);
r($docTest->prepareColsTest($cols5)) && p('doc_id:name,doc_id:sortType,doc_id:link;doc_title:name,doc_title:sortType,doc_title:nestedToggle;created_by:name,created_by:sortType;actions') && e('doc_id,~~,~~;doc_title,~~,~~;created_by,~~;~~');