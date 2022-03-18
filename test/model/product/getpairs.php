#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->getPairs();
cid=1
pid=1

测试项目集10下的11号产品 >> 正常产品11
测试项目集10下的55号产品 >> 多分支产品55
测试项目集10下的99号产品 >> 多平台产品99
测试不存在的项目集 >> 没有数据
返回所有产品的数量 >> 100
返回项目集10下的所有产品 >> 9
测试项目集10下的未关闭产品5 >> 正常产品6
返回项目集10下的未关闭产品的数量 >> 5

*/

/*class Tester
{
    public function __construct($user, $orderBy = 'id_desc')
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
        $this->config = new stdclass();
        $this->config->product->orderBy = $orderBy;
    }

    public function getProductPairs($programID)
    {
        $pairs = $this->product->getPairs('', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getProductPairsCount($programID)
    {
        $pairsCount = count($this->getProductPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    public function getAllPairs($programID)
    {
        $pairs = $this->product->getPairs('all', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getAllPairsCount($programID)
    {
        $pairsCount = count($this->getAllPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    public function getNoclosedPairs($programID)
    {
        $pairs = $this->product->getPairs('noclosed', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getNoclosedPairsCount($programID)
    {
        $pairsCount = count($this->getNoclosedPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    public function getProductPairsByOrder($programID, $mode = '', $orderBy = 'id_desc')
    {
        $this->config->product->orderBy = $orderBy;
        $pairs = $this->product->getPairs($mode, $programID);
        return checkOrder($pairs, $orderBy);
    }
}*/

$tester = new Product('admin');

$t_peoduct10 = array('programID'=>'10');
$t_noproduct = array('programID'=>'11');
$t_alproduct = array('programID'=>'0');
$t_notclose5 = array('programID'=>'5');

r($tester->getProductPairs($t_peoduct10['programID']))       && p('11') && e('正常产品11');   // 测试项目集10下的11号产品
r($tester->getProductPairs($t_peoduct10['programID']))       && p('55') && e('多分支产品55'); // 测试项目集10下的55号产品
r($tester->getProductPairs($t_peoduct10['programID']))       && p('99') && e('多平台产品99'); // 测试项目集10下的99号产品
r($tester->getProductPairs($t_noproduct['programID']))       && p()     && e('没有数据');     // 测试不存在的项目集
r($tester->getProductPairsCount($t_alproduct['programID']))  && p()     && e('100');          // 返回所有产品的数量
r($tester->getProductPairsCount($t_peoduct10['programID']))  && p()     && e('9');            // 返回项目集10下的所有产品
r($tester->getNoclosedPairs($t_notclose5['programID']))      && p('6')  && e('正常产品6');    // 测试项目集10下的未关闭产品5
r($tester->getNoclosedPairsCount($t_peoduct10['programID'])) && p()     && e('5');            // 返回项目集10下的未关闭产品的数量