#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->getHostByID().
timeout=0
cid=1

- 获取ID为1的主机的id,type,extranet
 - 属性id @1
 - 属性type @normal
 - 属性extranet @10.0.0.1
- 获取ID为10的主机的id,type,extranet
 - 属性id @10
 - 属性type @normal
 - 属性extranet @10.0.0.10
- 获取ID为0的主机，ID为0的主机不存在,返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(10);

$ipList = array(1, 10, 0);

$zanode = new zanodeTest();

r($zanode->getHostByID($ipList[0])) && p('id,type,extranet') && e('1,normal,10.0.0.1');     //获取ID为1的主机的id,type,extranet
r($zanode->getHostByID($ipList[1])) && p('id,type,extranet') && e('10,normal,10.0.0.10');   //获取ID为10的主机的id,type,extranet
r($zanode->getHostByID($ipList[2])) && p('') && e('0');                                     //获取ID为0的主机，ID为0的主机不存在,返回false
