<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class cardTester extends tester
{
    public function createCard($kanbanurl, $card)
    {
        $form = $this->initForm('kanban', 'view', $kanbanurl, 'appIframe-kanban');
        if (isset($card->name)) $form->dom->name->setValue($card->name);
        if ($form->dom->zin_kanban_createcard_form)
        {
            $nameTip = sprintf($this->lang->error->notempty, $this->lang->kanbancard->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('卡片必填提示信息正确')
                : $this->failed('卡片必填提示信息不正确');
        }
        return ($form->dom->firCardName->getText() == $card->name)
            ? $this->success('创建看板成功')
            : $this->failed('创建看板失败');
    }
}
