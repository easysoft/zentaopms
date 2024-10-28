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
        $form->dom->allTab->click();
        $form->wait(1);
        $form->dom->selectAllBtn->click();//全选产品
        $form->wait(1);
        $form->dom->batchEditBtn->click();//点击编辑按钮
        $firstID = $form->dom->id_static_0->getText();//获取第一个ID
        $nameInputDom  = "name[{$firstID}]";
        $typePickDom   = "type[{$firstID}]";
        $statusPickDom = "status[{$firstID}]";
        //修改表单字段
        if (isset($product->name))      $form->dom->$nameInputDom->setValue($product->name);
        if (isset($product->type))      $form->dom->$typePickDom->picker($product->type);
        if (isset($product->status))    $form->dom->$statusPickDom->picker($product->status);
        if (isset($product->acl))
        {
            if ($product->acl == 'open')
            {
                $form->dom->aclopen_0_0->click();
            }
            else if ($product->acl == 'private')
            {
                $form->dom->aclprivate_0_0->click();
            }
        }
        $form->dom->saveBtn->click();
        $form->wait(1);
        return $this->checkBatchEdit($product, $firstID);
    }

    /**
     * 检查批量编辑产品的结果
     * Check the result of batch edit product
     *
     * @param  object $product
     * @param  string $firstID
     * @return mixed
     */
    public function checkBatchEdit($product, $firstID)
    {
        if ($this->response('method') == 'all')
        {
            $viewUrl['productID'] = $firstID;
            $viewPage = $this->initForm('product', 'view', $viewUrl, 'appIframe-product');
            if (isset($product->name))
            {
                return ($viewPage->dom->productName->getText() == $product->name) ? $this->success('产品名称修改成功') : $this->failed('产品名称修改失败');
            }
            if (isset($product->type))
            {
                return ($viewPage->dom->type->getText() == $product->type) ? $this->success('产品类型修改成功') : $this->failed('产品类型修改失败');
            }
            if (isset($product->status))
            {
                return ($viewPage->dom->status->getText() == $product->status) ? $this->success('产品状态修改成功') : $this->failed('产品状态修改失败');
            }
            if ($product->acl == 'open')
            {
                $open = $this->lang->product->abbr->aclList->open;
                return ($viewPage->dom->acl->getText() == $open) ? $this->success('产品访问控制修改成功') : $this->failed('产品访问控制修改失败');
            }
            if ($product->acl == 'private')
            {
                $private = $this->lang->product->abbr->aclList->private;
                return ($viewPage->dom->acl->getText() == $private) ? $this->success('产品访问控制修改成功') : $this->failed('产品访问控制修改失败');
            }
        }
        else
        {
            return $this->failed('批量编辑产品失败');
        }
    }
}
