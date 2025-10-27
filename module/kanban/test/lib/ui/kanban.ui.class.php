<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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

    /**
     * 关闭看板
     * Close kanban
     *
     * @return mixed
     */
    public function closeKanban()
    {
        $form = $this->initForm('kanban', 'space', ['browseType' => 'cooperation'], 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->close)->click();
        $form->wait(2);
        $form->dom->saveCloseBtn->click();
        $form->wait(2);
        return ($form->dom->kanbanStatus->getText() == $this->lang->kanban->closed)
            ? $this->success('关闭看板成功')
            : $this->failed('关闭看板失败');
    }

    /**
     * 激活看板
     * Activate kanban
     *
     * @return mixed
     */
    public function activateKanban()
    {
        $form = $this->initForm('kanban', 'space', ['browseType' => 'cooperation'], 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->activate)->click();
        $form->wait(2);
        $form->dom->saveActivateBtn->click();
        $form->wait(2);
        return ($form->dom->kanbanStatus->getText() != $this->lang->kanban->closed)
            ? $this->success('激活看板成功')
            : $this->failed('激活看板失败');
    }
}
