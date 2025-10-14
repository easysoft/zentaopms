<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editStakeholderTester extends tester
{
    /**
     * Edit a stakeholder.
     * 编辑干系人表单。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function editStakeholder(array $stakeholder)
    {
        $form = $this->initForm('stakeholder', 'edit', array('id' => 1), 'appIframe-project');
        if(isset($stakeholder['key']))            $form->dom->key->click();
        if(isset($stakeholder['personality']))    $form->dom->personality->setValueInZenEditor($stakeholder['personality']);
        if(isset($stakeholder['impactAnalysis'])) $form->dom->impactAnalysis->setValueInZenEditor($stakeholder['impactAnalysis']);
        if(isset($stakeholder['response']))       $form->dom->response->setValueInZenEditor($stakeholder['response']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->checkResult($stakeholder);
    }

    /**
     * Check the result after edit the stakeholder.
     * 编辑干系人后检查结果。
     *
     * @param  array $stakeholder
     * @access public
     * @return object
     */
    public function checkResult(array $stakeholder)
    {
        /* 干系人详情页，检查干系人字段信息。*/
        $browsePage = $this->loadPage('stakeholder', 'browse');
        $browsePage->wait(1);
        $browsePage->dom->title->click();
        $viewPage = $this->loadPage('stakeholder', 'view');
        $viewPage->wait(1);

        if($viewPage->dom->key->getText() != $stakeholder['key'])                       return $this->failed('关键干系人信息错误');
        if($viewPage->dom->personality->getText() != $stakeholder['personality'])       return $this->failed('性格特征信息错误');
        if($viewPage->dom->impactAnalysis->getText() != $stakeholder['impactAnalysis']) return $this->failed('影响分析信息错误');
        if($viewPage->dom->response->getText() != $stakeholder['response'])             return $this->failed('应对策略信息错误');
        return $this->success('编辑干系人成功');
    }
}
