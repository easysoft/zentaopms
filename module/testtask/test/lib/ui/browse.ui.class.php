<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
    public function checkNum($tab, $num, $allProduct = false, $startTime = '', $endTime = '')
    {
        $form = $this->initForm('testtask', 'browse', array('productID' => '1'), 'appIframe-qa');
        if($allProduct)
        {
            $form->dom->dropMenu->click();
            $form->dom->allProduct->click();
            $form->wait(1);
        }
        $form->dom->$tab->click();
        $form->wait(1);
        if(!empty($startTime) || !empty($endTime))
        {
            $form->dom->begin->datePicker($startTime);
            $form->dom->end->datePicker($endTime);
            $form->wait(1);
        }
        if(!is_object($form->dom->num)) return $this->success('测试单数量为0');
        if($form->dom->num->getText() == $num) return $this->success('标签下的测试单数量正确');
        return $this->failed('标签下的测试单数量不正确');
    }
}
