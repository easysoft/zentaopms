<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageProductsTester extends tester
{
    /**
     * 维护执行关联产品。
     * Manage Products.
     *
     * @param  array $execution
     * @access public
     * @return void
     */
    public function manageProducts($execution)
    {
        $form = $this->initForm('execution', 'manageproducts', array('execution' => $execution['id']), 'appIframe-execution');
        if($execution['operate'] == 'link')   $form->dom->productb->click();
        if($execution['operate'] == 'unlink') $form->dom->producta->click();
        $form->dom->btn($this->lang->save)->click();

        $form = $this->initForm('execution', 'view', array('execution' => $execution['id']), 'appIframe-execution');
        if($execution['operate'] == 'link')
        {
            if($form->dom->linckedProductb === false) return $this->failed('关联产品失败');
            return $this->success('关联产品成功');
        }
        if($form->dom->linckedProducta === true) return $this->failed('取消关联产品失败');
        return $this->success('取消关联产品成功');
    }

    /**
     * 执行下是否有产品导航。
     * Product navigation.
     *
     * @param  array  $execution
     * @param  bool   $expect
     * @access public
     * @return void
     */
    public function checkProductsNev($execution, $expect)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $execution['id']), 'appIframe-execution');
        if(is_object($form->dom->productsNev) === $expect) return $this->success('关联产品导航显示正确');
        return $this->failed('关联产品导航显示错误');
    }
}
