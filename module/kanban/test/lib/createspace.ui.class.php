<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createSpaceTester extends tester
{
    public function createSpace($type, $space)
    {
        $form = $this->initForm('kanban', 'space', array('browseType' => $type), 'appIframe-kanban');
        $form->dom->btn($this->lang->kanban->createSpace)->click();
        if (isset($space->name))  $form->dom->name->setValue($space->name);
        if (isset($space->owner)) $form->dom->owner->picker($space->owner);
        $form->dom->btn($this->lang->save)->click();//保存
        if ($form->dom->zin_kanban_createspace_formPanel)
        {
            $nameTip = sprintf($this->lang->error->notempty,$this->lang->kanbanspace->name);
            return ($form->dom->nameTip->getText() == $nameTip)
                ? $this->success('空间名称必填提示信息正确')
                : $this->failed('空间名称必填提示不正确');
        }
    }
}
