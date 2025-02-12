<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
}
