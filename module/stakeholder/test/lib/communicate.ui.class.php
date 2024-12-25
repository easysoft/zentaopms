<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class communicateTester extends tester
{
    /**
     * Stakeholder communication records.
     * 干系人沟通记录。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function communicate($stakeholder)
    {
        $form = $this->initForm('stakeholder', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->communicate->click();
        $communicateForm = $this->loadPage('stakeholder', 'communicate');
        $form->wait(1);
        if(isset($stakeholder['comment'])) $communicateForm->dom->communication->setValueInZenEditor($stakeholder['comment']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->checkResult($stakeholder);
    }
}
