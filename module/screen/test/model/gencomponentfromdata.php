#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 screenModel->genComponentFromData();
timeout=0
cid=1

- 测试类型是text,标题是Text1生成的组件
 - 属性title @Text1
 - 属性type @text
- 测试类型是text,标题是Text2生成的组件
 - 属性title @Text2
 - 属性type @text
- 测试类型是text,标题是Text3生成的组件
 - 属性title @Text3
 - 属性type @text
- 测试类型是waterpolo,标题是Waterpolo1生成的组件
 - 属性title @Waterpolo1
 - 属性type @waterpolo
- 测试类型是table,标题是Table1生成的组件
 - 属性title @Table1
 - 属性type @table

*/

global $tester;
$tester->loadModel('screen');
