#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=productModel->getList();
cid=1
pid=1

返回项目集1下的产品数量 >> 9
测试传入programID=0的情况 >> 100
传入不存在的项目集 >> 0
返回项目集1下的未关闭的产品数量 >> 5
获取所有的未关闭的产品数量 >> 60
返回项目集1下的关闭了的产品数量 >> 4
返回所有的未关闭的产品数量 >> 40
返回项目集1下的与当前用户有关系的产品数量 >> 0
返回项目集1下产品线1的产品数量 >> 9
返回项目集1下产品线2的产品数量 >> 0

*/

class Tester
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
    }
    public function getAllProducts($programID)
    {
        return $this->product->getList($programID);
    }
    public function getAllProductsCount($programID)
    {
        return count($this->getAllProducts($programID));
    }

    public function getNoclosedProducts($programID)
    {
        return $this->product->getList($programID, 'noclosed');
    }
    public function getNoclosedProductsCount($programID)
    {
        return count($this->getNoclosedProducts($programID));
    }

    public function getClosedProducts($programID)
    {
        return $this->product->getList($programID, 'closed');
    }
    public function getClosedProductsCount($programID)
    {
        return count($this->getClosedProducts($programID));
    }

    public function getInvolvedProducts($programID)
    {
        return $this->product->getList($programID, 'involved');
    }
    public function getInvolvedProductsCount($programID)
    {
        return count($this->getInvolvedProducts($programID));
    }

    public function getProductsByLine($programID, $line = 0)
    {
        return $this->product->getList($programID, 'all', 0, $line);
    }
    public function countProductsByLine($programID, $line = 0)
    {
        return count($this->getProductsByLine($programID, $line));
    }

    public function getProductList($programID, $status = 'all', $line = 0)
    {
        return $this->product->getList($programID, $status, 0, $line);
    }
    public function getProductCount($programID, $status = 'all', $line = 0)
    {
        return count($this->getProductList($programID, $status, $line));
    }
}

$adminTester = new Tester('admin');

r($adminTester->getAllProductsCount(1))  && p() && e('9');   // 返回项目集1下的产品数量
r($adminTester->getAllProductsCount(0))  && p() && e('100'); // 测试传入programID=0的情况
r($adminTester->getAllProductsCount(11)) && p() && e('0');   // 传入不存在的项目集

r($adminTester->getNoclosedProductsCount(1)) && p() && e('5');  // 返回项目集1下的未关闭的产品数量
r($adminTester->getNoclosedProductsCount(0)) && p() && e('60'); // 获取所有的未关闭的产品数量

r($adminTester->getClosedProductsCount(1)) && p() && e('4');  // 返回项目集1下的关闭了的产品数量
r($adminTester->getClosedProductsCount(0)) && p() && e('40'); // 返回所有的未关闭的产品数量

r($adminTester->getInvolvedProductsCount(1)) && p() && e('0'); // 返回项目集1下的与当前用户有关系的产品数量

r($adminTester->countProductsByLine(1, 1)) && p() && e('9'); // 返回项目集1下产品线1的产品数量
r($adminTester->countProductsByLine(1, 2)) && p() && e('0'); // 返回项目集1下产品线2的产品数量