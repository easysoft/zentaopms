<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 检查产品下不同标签下的测试单数量。
     * Check the number of testtask under different tags of a product.
     *
     * @param  bool   $allProduct
     * @param  string $startTime
     * @param  string $endTime
     * @param  string $tab
     * @param  int    $num
     * @access public
     * @return void
     */
    public function checkTestTaskNum($tab, $num, $startTime = '', $endTime = '', $allProduct = false)
    {
        $form = $this->initForm('testtask', 'browse', array('productID' => '1'), 'appIframe-qa');
        if($allProduct)
        {
            $form->dom->dropMenu->click();
            $form->dom->allProduct->click();
            $form->wait(1);
        }
        if(!empty($startTime) || !empty($endTime))
        {
            $form->dom->begin->setValue($startTime);
            $form->dom->end->setValue($endTime);
            $form->wait(1);
        }
        $form->dom->${tab}->click();
        $form->wait(1);
        $tabNum = $tab . 'Num';
        if($form->dom->$tabNum->getText() == $num) return $this->success('标签下的测试单数量正确');
        return $this->failed('标签下的测试单数量不正确');
    }
}
