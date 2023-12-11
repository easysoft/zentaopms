#!/usr/bin/env php
<?php
/**
title=测试 kanbanModel->updateRegion();
timeout=0
cid=1

- 正常更新看板区域的名称属性name @测试修改区域名称
- 更新看板区域名称为空第name条的0属性 @『区域名称』不能为空。
- 更新看板区域名称为空格字符第name条的0属性 @『区域名称』不能为空。
- 更新看板区域名称为超长字符串第name条的0属性 @『区域名称』长度应当不超过『255』，且大于『0』。
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(4);

$regionIDList = array('1', '2', '3', '4');
$nameList     = array('测试修改区域名称', '', '  ', '这是一个超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长超长的名称');

$kanban = new kanbanTest();

r($kanban->updateRegionTest($regionIDList[0], $nameList[0])) && p('name')   && e('测试修改区域名称');       // 正常更新看板区域的名称
r($kanban->updateRegionTest($regionIDList[1], $nameList[1])) && p('name:0') && e('『区域名称』不能为空。'); // 更新看板区域名称为空
r($kanban->updateRegionTest($regionIDList[2], $nameList[2])) && p('name:0') && e('『区域名称』不能为空。'); // 更新看板区域名称为空格字符
r($kanban->updateRegionTest($regionIDList[3], $nameList[3])) && p('name:0') && e('『区域名称』长度应当不超过『255』，且大于『0』。'); // 更新看板区域名称为超长字符串
