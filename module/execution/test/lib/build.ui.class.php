<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class buildTester extends tester
{
    /**
     * 切换产品查看版本。
     * Switch products to view builds.
     *
     * @param  string $product
     * @param  string $expectNum
     * @access public
     * @return void
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('execution', 'build', array('execution' => '2' ), 'appIframe-execution');
        if($product != '') $form->dom->product->picker($product);
        $form->wait(1);
        $num = explode(' ', $form->dom->num->getText())[1];
        if($num == $expectNum) return $this->success('版本显示正确');
        return $this->failed('版本显示错误');
    }
}
