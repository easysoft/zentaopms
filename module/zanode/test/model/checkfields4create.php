#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeTao->checkFields4Create().
cid=1

- 测试添加物理机节点时必填项
 - 第name条的0属性 @『名称』不能为空。
 - 第extranet条的0属性 @『IP/域名』不能为空。
 - 第cpu条的0属性 @『cpu』不能为空。
 - 第memory条的0属性 @『内存』不能为空。
 - 第osNamePhysics条的0属性 @『操作系统』不能为空。
- 测试添加虚拟机节点时必填项
 - 第name条的0属性 @『名称』不能为空。
 - 第host条的0属性 @『所属宿主机』不能为空。
 - 第image条的0属性 @『镜像』不能为空。
 - 第cpu条的0属性 @『cpu』不能为空。
 - 第memory条的0属性 @『内存』不能为空。
 - 第osName条的0属性 @『操作系统』不能为空。
- 测试名称格式为汉字属性name @名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头
- 测试名称格式以符号开头属性name @名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头
- 测试正确的名称格式 @1
- 测试重复的名称属性name @名称已存在

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(1);
zdTable('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

$physicsRequiredTest = array();
$physicsRequiredTest['hostType']      = 'physics';
$physicsRequiredTest['name']          = '';
$physicsRequiredTest['extranet']      = '';
$physicsRequiredTest['cpu']           = '';
$physicsRequiredTest['memory']        = '';
$physicsRequiredTest['osNamePhysics'] = '';
r($zanode->checkFields4CreateTest($physicsRequiredTest)) && p('name:0;extranet:0;cpu:0;memory:0;osNamePhysics:0') && e('『名称』不能为空。;『IP/域名』不能为空。;『cpu』不能为空。;『内存』不能为空。;『操作系统』不能为空。'); //测试添加物理机节点时必填项

$virtualRequiredTest = array();
$virtualRequiredTest['hostType'] = '';
$virtualRequiredTest['name']     = '';
$virtualRequiredTest['host']     = '';
$virtualRequiredTest['image']    = '';
$virtualRequiredTest['cpu']      = '';
$virtualRequiredTest['memory']   = '';
$virtualRequiredTest['osName']   = '';
r($zanode->checkFields4CreateTest($virtualRequiredTest)) && p('name:0;host:0;image:0;cpu:0;memory:0;osName:0') && e('『名称』不能为空。;『所属宿主机』不能为空。;『镜像』不能为空。;『cpu』不能为空。;『内存』不能为空。;『操作系统』不能为空。'); //测试添加虚拟机节点时必填项

$nameStyleTest = array();
$nameStyleTest['name']     = '这是执行节点名称';
$nameStyleTest['hostType'] = '';
$nameStyleTest['host']     = 1;
$nameStyleTest['image']    = 1;
$nameStyleTest['cpuCores'] = 1;
$nameStyleTest['memory']   = 1;
$nameStyleTest['osName']   = 'Ubuntu 20.04';
r($zanode->checkFields4CreateTest($nameStyleTest)) && p('name') && e("名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头"); //测试名称格式为汉字
$nameStyleTest['name'] = '-zanode1';
r($zanode->checkFields4CreateTest($nameStyleTest)) && p('name') && e("名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头"); //测试名称格式以符号开头
$nameStyleTest['name'] = 'normal_name';
r($zanode->checkFields4CreateTest($nameStyleTest)) && p() && e('1'); //测试正确的名称格式

$repeatNameTest = array();
$repeatNameTest['name']     = 'zanode1';
$repeatNameTest['hostType'] = '';
$repeatNameTest['host']     = 1;
$repeatNameTest['image']    = 1;
$repeatNameTest['cpuCores'] = 1;
$repeatNameTest['memory']   = 1;
$repeatNameTest['osName']   = 'Ubuntu 20.04';
r($zanode->checkFields4CreateTest($repeatNameTest)) && p('name') && e('名称已存在'); //测试重复的名称

//$networkTest = array();
//$networkTest['name']     = 'networkTest';
//$networkTest['hostType'] = 'physics';
//$networkTest['extranet'] = '1';
//$networkTest['host']     = 1;
//$networkTest['image']    = 1;
//$networkTest['cpuCores'] = 1;
//$networkTest['memory']   = 1;
//$networkTest['osName']   = 'Ubuntu 20.04';
//r($zanode->checkFields4CreateTest($networkTest)) && p('extranet') && e('无法连接到物理机，请检查网络后重试。'); //测试输入错误的物理机地址extranet
