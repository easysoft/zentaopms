#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 systemModel::backup();
timeout=0
cid=18727

- 查询默认备份模式为空属性result @fail
- 查询备份模式为手动属性message @CNE服务器出错
- 查询备份模式为系统属性result @fail
- 查询备份模式为升级属性message @CNE服务器出错
- 查询备份模式为降级属性result @fail

*/
global $tester;
$system = $tester->loadModel('system');

$instance = new stdClass();
$instance->spaceData = new stdClass();
$instance->spaceData->k8space = 'qucikon-system';
$instance->k8name = 'zentaopaas';
r($system->backup($instance, ''))          && p('result')  && e('fail');          // 查询默认备份模式为空
r($system->backup($instance, 'manual'))    && p('message') && e('CNE服务器出错'); // 查询备份模式为手动
r($system->backup($instance, 'system'))    && p('result')  && e('fail');          // 查询备份模式为系统
r($system->backup($instance, 'upgrade'))   && p('message') && e('CNE服务器出错'); // 查询备份模式为升级
r($system->backup($instance, 'downgrade')) && p('result')  && e('fail');          // 查询备份模式为降级
