#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->setLibMenu();
cid=1
pid=1

修改下拉菜单之后返回值 >> 1

*/

$caselib         = new caselibTest();
$libs            = $tester->loadModel('caselib')->getLibraries();
$config->webRoot = 'model/caselib/';//ztest运行时候获取的不一致
$info            = $caselib->setLibMenuTest($libs, 201);

$menuStr = <<<EOT
<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='这是测试套件名称201'><span class='text'>这是测试套件名称201</span> <span class='caret' style='margin-top: 3px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='model/caselib/setlibmenu.php?m=caselib&f=ajaxGetDropMenu&t=&objectID=201&module=caselib&method=browse'><div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div></div></div>
EOT;
r($tester->lang->switcherMenu == trim($menuStr)) && p() && e('1'); //修改下拉菜单之后返回值