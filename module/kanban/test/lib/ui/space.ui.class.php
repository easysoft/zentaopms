<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanbanspace->name);
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
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanbanspace->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('空间名称必填提示信息正确')
                : $this->failed('空间名称必填提示不正确');
        }
        return ($form->dom->spaceName->getText() == $space->name)
            ? $this->success('编辑空间成功')
            : $this->failed('编辑空间失败');
    }

    /**
     * 关闭空间
     * Close Space
     * @return mixed
     */
    public function closeSpace()
    {
        $form = $this->initForm('kanban', 'space', array(), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->showClosed)->click();
        $form->dom->btn($this->lang->kanban->setting)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->kanban->closeSpace)->click();
        $form->wait(2);
        $form->dom->btn($this->lang->save)->click();
        return ($form->dom->closed->getText() == $this->lang->kanban->closed)
            ? $this->success('关闭空间成功')
            : $this->failed('关闭空间失败');
    }

    /**
     * 激活空间
     * Active Space
     * @return mixed
     */
    public function activateSpace()
    {
        $form = $this->initForm('kanban', 'space', array(), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->setting)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->kanban->activateSpace)->click();
        $form->wait(2);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        if ($form->dom->closed)
        {
            return $this->failed('激活空间失败');
        }
        return $this->success('激活空间成功');
    }

    /**
     * 删除空间
     * Delete Space
     * @return mixed
     */
    public function deleteSpace()
    {
        $form = $this->initForm('kanban', 'space', array(), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->setting)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->kanban->deleteSpace)->click();
        $form->wait(1);
        $form->dom->confirm->click();
        $form->wait(1);
        return ($form->dom->involvedNum->getText() == '0')
            ? $this->success('删除空间成功')
            : $this->failed('删除空间失败');
    }

    /**
     * 切换tab
     * Switch Tab
     *
     * @param  $tabName   tab名 involved|cooperation|public|private
     * @param  $expectNum 预期数据
     * @return mixed
     */
    public function switchTab($tabName, $expectNum)
    {
        $form = $this->initForm('kanban', 'space', array(), 'appIframe-kanban');
        $form->wait(1);
        $tabDom = $tabName . 'Tab';
        $numDom = $tabName . 'Num';
        $tabMessage = [
            'involved'    => '我参与的',
            'cooperation' => '协作空间',
            'public'      => '公共空间',
            'private'     => '私人空间',
        ];
        $form->dom->$tabDom->click();
        $form->wait(1);
        $num = $form->dom->$numDom->getText();
        return ($num == $expectNum)
            ? $this->success("{$tabMessage[$tabName]}tab下数据显示正确")
            : $this->failed("{$tabMessage[$tabName]}tab下数据显示不正确");
    }
}
