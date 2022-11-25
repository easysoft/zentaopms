#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getBeginEnd4CFD();
cid=1
pid=1

ID 161 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1
ID 162 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1
ID 163 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1
ID 171 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1
ID 181 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1
ID 191 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天 >> 1,1

*/

$executionIDList = array(161, 162, 163, 171, 181, 191);

$execution = new executionTest();
r($execution->getBeginEnd4CFDTest($executionIDList[0])) && p('0,1') && e('1,1'); // ID 161 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
r($execution->getBeginEnd4CFDTest($executionIDList[1])) && p('0,1') && e('1,1'); // ID 162 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
r($execution->getBeginEnd4CFDTest($executionIDList[2])) && p('0,1') && e('1,1'); // ID 163 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
r($execution->getBeginEnd4CFDTest($executionIDList[3])) && p('0,1') && e('1,1'); // ID 171 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
r($execution->getBeginEnd4CFDTest($executionIDList[4])) && p('0,1') && e('1,1'); // ID 181 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
r($execution->getBeginEnd4CFDTest($executionIDList[5])) && p('0,1') && e('1,1'); // ID 191 的专业研发看板累计流图默认开始和结束时间 是不是14天前和今天
