<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewTester extends tester
{
    /*
     * 看板列设置
     * set Column
     *
     * @param  $kanbanurl
     * @param  $column
     * @return mixed
     */
    public function setColumn($kanbanurl, $columnName)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->columnMoreBtn->click();
        $form->wait(2);
        $form->dom->setColumnBtn->click();
        $form->wait(2);
        if (isset($columnName)) $form->dom->name->setValue($columnName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        //校验创建结果
        if ($form->dom->zin_kanban_setcolumn_form)
        {
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanban->columnName);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('看板列名称必填提示信息正确')
                : $this->failed('看板列名称必填提示信息不正确');
        }
        return ($form->dom->firColumnName->getText() == $columnName)
            ? $this->success('看板列设置成功')
            : $this->failed('看板列设置失败');
    }
}