<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 检查产品下创建模块。
     * Check the creation of modules under the product.
     *
     * @param  string $moduleName
     * @access public
     * @return object
     */
    public function createModule($moduleName)
    {
        $form = $this->initForm('tree', 'browse', array('product' => '1', 'view' => 'story'), 'appIframe-product');
        $form->dom->firsrNullModule->setValue($moduleName);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if($form->dom->firstModule->getText() == $moduleName) return $this->success('创建模块成功');
        return $this->failed('创建模块失败');
    }

    /**
     * 检查产品下创建子模块。
     * Check the creation of child modules under the product.
     *
     * @param  string $childModuleName
     * @access public
     * @return object
     */
    public function createChildModule($childModuleName)
    {
        $form = $this->initForm('tree', 'browse', array('product' => '1', 'view' => 'story'), 'appIframe-product');
        if(!is_object($form->dom->firstViewBtn)) return $this->failed('不能创建子模块');
        $form->dom->firstViewBtn->click();
        $form->wait(1);
        $form->dom->firsrNullModule->setValue($childModuleName);
        $form->dom->submitBtn->click();
        $form->wait(1);

        $form = $this->loadPage();
        $form->wait(1);
        if($form->dom->firstCaret->attr('class') == 'caret-right') $form->dom->firstCaret->click();
        $form->wait(1);
        if($form->dom->firstChildModule->getText() == $childModuleName) return $this->success('创建模块成功');
        return $this->failed('创建模块失败');


    }
}
