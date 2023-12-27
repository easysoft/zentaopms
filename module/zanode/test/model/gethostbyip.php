#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->getHostByIP().
timeout=0
cid=1

- 判断ip为10.0.0.1的主机是否存在,并返回id,type,extranet
 - 属性id @1
 - 属性type @node
 - 属性extranet @10.0.0.1
- 判断ip为10.0.0.10的主机是否存在,并返回id,type,extranet
 - 属性id @10
 - 属性type @physics
 - 属性extranet @10.0.0.10
- 判断ip为11111的主机是否存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(10);

$ipList = array('10.0.0.1', '10.0.0.10', '11111');

$zanode = new zanodeTest();

r($zanode->getHostByIP($ipList[0])) && p('id,type,extranet') && e('1,node,10.0.0.1');       //判断ip为10.0.0.1的主机是否存在,并返回id,type,extranet
r($zanode->getHostByIP($ipList[1])) && p('id,type,extranet') && e('10,physics,10.0.0.10');  //判断ip为10.0.0.10的主机是否存在,并返回id,type,extranet
r($zanode->getHostByIP($ipList[2])) && p('') && e('0');                                     //判断ip为11111的主机是否存在
