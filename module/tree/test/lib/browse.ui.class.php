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

        if($form->dom->lastModule->getText() == $moduleName) return $this->success('创建模块成功');
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

    /**
     * 编辑模块。
     * Edit the module.
     *
     * @param  string $moduleName
     * @access public
     * @return object
     */
    public function editModule($newName)
    {
        $form = $this->initForm('tree', 'browse', array('product' => '1', 'view' => 'story'), 'appIframe-product');
        $form->dom->firstEditBtn->click();
        $form->wait(1);
        $form->dom->name->setValue($newName);
        $form->wait(1);
        $form->dom->editSubmitBtn->click();
        $form->wait(1);

        if(empty($newName))
        {
            if($form->dom->nameTip->getText() == sprintf($this->lang->error->notempty, $this->lang->tree->name)) return $this->success('编辑模块时模块为空，提示正确');
            return $this->failed('编辑模块时模块为空，提示错误');
        }
        if($form->dom->firstModule->getText() == $newName) return $this->success('编辑模块成功');
        return $this->failed('编辑模块失败');
    }
}
