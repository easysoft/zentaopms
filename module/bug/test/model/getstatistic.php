#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

/**

title=bugModel->getStatistic();
cid=15395
pid=1

- 查询product id为0创建日期在今天的统计信息第openedDate条的num属性 @0
- 查询product id为1创建日期在今天的统计信息第openedDate条的num属性 @1
- 查询product id为1创建日期在今天的统计信息第resolvedDate条的num属性 @1
- 查询product id为1创建日期在今天的统计信息第closedDate条的num属性 @0
- 查询product id为11解决日期在今天的统计信息第resolvedDate条的num属性 @1
- 查询product id为31解决日期在今天的统计信息第resolvedDate条的num属性 @1
- 查询product id为61解决日期在今天的统计信息第resolvedDate条的num属性 @0
- 查询product id为91关闭日期在今天的统计信息第closedDate条的num属性 @0
- 查询product id为10001关闭日期在今天的统计信息第closedDate条的num属性 @0

*/

zenData('product')->gen(100);
zenData('bug')->loadYaml('bug_product')->gen(100);

$productIDList = array('1', '11', '31', '61', '91', '10001');

$bug=new bugTest();

r($bug->getStatisticTest())                  && p('openedDate:num')   && e('0'); // 查询product id为0创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('openedDate:num')   && e('1'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('resolvedDate:num') && e('1'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('closedDate:num')   && e('0'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[1])) && p('resolvedDate:num') && e('1'); // 查询product id为11解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[2])) && p('resolvedDate:num') && e('1'); // 查询product id为31解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[3])) && p('resolvedDate:num') && e('0'); // 查询product id为61解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[4])) && p('closedDate:num')   && e('0'); // 查询product id为91关闭日期在今天的统计信息
r($bug->getStatisticTest($productIDList[5])) && p('closedDate:num')   && e('0'); // 查询product id为10001关闭日期在今天的统计信息
