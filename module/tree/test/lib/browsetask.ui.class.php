<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        if($checkRepeat) $moduleName = $form->dom->firstModule->getText();
        $form->dom->firsrNullModule->setValue($moduleName);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if($checkRepeat)
        {
            if($form->dom->modalText->getText() == sprintf($this->lang->tree->repeatName, $moduleName)) return $this->success('创建模块时模块已存在，提示正确');
            return $this->failed('创建模块时模块已存在，提示错误');
        }
        if(preg_match('/\s/', $moduleName))
        {
            if($form->dom->modalText->getText() == $this->lang->tree->shouldNotBlank) return $this->success('创建模块时模块名包含空格，提示正确');
            return $this->failed('创建模块时模块名包含空格，提示错误');
        }
        if($form->dom->lastModule->getText() == $moduleName) return $this->success('创建模块成功');
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
        if(!is_object($form->dom->firstViewBtn)) return $this->failed('不能创建子模块');
        $form->dom->firstViewBtn->click();
        $form->wait(1);
        if($checkRepeat) $childModuleName = $form->dom->firstChildModule->getText();
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
        if(preg_match('/\s/', $childModuleName))
        {
            if($form->dom->modalText->getText() == $this->lang->tree->shouldNotBlank) return $this->success('创建子模块时子模块名包含空格，提示正确');
            return $this->failed('创建子模块时子模块名包含空格，提示错误');
        }
        if($form->dom->firstCaret->attr('class') == 'caret-right') $form->dom->firstCaret->click();
        $form->wait(1);
        if($form->dom->lastChildModule->getText() == $childModuleName) return $this->success('创建子模块成功');
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
        $form = $this->initForm('tree', 'browsetree', array('rootID' => '2'), 'appIframe-execution');
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
        if($form->dom->lastModule->getText() == $newName)
        {
            if($form->dom->nameTip->getText() == sprintf($this->lang->tree->repeatName, $newName)) return $this->success('编辑模块时模块已存在，提示正确');
            return $this->failed('编辑模块时模块已存在，提示错误');
        }
        if(preg_match('/\s/', $newName))
        {
            if(!is_object($form->dom->modalText)) return $this->failed('编辑模块时模块名包含空格，没有提示');
            if($form->dom->modalText->getText() == $this->lang->tree->shouldNotBlank) return $this->success('编辑模块时模块名包含空格，提示正确');
            return $this->failed('编辑模块时模块名包含空格，提示错误');
        }
        if($form->dom->firstModule->getText() == $newName) return $this->success('编辑模块成功');
        return $this->failed('编辑模块失败');
    }
}
