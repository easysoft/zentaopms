#!/usr/bin/env php
<?php

/**

title=测试 devModel::getLinkTitle();
timeout=0
cid=16004

- 执行devTest模块的getLinkTitleTest方法，参数是$arrayMenus
 - 属性product @产品
 - 属性project @项目
 - 属性task @任务
- 执行devTest模块的getLinkTitleTest方法，参数是$stringMenus
 - 属性my @地盘
 - 属性qa @测试
 - 属性doc @文档
- 执行devTest模块的getLinkTitleTest方法，参数是$emptyMenus  @0
- 执行devTest模块的getLinkTitleTest方法，参数是$htmlMenus
 - 属性admin @后台
 - 属性help @帮助
- 执行devTest模块的getLinkTitleTest方法，参数是$invalidMenus 属性valid @有效

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTest = new devModelTest();

// 步骤1：测试数组格式菜单（包含link键）
$arrayMenus = array(
    'product' => array('link' => '产品|product|all|'),
    'project' => array('link' => '项目|project|browse|'),
    'task'    => array('link' => '任务|task|browse|')
);
r($devTest->getLinkTitleTest($arrayMenus)) && p('product,project,task') && e('产品,项目,任务');

// 步骤2：测试字符串格式菜单
$stringMenus = array(
    'my'      => '地盘|my|index|',
    'qa'      => '测试|qa|index|',
    'doc'     => '文档|doc|index|'
);
r($devTest->getLinkTitleTest($stringMenus)) && p('my,qa,doc') && e('地盘,测试,文档');

// 步骤3：测试空菜单数组
$emptyMenus = array();
r($devTest->getLinkTitleTest($emptyMenus)) && p() && e('0');

// 步骤4：测试包含HTML标签的菜单链接
$htmlMenus = array(
    'admin' => '<i class="icon icon-cog"></i> 后台|admin|index|',
    'help'  => '<span class="text-primary">帮助</span>|help|browse|'
);
r($devTest->getLinkTitleTest($htmlMenus)) && p('admin,help') && e('后台,帮助');

// 步骤5：测试没有分隔符的菜单项（应该被跳过）
$invalidMenus = array(
    'invalid1' => array('link' => '无效链接'),
    'invalid2' => '无效链接2',
    'valid'    => '有效|valid|index|'
);
r($devTest->getLinkTitleTest($invalidMenus)) && p('valid') && e('有效');