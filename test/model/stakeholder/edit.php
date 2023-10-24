#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->edit();
cid=1
pid=1

修改关键干系人 >> key,0,1
修改nature >> 0
修改analysis >> key,1,0
修改strategy >> 0
不输入是否关键干系人 >> 『key』不符合格式，应当为:『/0|1/』。

*/
$stakeholderID = array('7', '17');

$updateKey      = array('key' => '1');
$updateNature   = array('key' => '0', 'nature' => '修改nature');
$updateAnalysis = array('key' => '0', 'analysis' => '修改analysis');
$updateStrategy = array('key' => '0', 'strategy' => '修改strategy');
$noKey          = array('key' => '');

$stakeholder = new stakeholderTest();
r($stakeholder->editTest($stakeholderID[0], $updateKey))      && p('0:field,old,new') && e('key,0,1');                              //修改关键干系人
r($stakeholder->editTest($stakeholderID[1], $updateNature))   && p()                  && e('0');                                    //修改nature
r($stakeholder->editTest($stakeholderID[0], $updateAnalysis)) && p('0:field,old,new') && e('key,1,0');                              //修改analysis
r($stakeholder->editTest($stakeholderID[1], $updateStrategy)) && p()                  && e('0');                                    //修改strategy
r($stakeholder->editTest($stakeholderID[0], $noKey))          && p('key:0')           && e('『key』不符合格式，应当为:『/0|1/』。');//不输入是否关键干系人

