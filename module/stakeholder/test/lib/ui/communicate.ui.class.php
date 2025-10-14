<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $communicateForm->wait(1);
        $communicateForm->dom->submitBtn->click();
        $communicateForm->wait(1);
        return $this->checkResult($stakeholder);
    }

    /**
     * Check the communication records on the stakeholder view page.
     * 检查干系人详情页沟通记录。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function checkResult($stakeholder)
    {
        /* 干系人详情页，检查沟通记录信息。*/
        $browsePage = $this->loadPage('stakeholder', 'browse');
        $browsePage->wait(1);
        $browsePage->dom->title->click();
        $viewPage = $this->loadPage('stakeholder', 'view');
        $viewPage->wait(1);

        if($viewPage->dom->communication->getText() != $stakeholder['comment']) return $this->failed('沟通记录信息错误');
        return $this->success('沟通记录保存成功');
    }
}
