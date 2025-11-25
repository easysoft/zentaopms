<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class browsetaskTester extends tester
{
    /**
     * 检查执行下创建模块。
     * Check the creation of modules under the execution.
     *
     * @param  string $moduleName
     * @param  bool   $checkRepeat
     * @access public
     * @return object
     */
    public function createModule($moduleName, $checkRepeat = false)
    {
        $form = $this->initForm('tree', 'browsetask', array('rootID' => '2'), 'appIframe-execution');
        $form->wait(1);
        if($checkRepeat) $moduleName = preg_replace('/\[.*]/', '', $form->dom->firstModule->getText());
        $form->dom->firsrNullModule->setValue($moduleName);
        $form->dom->submitBtn->click();
        $form->wait(3);

        if($checkRepeat)
        {
            if($form->dom->modalText->getText() == sprintf($this->lang->tree->repeatName, $moduleName)) return $this->success('创建模块时模块已存在，提示正确');
            return $this->failed('创建模块时模块已存在，提示错误');
        }
        if(ctype_space($moduleName))
        {
            if($form->dom->modalText->getText() == $this->lang->tree->shouldNotBlank) return $this->success('创建模块时模块名包含空格，提示正确');
            return $this->failed('创建模块时模块名包含空格，提示错误');
        }
        if(preg_replace('/\[.*]/', '', $form->dom->lastModule->getText()) == $moduleName) return $this->success('创建模块成功');
        return $this->failed('创建模块失败');
    }

    /**
     * 检查执行下创建子模块。
     * Check the creation of child modules under the execution.
     *
     * @param  string $childModuleName
     * @param  bool   $checkRepeat
     * @access public
     * @return object
     */
    public function createChildModule($childModuleName, $checkRepeat = false)
    {
        $form = $this->initForm('tree', 'browsetask', array('rootID' => '2'), 'appIframe-execution');
        $form->wait(1);
        if(!is_object($form->dom->firstViewBtn)) return $this->failed('不能创建子模块');
        $form->dom->firstViewBtn->click();
        $form->wait(1);
        if($checkRepeat) $childModuleName = preg_replace('/\[.*]/', '', $form->dom->firstChildModule->getText());
        $form->dom->firsrNullModule->setValue($childModuleName);
        $form->dom->submitBtn->click();
        $form->wait(1);

        $form = $this->loadPage();
        $form->wait(1);

        if($checkRepeat)
        {
            if($form->dom->modalText->getText() == sprintf($this->lang->tree->repeatName, $childModuleName)) return $this->success('创建子模块时子模块已存在，提示正确');
            return $this->failed('创建子模块时子模块已存在，提示错误');
        }
        if(ctype_space($childModuleName))
        {
            if($form->dom->modalText->getText() == $this->lang->tree->shouldNotBlank) return $this->success('创建子模块时子模块名包含空格，提示正确');
            return $this->failed('创建子模块时子模块名包含空格，提示错误');
        }
        if($form->dom->firstCaret->attr('class') == 'caret-right') $form->dom->firstCaret->click();
        $form->wait(1);
        if(preg_replace('/\[.*]/', '', $form->dom->lastChildModule->getText()) == $childModuleName) return $this->success('创建子模块成功');
        return $this->failed('创建子模块失败');
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
        $form = $this->initForm('tree', 'browsetask', array('rootID' => '3'), 'appIframe-execution');
        $form->wait(1);
        $form->dom->firstEditBtn->click();
        $form->wait(1);
        $form->dom->name->setValue($newName);
        $form->wait(1);
        $form->dom->editSubmitBtn->click();
        $form->wait(3);

        if(empty($newName) || ctype_space($newName))
        {
            if($form->dom->nameTip->getText() == sprintf($this->lang->error->notempty, $this->lang->tree->name)) return $this->success('编辑模块时模块为空，提示正确');
            return $this->failed('编辑模块时模块为空，提示错误');
        }
        if(preg_replace('/\[.*]/', '', $form->dom->lastModule->getText()) == $newName)
        {
            if($form->dom->nameTip->getText() == sprintf($this->lang->tree->repeatName, $newName)) return $this->success('编辑模块时模块已存在，提示正确');
            return $this->failed('编辑模块时模块已存在，提示错误');
        }
        if(preg_replace('/\[.*]/', '', $form->dom->firstModule->getText()) == $newName) return $this->success('编辑模块成功');
        return $this->failed('编辑模块失败');
    }

    /**
     * 删除模块。
     * Delete the module.
     *
     * @access public
     * @return object
     */
    public function deleteModule()
    {
        $form = $this->initForm('tree', 'browsetask', array('rootID' => '3'), 'appIframe-execution');
        $form->wait(1);
        if($form->dom->firstCaret->attr('class') == 'caret-right') $form->dom->firstCaret->click();
        $form->wait(1);
        $moduleName = preg_replace('/\[.*]/', '', $form->dom->lastChildModule->getText());
        $form->dom->lastChildDelBtn->click();
        $form->wait(1);
        if($form->dom->modalText->getText() != $this->lang->tree->confirmDelete) return $this->failed('删除模块提示信息错误');
        $form->dom->modalConfirm->click();
        $form->wait(3);

        if(preg_replace('/\[.*]/', '', $form->dom->lastChildModule->getText()) == $moduleName) return $this->failed('删除模块失败');
        return $this->success('删除模块成功');
    }
}
