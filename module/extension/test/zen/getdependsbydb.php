#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 extensionZen::getDependsByDB();
timeout=0
cid=16487

- 执行func模块的invokeArgs方法，参数是$zenInstance, ['base_plugin']  @0
- 执行func模块的invokeArgs方法，参数是$zenInstance, ['nonexistent_plugin']  @0
- 执行func模块的invokeArgs方法，参数是$zenInstance, ['independent_plugin_d']  @0
- 执行func模块的invokeArgs方法，参数是$zenInstance, ['test_plugin_a']  @0
- 执行func模块的invokeArgs方法，参数是$zenInstance, ['other_plugin']  @0

*/

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

zenData('extension')->loadYaml('extension_getdependsbydb', false, 2)->gen(5);

$zen = initReference('extension');
$func = $zen->getMethod('getDependsByDB');
$zenInstance = $zen->newInstance();
$zenInstance->extension = $tester->loadModel('extension');

r($func->invokeArgs($zenInstance, ['base_plugin'])) && p() && e('0');
r($func->invokeArgs($zenInstance, ['nonexistent_plugin'])) && p() && e('0');
r($func->invokeArgs($zenInstance, ['independent_plugin_d'])) && p() && e('0');
r($func->invokeArgs($zenInstance, ['test_plugin_a'])) && p() && e('0');
r($func->invokeArgs($zenInstance, ['other_plugin'])) && p() && e('0');