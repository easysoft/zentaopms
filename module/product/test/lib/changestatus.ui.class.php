<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';

class changeStatus extends tester
{
    /**
     * 关闭产品
     * close product
     *
     * @param  $producrID 产品ID
     * @return mixed
     */
    public function closeProduct($productID)
    {
        $form = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->dom->btn($this->lang->product->close)->click();//产品详情页中点击关闭
        $form->wait(2);
        $form->dom->confirmBtn->click();//弹窗中点击关闭产品
        $viewPage = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->wait(2);
        $closed = $this->lang->product->statusList->closed;//产品详情页中关闭状态的语言项
        //判断详情页中的状态是否为[关闭]
        if($viewPage->dom->status->getText() == $closed)
        {
            return $this->success('关闭产品成功');
        }
        return $this->failed('关闭产品失败');
    }
    /**
     * 激活产品
     * activate product
     *
     * @param  $producrID 产品ID
     * @return mixed
     */
    public function activateProduct($productID)
    {
        $form = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->dom->btn($this->lang->product->activate)->click();//产品详情页中点击激活
        $form->wait(2);
        $form->dom->confirmBtn->click();//弹窗中点击激活产品
        $viewPage = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->wait(2);
        $normal = $this->lang->product->statusList->normal;//产品详情页中正常状态的语言项
        //判断详情页中的状态是否为[激活]
        if($viewPage->dom->status->getText() == $normal)
        {
            return $this->success('激活产品成功');
        }
        return $this->failed('激活产品失败');
    }
    /**
     * 删除产品
     * delete product
     *
     * @param  $producrID 产品ID
     * @return mixed
     */
    public function deleteProduct($productID)
    {
        $form = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->dom->delBtn->click();//产品详情页中点击删除
        $form->wait(1);
        $form->dom->delConfirmBtn->click();//删除弹窗中点确认
        $form->wait(2);
        $viewPage = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $deleted  = $this->lang->product->deleted;//产品详情页中已删除状态的语言项
        //判断详情页中的状态是否为[已删除]
        return ($viewPage->dom->delStatus->getText() == $deleted) ? $this->success('删除产品成功') : $this->failed('删除产品失败');
    }
}
