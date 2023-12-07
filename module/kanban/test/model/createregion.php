#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(1);

/**

title=测试 kanbanModel->createRegion();
timeout=0
cid=1

- 创建默认区域
 - 属性name @默认区域
 - 属性space @1
 - 属性kanban @100001
- 创建自定义区域1
 - 属性name @新增的区域1
 - 属性space @1
 - 属性kanban @100001
- 创建复制样式区域2
 - 属性name @新增的区域2
 - 属性space @1
 - 属性kanban @100002
- 创建自定义区域3
 - 属性name @新增的区域3
 - 属性space @1
 - 属性kanban @0
- 创建复制样式区域4
 - 属性name @新增的区域4
 - 属性space @1
 - 属性kanban @0
- 创建没有名称的区域第name条的0属性 @『区域名称』不能为空。
- 创建重名自定义区域1第name条的0属性 @『区域名称』已经有『新增的区域1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/
$kanban1 = new stdclass();
$kanban1->id    = '100001';
$kanban1->space = '1';

$copyRegionID = 2;

$region1 = new stdclass;
$region1->name   = '新增的区域1';
$region1->space  = 1;
$region1->kanban = 100001;

$region2 = new stdclass;
$region2->name   = '新增的区域2';
$region2->space  = 1;
$region2->kanban = 100002;

$region3 = new stdclass;
$region3->name  = '新增的区域3';
$region3->space = 1;

$region4 = new stdclass;
$region4->name  = '新增的区域4';
$region4->space = 1;

$region5 = new stdclass;
$region5->name  = '';
$region5->space = 1;

$kanban = new kanbanTest();

r($kanban->createRegionTest($kanban1))                          && p('name,space,kanban') && e('默认区域,1,100001');      // 创建默认区域
r($kanban->createRegionTest($kanban1, $region1))                && p('name,space,kanban') && e('新增的区域1,1,100001');   // 创建自定义区域1
r($kanban->createRegionTest($kanban1, $region2, $copyRegionID)) && p('name,space,kanban') && e('新增的区域2,1,100002');        // 创建复制样式区域2
r($kanban->createRegionTest($kanban1, $region3))                && p('name,space,kanban') && e('新增的区域3,1,0');   // 创建自定义区域3
r($kanban->createRegionTest($kanban1, $region4, $copyRegionID)) && p('name,space,kanban') && e('新增的区域4,1,0');        // 创建复制样式区域4
r($kanban->createRegionTest($kanban1, $region5))                && p('name:0')            && e('『区域名称』不能为空。'); // 创建没有名称的区域
r($kanban->createRegionTest($kanban1, $region1))                && p('name:0')            && e('『区域名称』已经有『新增的区域1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 创建重名自定义区域1