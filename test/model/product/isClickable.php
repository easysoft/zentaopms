#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试productModel->isClickable();
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

    public function testIsClickable($productID, $status)
    {
        $product = $this->product->getById($productID);
        $isClick = $this->product->isClickable($product, $status);
        return $isClick == false ? 'false' : 'true';
    }
}

$adminTester = new Tester('admin');

r($adminTester->testIsClickable(2, 'close'))  && p() && e('true');  // status为normal,action为close
r($adminTester->testIsClickable(75, 'close')) && p() && e('false'); // status为close,action为close
r($adminTester->testIsClickable(2, 'start'))  && p() && e('true');  // status为normal,action为start
r($adminTester->testIsClickable(75, 'start')) && p() && e('true');  // status为close,action为start
