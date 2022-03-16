#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

su('admin');

/**

title=测试productModel->create();
cid=1
pid=1

测试正常的创建 >> case1
测试不填产品代号的情况 >> 『code』不能为空。
测试创建重复的产品 >> 『code』已经有『testcase1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试不填产品名称的情况 >> 『name』不能为空。
测试传入name和code >> case3,testcase3
测试传入program、name、code >> 3,case4,testcase4
测试传入program、name、code、type、status >> 4,branch,closed

*/

$config = new stdClass();
$config->createFields->product = array('program' => '', 'name' => '', 'code' => '', 'PO' => '', 'QD' => '', 'RD' => '', 'type' => 'normal', 'status' => 'normal', 'desc' => '', 'acl' => 'private');
/*
function createObject($module = '', $param = array())
{
    global $config;
    foreach($config->createFields->$module as $field => $defaultValue) $_POST[$field] = $defaultValue;

    foreach($param as $key => $value) $_POST[$key] = $value;

    global $tester;
    $objectModel = $tester->loadModel($module);
    $objectID = $objectModel->create();
    unset($_POST);

    if(dao::isError())
    {
        return dao::getError();
    }
    else
    {
        $object = $objectModel->getByID($objectID);
        return $object;
    }
}*/
$createObject = new Product('admin');

$create1 = array('name' => 'case1', 'code' => 'testcase1');
$create2 = array('name' => 'case2');
$create3 = array('name' => 'case1', 'code' => 'testcase1');
$create4 = array('code' => 'testcase1');
$create5 = array('name' => 'case3', 'code' => 'testcase3');
$create6 = array('program' => '3', 'name' => 'case4', 'code' => 'testcase4');
$create7 = array('program' => '4', 'name' => 'case5', 'code' => 'testcase5', 'type' => 'branch', 'status' => 'closed');

r($createObject->createObject('product', $create1)) && p('name')                && e('case1');              // 测试正常的创建
r($createObject->createObject('product', $create2)) && p('code:0')              && e('『code』不能为空。'); // 测试不填产品代号的情况
r($createObject->createObject('product', $create3)) && p('code:0')              && e('『code』已经有『testcase1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 测试创建重复的产品
r($createObject->createObject('product', $create4)) && p('name:0')              && e('『name』不能为空。'); // 测试不填产品名称的情况
r($createObject->createObject('product', $create5)) && p('name,code')           && e('case3,testcase3');    // 测试传入name和code
r($createObject->createObject('product', $create6)) && p('program,name,code')   && e('3,case4,testcase4');  // 测试传入program、name、code
r($createObject->createObject('product', $create7)) && p('program,type,status') && e('4,branch,closed');    // 测试传入program、name、code、type、status
system("./ztest init");