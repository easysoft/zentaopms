#!/usr/bin/env php
<?php

/**

title=测试 hostModel::processTreemap();
timeout=0
cid=16761

- 步骤1:空数组输入 @0
- 步骤2:单个简单对象第0条的text属性 @简单主机
- 步骤3:带roomName的对象第0条的text属性 @主机名称
- 步骤4:带hostID的对象第0条的hostid属性 @123
- 步骤5:嵌套数组结构第0条的text属性 @北京机房1
- 步骤6:带children属性对象第0条的text属性 @子对象1
- 步骤7:HTML特殊字符转义第0条的text属性 @&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 加载语言包
global $app;
$app->loadLang('serverroom');

// 4. 创建测试实例
$hostTest = new hostModelTest();

// 5. 测试数据准备

// 步骤1:测试空数组
$emptyData = array();

// 步骤2:测试简单对象(只有name,会用作text)
$simpleObject = new stdClass();
$simpleObject->name = '简单主机';

// 步骤3:测试有roomName的对象(优先使用name作为text)
$roomObject = new stdClass();
$roomObject->roomName = '主机房名称';
$roomObject->name = '主机名称';

// 步骤4:测试有hostID的对象(会生成hostid属性)
$hostWithId = new stdClass();
$hostWithId->name = '带ID主机';
$hostWithId->hostID = 123;

// 步骤5:测试嵌套数组结构(会递归处理)
$arrayData = array(
    'beijing' => array(
        (object)array('roomName' => '北京机房1', 'name' => '北京主机1'),
        (object)array('roomName' => '北京机房2', 'name' => '北京主机2')
    )
);

// 步骤6:测试有children属性的对象(会递归处理children)
$objectWithChildren = new stdClass();
$objectWithChildren->name = '父级对象';
$objectWithChildren->children = array(
    (object)array('name' => '子对象1'),
    (object)array('name' => '子对象2')
);

// 步骤7:测试含有HTML特殊字符的文本(会被htmlspecialchars转义)
$htmlObject = new stdClass();
$htmlObject->name = '<script>alert("test")</script>';

// 6. 执行测试(必须包含至少5个测试步骤)
r(count($hostTest->processTreemapTest($emptyData))) && p() && e('0'); // 步骤1:空数组输入
r($hostTest->processTreemapTest(array($simpleObject))) && p('0:text') && e('简单主机'); // 步骤2:单个简单对象
r($hostTest->processTreemapTest(array($roomObject))) && p('0:text') && e('主机名称'); // 步骤3:带roomName的对象
r($hostTest->processTreemapTest(array($hostWithId))) && p('0:hostid') && e('123'); // 步骤4:带hostID的对象
r($hostTest->processTreemapTest($arrayData)) && p('0:text') && e('北京机房1'); // 步骤5:嵌套数组结构
r($hostTest->processTreemapTest(array($objectWithChildren))[0]['children']) && p('0:text') && e('子对象1'); // 步骤6:带children属性对象
r($hostTest->processTreemapTest(array($htmlObject))) && p('0:text') && e('&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;'); // 步骤7:HTML特殊字符转义