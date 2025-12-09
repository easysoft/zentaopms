<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class testcaseTester extends tester
{
    /**
     * 切换产品查看执行下用例。
     * Switch products to view testcases.
     *
     * @param  string $product
     * @param  string $expectNum
     * @access public
     * @return void
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('execution', 'testcase', array('execution' => '2' ), 'appIframe-execution');
        $form->dom->productNav->click();
        $form->wait(1);
        $form->dom->$product->click();
        $form->wait(1);

        if($form->dom->num->getText() == $expectNum) return $this->success('切换产品查看用例成功');
        return $this->failed('切换产品查看用例失败');
    }
}
