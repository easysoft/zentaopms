#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getStatistic();
cid=1
pid=1

查询product id为0创建日期在今天的统计信息 >> 0
查询product id为1创建日期在今天的统计信息 >> 0
查询product id为1创建日期在今天的统计信息 >> 0
查询product id为1创建日期在今天的统计信息 >> 0
查询product id为11解决日期在今天的统计信息 >> 1
查询product id为31解决日期在今天的统计信息 >> 0
查询product id为61解决日期在今天的统计信息 >> 0
查询product id为91关闭日期在今天的统计信息 >> 0
查询product id为10001关闭日期在今天的统计信息 >> 0

*/

$productIDList = array('1', '11', '31', '61', '91', '10001');

$bug=new bugTest();

r($bug->getStatisticTest())                  && p('openedDate:num')   && e('0'); // 查询product id为0创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('openedDate:num')   && e('0'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('resolvedDate:num') && e('0'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[0])) && p('closedDate:num')   && e('0'); // 查询product id为1创建日期在今天的统计信息
r($bug->getStatisticTest($productIDList[1])) && p('openedDate:num')   && e('1'); // 查询product id为11解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[2])) && p('openedDate:num')   && e('0'); // 查询product id为31解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[3])) && p('openedDate:num')   && e('0'); // 查询product id为61解决日期在今天的统计信息
r($bug->getStatisticTest($productIDList[4])) && p('openedDate:num')   && e('0'); // 查询product id为91关闭日期在今天的统计信息
r($bug->getStatisticTest($productIDList[5])) && p('openedDate:num')   && e('0'); // 查询product id为10001关闭日期在今天的统计信息