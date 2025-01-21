<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class expectTester extends tester
{
    /**
     * Stakeholder  expectation records.
     * 干系人期望记录。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function expect($stakeholder)
    {
        $form = $this->initForm('stakeholder', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->expect->click();
        $expectForm = $this->loadPage('stakeholder', 'expect');
        $form->wait(1);
        if(isset($stakeholder['expectComment'])) $expectForm->dom->expectComment->setValueInZenEditor($stakeholder['expectComment']);
        if(isset($stakeholder['progress']))      $expectForm->dom->progress->setValueInZenEditor($stakeholder['progress']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->checkResult($expectForm, $stakeholder);
    }
}
