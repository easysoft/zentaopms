<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditProduct extends tester
{
    /**
     * 批量编辑产品
     * Batch edit product
     *
     * @param  object $product
     * @return mixed
     */
    public function batchEditProduct($product)
    {
        $form = $this->initForm('product', 'all', array(), 'appIframe-product');
        $form->dom->allProductTab->click();
        $form->wait(2);
        $form->dom->selectAllBtn->click();//全选产品
        $form->dom->batchEditBtn->click();//点击编辑按钮
        $firstID = $form->dom->id_static_0->getText();//获取第一个ID
