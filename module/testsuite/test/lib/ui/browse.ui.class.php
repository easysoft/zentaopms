<?php
declare(strict_types=1);
include dirname(__FILE__, 6).'/test/lib/ui.php';
class browseTestSuiteTester extends tester
{
    /*
     * check the testsuit count.
     * @access public
     * @return object
     */
    public function browseTestSuite()
    {
        $form = $this->initForm('testsuite', 'browse', array('productID' => 1), 'appIframe-qa');

        if($form->dom->sumCount->getText()           != 2) return $this->failed('测试套件标签数统计错误');
        if($form->dom->footerSumCount->getText()     != 2) return $this->failed('测试套件列表底部总数统计错误');
        if($form->dom->footerPublicCount->getText()  != 1) return $this->failed('测试套件列表底部公开数统计错误');
        if($form->dom->footerPrivateCount->getText() != 1) return $this->failed('测试套件列表底部私有数统计错误');

        return $this->success('测试套件列表测试成功');
    }
}
