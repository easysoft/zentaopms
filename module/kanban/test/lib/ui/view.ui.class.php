<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class viewTester extends tester
{
    /*
     * 看板列设置
     * set Column
     *
     * @param  $kanbanurl
     * @param  $columnName
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

    /*
     * 修改泳道名
     * Edit LaneName
     *
     * @param  $kanbanurl
     * @param  $laneName
     * @return mixed
     */
    public function editLaneName($kanbanurl, $laneName)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->laneMoreBtn->click();
        $form->wait(2);
        $form->dom->editLaneNameBtn->click();
        $form->wait(2);
        if (isset($laneName)) $form->dom->name->setValue($laneName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        if ($form->dom->zin_kanban_editlanename_form)
        {
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanban->laneName);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('泳道名称必填提示信息正确')
                : $this->failed('泳道名称必填提示信息不正确');
        }
        return ($form->dom->firLaneName->getText() == $laneName)
            ? $this->success('泳道名称修改成功')
            : $this->failed('泳道名称修改失败');
    }

    /*
     * 修改泳道背景色
     * Edit Lane Color
     *
     * @param  $kanbanurl
     * @return mixed
     */
    public function editLaneColor($kanbanurl)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->laneMoreBtn->click();
        $form->wait(2);
        $form->dom->editLaneColorBtn->click();
        $form->wait(2);
        $form->dom->firColor->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        if ($form->dom->zin_kanban_editlanecolor_form)
        {
            return $this->failed('修改泳道背景色失败');
        }
        $style = $form->dom->firLane->attr('style');
        preg_match('/--kanban-lane-color:\s*([^;]+)/', $style, $matches);
        $colorValue = isset($matches[1]) ? trim($matches[1]) : null;
        return ($colorValue == '#3C4353')
            ? $this->success('泳道背景色修改成功')
            : $this->failed('泳道背景色修改失败');
    }
}
