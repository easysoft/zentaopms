<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class cardTester extends tester
{
    /*
     * 创建卡片
     * Create card
     *
     * @param array  $kanbanurl
     * @param object $card
     *
     * @return mixed
     */
    public function createCard($kanbanurl, $card)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->createBtn->click();
        $form->wait(1);
        $form->dom->btn($this->lang->kanban->createCard)->click();
        $form->wait(1);

        //填写卡片信息
        if (isset($card->name)) $form->dom->name->setValue($card->name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        //校验创建结果
        if ($form->dom->zin_kanban_createcard_form)
        {
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanbancard->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('卡片必填提示信息正确')
                : $this->failed('卡片必填提示信息不正确');
        }
        return ($form->dom->firCardName->getText() == $card->name)
            ? $this->success('创建卡片成功')
            : $this->failed('创建卡片失败');
    }

    /*
     * 编辑卡片
     * Edit card
     *
     * @param array  $kanbanurl
     * @param object $card
     *
     * @return mixed
     */
    public function editCard($kanbanurl, $card)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->editCard)->click();
        $form->wait(1);

        //填写卡片信息
        if (isset($card->name))  $form->dom->name->setValue($card->name);
        if (isset($card->begin)) $form->dom->begin->datePicker($card->begin);
        if (isset($card->end))   $form->dom->end->datePicker($card->end);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        //校验编辑结果
        if ($form->dom->zin_kanban_editcard_1_form)
        {
            if($form->dom->nameTip)
            {
                $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanbancard->name);
                return ($form->dom->nameTip->getText() == $nameTip)
                    ? $this->success('卡片名称必填提示正确')
                    : $this->failed('卡片名称必填提示不正确');
            }
            return ($form->dom->endTip->getText() == $this->lang->kanbancard->error->endSmall)
                ? $this->success('卡片日期校验提示正确')
                : $this->failed('卡片日期校验提示不正确');
        }
        return ($form->dom->firCardName->getText() == $card->name)
            ? $this->success('编辑卡片成功')
            : $this->failed('编辑卡片失败');
    }

    /*
     * 完成卡片
     * Finish card
     *
     * @param  array $kanbanurl
     * @return mixed
     */
    public function finishCard($kanbanurl)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->finishCard)->click();
        $form->wait(2);
        return ($form->dom->progressNum->getText() == '100.00%')
            ? $this->success('完成卡片成功')
            : $this->failed('完成卡片失败');
    }

    /*
     * 激活卡片
     * Activate card
     *
     * @param array  $kanbanurl 看板ID
     * @param string $progress  进度
     *
     * @return mixed
     */
    public function activateCard($kanbanurl, $progress)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        $form->dom->moreBtn->click();
        $form->dom->btn($this->lang->kanban->activateCard)->click();
        $form->wait(1);
        if (isset($progress)) $form->dom->progress->setValue($progress);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        //校验激活结果
        if ($form->dom->zin_kanban_activatecard_form)
        {
            $progressTip = $this->lang->kanbancard->error->progressIllegal;
            return ($form->dom->progressTip->getText() == $progressTip)
                ? $this->success('激活卡片提示信息正确')
                : $this->failed('激活卡片提示信息不正确');
        }
        return ($form->dom->progressNum->getText() == $progress . '%')
            ? $this->success('激活卡片成功')
            : $this->failed('激活卡片失败');
    }
}
