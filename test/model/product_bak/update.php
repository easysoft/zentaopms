#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');

/**

title=测试productModel->update();
cid=1
pid=1

测试更新产品名称 >> name,正常产品2,john
测试更新产品代号 >> code,code2,newcode1
测试更新产品名称和代号 >> name,john,jack;code,newcode1,newcode2
测试不更改产品名称 >> 没有数据更新
测试不更改产品代号 >> 没有数据更新
测试同一项目集下产品名称不能重复 >> 『产品名称』已经有『jack』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试同一项目集下产品代号不能重复 >> 『产品代号』已经有『newcode2』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

function updateObject($module, $objectID, $param = array())
{
    global $tester;
    $objectModel = $tester->loadModel($module);

    $object = $objectModel->getById($objectID);
    foreach($object as $field => $value)
    {
        if(in_array($field, array_keys($param)))
        {
            $_POST[$field] = $param[$field];
        }
        else
        {
            $_POST[$field] = $value;
        }
    }

    $change = $objectModel->update($objectID);
    if($change == array()) $change = '没有数据更新';
    unset($_POST);

    if(dao::isError())
    {
        return dao::getError();
    }
    else
    {
        return $change;
    }
}

$case1 = array('name' => 'john');
$case2 = array('code' => 'newcode1');
$case3 = array('name' => 'jack', 'code' => 'newcode2');
$case4 = array('name' => 'jack');
$case5 = array('code' => 'newcode2');
$case6 = array('name' => 'jack');
$case7 = array('code' => 'newcode2');

r(updateObject('product', 2, $case1))  && p('0:field,old,new') && e('name,正常产品2,john'); // 测试更新产品名称
r(updateObject('product', 2, $case2))  && p('0:field,old,new') && e('code,code2,newcode1'); // 测试更新产品代号
r(updateObject('product', 2, $case3))  && p('0:field,old,new;1:field,old,new') && e('name,john,jack;code,newcode1,newcode2'); // 测试更新产品名称和代号
r(updateObject('product', 2, $case4))  && p() && e('没有数据更新'); // 测试不更改产品名称
r(updateObject('product', 2, $case5))  && p() && e('没有数据更新'); // 测试不更改产品代号
r(updateObject('product', 13, $case6)) && p('name:0') && e('『产品名称』已经有『jack』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');     // 测试同一项目集下产品名称不能重复
r(updateObject('product', 13, $case7)) && p('code:0') && e('『产品代号』已经有『newcode2』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 测试同一项目集下产品代号不能重复

system("./ztest init");