<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editProductTester extends tester
{
    /**
     * Edit product
     *
     * @param  $productID 产品ID
     * @param  $product 产品数据
     * $return mixed
     */
    public function editProduct($productID, $product)
    {
        $form = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->dom->editBtn->click();
        if(isset($product->name)) $form->dom->name->setValue($product->name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        if($this->response('method') != 'view')
        {
            if($this->checkFormTips('product')) return $this->success('编辑产品表单提示信息正确');
            return $this->failed('编辑产品表单提示信息不正确');
        }
        $viewPage = $this->initForm('product', 'view', $productID, 'appIframe-product');
        if($viewPage->dom->productName->getText() != $product->name) return $this->failed('名称错误');
        return $this->success('产品编辑成功');
    }
}
