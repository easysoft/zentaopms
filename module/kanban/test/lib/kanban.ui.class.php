<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class kanbanTester extends tester
{
    /*
     * 创建看板
     * Create kanban
     *
     * @param  $kanban 看板
     * @return mixed
     */
    public function createKanban($kanban)
    {
        $form = $this->initForm('kanban', 'space', ['browseType' => 'cooperation'], 'appIframe-kanban');
        $form->dom->createKanbanBtn->click();
        $form->wait(2);
        if (isset($kanban->name)) $form->dom->name->setValue($kanban->name);
        $form->dom->saveKanbanBtn->click();
        $form->wait(2);

        //校验创建结果
        if ($form->dom->zin_kanban_create_formPanel)
        {
            if($this->checkFormTips('kanban')) return $this->success('看板名称必填提示信息正确');
            return $this->failed('看板名称必填提示信息不正确');
        }
        return ($form->dom->kanbanName->getText() == $kanban->name)
            ? $this->success('创建看板成功')
            : $this->failed('创建看板失败');
    }

    /**
     * 编辑看板
     * Edit kanban
     *
     * @param  $kanban 看板
     * @return mixed
     */
    public function editKanban($kanban)
    {
        $form = $this->initForm('kanban', 'space', ['browseType' => 'cooperation'], 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->edit)->click();
        $form->wait(2);
        if (isset($kanban->name)) $form->dom->name->setValue($kanban->name);
        $form->dom->saveEditBtn->click();
        $form->wait(2);
        //校验编辑结果
        if($form->dom->zin_kanban_edit_1_formPanel)
        {
            if($this->checkFormTips('kanban')) return $this->success('看板名称必填提示信息正确');
            return $this->failed('看板名称必填提示信息不正确');
        }
        return ($form->dom->kanbanName->getText() == $kanban->name)
            ? $this->success('编辑看板成功')
            : $this->failed('编辑看板失败');
    }
}
