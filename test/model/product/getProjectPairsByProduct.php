#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=productModel->getProjectPairsByProduct();
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
    }

    public function getProjectPairsByProductID($productID)
    {
        $projects = $this->product->getProjectPairsByProduct($productID, 0, 0);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    public function getAppendProject($projectID)
    {
        $project = $this->product->getProjectPairsByProduct(10086, 0, $projectID);
        if($project == array()) return '没有数据';
        return $project;
    }
}

$tester = new Tester('admin');

r($tester->getProjectPairsByProductID(1))   && p('11') && e('项目1');    // 返回产品1关联的项目11名字
r($tester->getProjectPairsByProductID(1))   && p('21') && e('项目11');   // 返回产品1关联的项目21名字
r($tester->getProjectPairsByProductID(101)) && p()     && e('没有数据'); // 传入不存在的产品

r($tester->getAppendProject(15))  && p('15') && e('项目5');    // 返回id为15的项目名
r($tester->getAppendProject(701)) && p()     && e('没有数据'); // 传入不存在的项目id
