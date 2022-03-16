#!/usr/bin/env php
<?php include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=productModel->getProjectListByProduct();
cid=1
pid=1

返回产品1关联的项目11名字 >> 项目11
返回产品1关联的项目21名字 >> 项目1
传入不存在的产品 >> 没有数据

*/

class Tester
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
    }

    public function getAllProjectsByProduct($productID)
    {
        $projects = $this->product->getProjectListByProduct($productID, 'all');
        if($projects == array()) return '没有数据';
        return $projects;
    }

    public function getProjectsByStatus($productID, $status)
    {
        $projects = $this->product->getProjectListByProduct($productID, $browseType);
        if($projects == array()) return '没有数据';
        return $projects;
    }
}

$tester = new Tester('admin');
r($tester->getAllProjectsByProduct(1))   && p('21:name') && e('项目11');   // 返回产品1关联的项目11名字
r($tester->getAllProjectsByProduct(1))   && p('11:name') && e('项目1');    // 返回产品1关联的项目21名字
r($tester->getAllProjectsByProduct(101)) && p()          && e('没有数据'); // 传入不存在的产品