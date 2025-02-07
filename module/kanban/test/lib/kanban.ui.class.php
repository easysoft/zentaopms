<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class kanbanTester extends tester
{
    public function createKanban($kanban)
    {
        $form = $this->initForm('kanban', 'space', ['browseType' => 'cooperation'], 'appIframe-kanban');
        $form->dom->createKanbanBtn->click();
        if (isset($kanban->name)) $form->dom->name->setValue($kanban->name);
        $form->dom->saveKanbanBtn->click();
        if ($form->dom->zin_kanban_create_formPanel)
        {
            if($this->checkFormTips('kanban')) return $this->success('看板名称必填提示信息正确');
            return $this->failed('看板名称必填提示信息不正确');
        }
        return ($form->dom->kanbanName->getText() == $kanban->name)
            ? $this->success('创建看板成功')
            : $this->failed('创建看板失败');
    }
}
