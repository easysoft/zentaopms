<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class spaceTester extends tester
{
    /**
     * 创建空间
     * Create Space
     * @param  string $type 空间类型
     * @param  object $space 空间信息
     * @return mixed
     */
    public function createSpace($type, $space)
    {
        $form = $this->initForm('kanban', 'space', array('browseType' => $type), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->createSpace)->click();
        $form->wait(2);

        // 设置表单字段值
        if (isset($space->name))  $form->dom->name->setValue($space->name);
        if (isset($space->owner)) $form->dom->owner->picker($space->owner);
        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(2);
        //校验创建结果
        if ($form->dom->zin_kanban_createspace_formPanel)
        {
            $nameTip = sprintf($this->lang->error->notempty,$this->lang->kanbanspace->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('空间名称必填提示信息正确')
                : $this->failed('空间名称必填提示不正确');
        }
        return ($form->dom->spaceName->getText() == $space->name)
            ? $this->success('创建空间成功')
            : $this->failed('创建空间失败');
    }

    /**
     * 编辑空间
     * Edit Space
     *
     * @param  object $space 空间信息
     * @return mixed
     */
    public function editSpace($space)
    {
        $form = $this->initForm('kanban', 'space', array(), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->setting)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->kanban->editSpace)->click();
        $form->wait(2);
        if (isset($space->name)) $form->dom->name->setValue($space->name);
        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(2);
        //校验编辑结果
        if($form->dom->zin_kanban_editspace_formPanel)
        {
            $nameTip = sprintf($this->lang->error->notempty,$this->lang->kanbanspace->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('空间名称必填提示信息正确')
                : $this->failed('空间名称必填提示不正确');
        }
        return ($form->dom->spaceName->getText() == $space->name)
            ? $this->success('编辑空间成功')
            : $this->failed('编辑空间失败');
    }
}
