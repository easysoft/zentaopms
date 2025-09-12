#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDocRecord();
timeout=0
cid=0

- 步骤1：普通文档记录处理属性url @/home/z/rzto/module/search/test/tao/processdocrecord.php?m=doc&f=view&id=1
- 步骤2：资产库实践文档记录处理属性url @/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=practiceView&id=2
- 步骤3：资产库组件文档记录处理属性url @/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=componentView&id=3
- 步骤4：空资产库类型文档记录处理属性url @/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=componentView&id=4
- 步骤5：单个文档对象列表测试属性url @/home/z/rzto/module/search/test/tao/processdocrecord.php?m=doc&f=view&id=1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doc');
$table->id->range('1-10');
$table->title->range('测试文档1,测试文档2,测试文档3,测试文档4,测试文档5,测试文档6,测试文档7,测试文档8,测试文档9,测试文档10');
$table->assetLib->range('0{5},1{3},2{2}');
$table->assetLibType->range('``,``,``,``,``,practice{2},component{1},component{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 准备测试数据
// 准备普通文档记录（无资产库）
$record1 = new stdClass();
$record1->objectID = 1;
$record1->objectType = 'doc';
$record1->title = '测试文档1';
$record1->content = '这是测试内容';

// 准备资产库文档对象列表
$objectList = array(
    'doc' => array(
        1 => (object)array('id' => 1, 'assetLib' => 0, 'assetLibType' => ''),
        2 => (object)array('id' => 2, 'assetLib' => 1, 'assetLibType' => 'practice'),
        3 => (object)array('id' => 3, 'assetLib' => 2, 'assetLibType' => 'component'),
        4 => (object)array('id' => 4, 'assetLib' => 3, 'assetLibType' => ''),
    )
);

// 准备资产库实践文档记录
$record2 = new stdClass();
$record2->objectID = 2;
$record2->objectType = 'doc';
$record2->title = '实践文档';
$record2->content = '实践内容';

// 准备资产库组件文档记录
$record3 = new stdClass();
$record3->objectID = 3;
$record3->objectType = 'doc';
$record3->title = '组件文档';
$record3->content = '组件内容';

// 准备空资产库类型文档记录
$record4 = new stdClass();
$record4->objectID = 4;
$record4->objectType = 'doc';
$record4->title = '空类型文档';
$record4->content = '空类型内容';

// 准备另一个普通文档记录
$record5 = new stdClass();
$record5->objectID = 1;
$record5->objectType = 'doc';
$record5->title = '重复测试文档';
$record5->content = '重复测试内容';

// 准备单个文档对象列表
$singleObjectList = array(
    'doc' => array(
        1 => (object)array('id' => 1, 'assetLib' => 0, 'assetLibType' => ''),
    )
);

// 🔴 强制要求：必须包含至少5个测试步骤
r($searchTest->processDocRecordTest($record1, $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processdocrecord.php?m=doc&f=view&id=1'); // 步骤1：普通文档记录处理
r($searchTest->processDocRecordTest($record2, $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=practiceView&id=2'); // 步骤2：资产库实践文档记录处理
r($searchTest->processDocRecordTest($record3, $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=componentView&id=3'); // 步骤3：资产库组件文档记录处理
r($searchTest->processDocRecordTest($record4, $objectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processdocrecord.php?m=assetlib&f=componentView&id=4'); // 步骤4：空资产库类型文档记录处理
r($searchTest->processDocRecordTest($record5, $singleObjectList)) && p('url') && e('/home/z/rzto/module/search/test/tao/processdocrecord.php?m=doc&f=view&id=1'); // 步骤5：单个文档对象列表测试