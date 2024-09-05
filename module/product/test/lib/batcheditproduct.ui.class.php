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
        $nameInputDom  = "name[{$firstID}]";
        $typePickDom   = "type[{$firstID}]";
        $statusPickDom = "status[{$firstID}]";
        //修改表单字段
        if (isset($product->name))      $form->dom->$nameInputDom->setValue($product->name);
        if (isset($product->type))      $form->dom->$typePickDom->picker($product->type);
        if (isset($product->status))    $form->dom->$statusPickDom->picker($product->status);
        if ($product->acl == 'open')    $form->dom->aclopen_0_0->click();
        if ($product->acl == 'private') $form->dom->aclprivate_0_0->click();
        $form->dom->saveBtn->click();
        $form->wait(1);
        return $this->checkBatchEdit($product, $firstID);
    }

    /**
     * 检查批量编辑产品的结果
     * Check the result of batch edit product
     *
     * @param  object $product
