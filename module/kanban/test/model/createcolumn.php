#!/usr/bin/env php
<?php
/**

title=创建没有在制品限制的看板列 >> 测试创建不限制看板列,-1,
timeout=0
cid=333

- 创建没有在制品限制的看板列
 - 属性name @测试创建不限制看板列
 - 属性limit @-1
 - 属性color @#333
 - 属性parent @0
 - 属性group @1
- 创建没有在制品限制的看板列
 - 属性name @测试创建不限制子看板列
 - 属性limit @-1
 - 属性color @#333
 - 属性parent @1
 - 属性group @1
- 创建有在制品限制的看板列
 - 属性name @测试创建限制父看板列
 - 属性limit @111
 - 属性color @#333
 - 属性parent @-1
 - 属性group @1
- 创建有在制品限制的子看板列第limit条的0属性 @父列的在制品数量不能小于子列在制品数量之和
- 创建没有名称的看板列第name条的0属性 @『看板列名称』不能为空。
- 创建没有填写在制品限制的看板列属性limit @在制品数量必须是正整数。
- 创建在制品数量为0的看板列属性limit @在制品数量必须是正整数。
- 创建在制品数量超出限制的子看板列第limit条的0属性 @父列的在制品数量不能小于子列在制品数量之和

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(1);

$column1 = new stdclass();
$column1->name    = '测试创建不限制看板列';
$column1->limit   = '-1';
$column1->noLimit = '-1';
$column1->color   = '#333';
$column1->group   = '1';
$column1->parent  = '0';

$column2 = new stdclass();
$column2->name    = '测试创建不限制子看板列';
$column2->limit   = '-1';
$column2->noLimit = '-1';
$column2->color   = '#333';
$column2->group   = '1';
$column2->parent  = '1';

$column3 = new stdclass();
$column3->name     = '测试创建限制父看板列';
$column3->limit    = '111';
$column3->color    = '#333';
$column3->group    = '1';
$column3->parent   = '-1';

$column4 = new stdclass();
$column4->name     = '测试创建限制子看板列';
$column4->limit    = '11';
$column4->color    = '#333';
$column4->group    = '1';
$column4->parent   = '5263';

$column5 = new stdclass();
$column5->name    = '';
$column5->limit   = '-1';
$column5->noLimit = '-1';
$column5->color   = '#333';
$column5->group   = '1';
$column5->parent  = '0';

$column6 = new stdclass();
$column6->name    = '测试创建不填写在制品数量的看板列';
$column6->limit    = '';
$column6->color   = '#333';
$column6->group   = '1';
$column6->parent  = '0';

$column7 = new stdclass();
$column7->name    = '测试创建在制品数量为0的看板列';
$column7->limit    = 0;
$column7->color   = '#333';
$column7->group   = '1';
$column7->parent  = '0';

$column8 = new stdclass();
$column8->name    = '测试创建在制品数量超出限制的子看板列';
$column8->limit    = '111';
$column8->color   = '#333';
$column8->group   = '1';
$column8->parent  = '5263';

$kanban = new kanbanTest();

r($kanban->createColumnTest(1, $column1)) && p('name,limit,parent,group') && e('测试创建不限制看板列,-1,0,1');                // 创建没有在制品限制的看板列
r($kanban->createColumnTest(1, $column2)) && p('name,limit,parent,group') && e('测试创建不限制子看板列,-1,1,1');              // 创建没有在制品限制的看板列
r($kanban->createColumnTest(1, $column3)) && p('name,limit,parent,group') && e('测试创建限制父看板列,111,-1,1');              // 创建有在制品限制的看板列
r($kanban->createColumnTest(1, $column4)) && p('limit:0')                 && e('父列的在制品数量不能小于子列在制品数量之和'); // 创建有在制品限制的子看板列
r($kanban->createColumnTest(1, $column5)) && p('name:0')                  && e('『看板列名称』不能为空。');                   // 创建没有名称的看板列
r($kanban->createColumnTest(1, $column6)) && p('limit')                   && e('在制品数量必须是正整数。');                   // 创建没有填写在制品限制的看板列
r($kanban->createColumnTest(1, $column7)) && p('limit')                   && e('在制品数量必须是正整数。');                   // 创建在制品数量为0的看板列
r($kanban->createColumnTest(1, $column8)) && p('limit:0')                 && e('父列的在制品数量不能小于子列在制品数量之和'); // 创建在制品数量超出限制的子看板列
