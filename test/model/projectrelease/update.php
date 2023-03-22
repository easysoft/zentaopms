#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->update();
cid=1
pid=1

测试releaseID不存在，name在发布中不存在，date正常存在，不可修改 >> 没有数据更新
测试releaseID为空，name在发布中不存在，date正常存在，不可修改 >> 没有数据更新
测试releaseID正常存在，name在当前发布存在，date正常存在，可修改 >> date,2022-03-31
测试releaseID正常存在，name在当前发布存在，date为空，不可修改 >> 『date』不能为空。
测试releaseID正常存在，name在发布中不存在，date正常存在，可修改 >> name,产品正常的正常的发布2
测试releaseID正常存在，name在发布中不存在，date为空，不可修改 >> 『date』不能为空。
测试releaseID正常存在，name为空，date正常存在，不可修改 >> 『name』不能为空。
测试releaseID正常存在，name为空，date为空，不可修改 >> 『name』不能为空。;『date』不能为空。
测试releaseID正常存在，name在其他发布存在，date正常，不可修改 >> 『name』已经有『产品1发布9』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$releaseID = array(1, 100, '');
$name      = array('产品1正常的发布1', '产品1发布9', '产品正常的正常的发布2', '');
$date      = array('2022-03-31', '');

$projectrelease = new projectreleaseTest();

r($projectrelease->updateTest($releaseID[1], $name[2], $date[0])) && p()                  && e('没有数据更新');                          //测试releaseID不存在，name在发布中不存在，date正常存在，不可修改
r($projectrelease->updateTest($releaseID[2], $name[2], $date[0])) && p()                  && e('没有数据更新');                          //测试releaseID为空，name在发布中不存在，date正常存在，不可修改
r($projectrelease->updateTest($releaseID[0], $name[0], $date[0])) && p('0:field,new')     && e('date,2022-03-31');                       //测试releaseID正常存在，name在当前发布存在，date正常存在，可修改
r($projectrelease->updateTest($releaseID[0], $name[0], $date[1])) && p('date:0')          && e('『date』不能为空。');                    //测试releaseID正常存在，name在当前发布存在，date为空，不可修改
r($projectrelease->updateTest($releaseID[0], $name[2], $date[0])) && p('0:field,new')     && e('name,产品正常的正常的发布2');            //测试releaseID正常存在，name在发布中不存在，date正常存在，可修改
r($projectrelease->updateTest($releaseID[0], $name[2], $date[1])) && p('date:0')          && e('『date』不能为空。');                    //测试releaseID正常存在，name在发布中不存在，date为空，不可修改
r($projectrelease->updateTest($releaseID[0], $name[3], $date[0])) && p('name:0')          && e('『name』不能为空。');                    //测试releaseID正常存在，name为空，date正常存在，不可修改
r($projectrelease->updateTest($releaseID[0], $name[3], $date[1])) && p('name:0;date:0')   && e('『name』不能为空。;『date』不能为空。'); //测试releaseID正常存在，name为空，date为空，不可修改

r($projectrelease->updateTest($releaseID[0], $name[1], $date[0])) && p('name:0')          && e('『name』已经有『产品1发布9』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); //测试releaseID正常存在，name在其他发布存在，date正常，不可修改

