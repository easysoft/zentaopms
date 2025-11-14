#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel::printHomeButton();
timeout=0
cid=15694

- 打印project应用下的appName按钮。 @<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=project&f=browse' class='btn' style='padding-top: 2px' ><i class='icon icon-project'></i> 项目</a></div>
- 打印product应用下的appName按钮。 @<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=product&f=all' class='btn' style='padding-top: 2px' ><i class='icon icon-product'></i> 产品</a></div>
- 打印qa应用下的appName按钮。 @<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=qa&f=index' class='btn' style='padding-top: 2px' ><i class='icon icon-test'></i> 测试</a></div>
- 打印任务应用下的appName按钮。 @0
- 打印项目模板应用下的appName按钮。 @<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=project&f=template' class='btn' style='padding-top: 2px' ><i class='icon icon-project'></i> 项目模板</a></div>

*/

global $tester, $app, $lang;
$app->loadCommon();
$app->appName    = 'pms';
$app->moduleName = 'project';
$app->methodName = 'all';
$app->setControlFile();

$moduleName    = $app->moduleName;
$methodName    = $app->methodName;
$file2Included = $app->importControlFile();
$className     = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
$module        = new $className();
$app->control  = $module;

ob_start();
commonModel::printHomeButton('project');
$result = ob_get_contents();
$result = str_replace(["\r", "\n"], '', $result);
ob_end_clean();

r($result) && p() && e(`<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=project&f=browse' class='btn' style='padding-top: 2px' ><i class='icon icon-project'></i> 项目</a></div>`); // 打印project应用下的appName按钮。

ob_start();
commonModel::printHomeButton('product');
$result = ob_get_contents();
$result = str_replace(["\r", "\n"], '', $result);
ob_end_clean();

r($result) && p() && e(`<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=product&f=all' class='btn' style='padding-top: 2px' ><i class='icon icon-product'></i> 产品</a></div>`); // 打印product应用下的appName按钮。

ob_start();
commonModel::printHomeButton('qa');
$result = ob_get_contents();
$result = str_replace(["\r", "\n"], '', $result);
ob_end_clean();

r($result) && p() && e(`<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=qa&f=index' class='btn' style='padding-top: 2px' ><i class='icon icon-test'></i> 测试</a></div>`); // 打印qa应用下的appName按钮。

ob_start();
commonModel::printHomeButton('task');
$result = ob_get_contents();
$result = str_replace(["\r", "\n"], '', $result);
ob_end_clean();

r($result) && p() && e('0'); // 打印任务应用下的appName按钮。

$lang->project->common = $lang->projectCommon = $lang->project->template;
ob_start();
commonModel::printHomeButton('project');
$result = ob_get_contents();
$result = str_replace(["\r", "\n"], '', $result);
ob_end_clean();

r($result) && p() && e(`<div class='btn-group header-btn'><a href='pms/printhomebutton.php?m=project&f=template' class='btn' style='padding-top: 2px' ><i class='icon icon-project'></i> 项目模板</a></div>`); // 打印项目模板应用下的appName按钮。
