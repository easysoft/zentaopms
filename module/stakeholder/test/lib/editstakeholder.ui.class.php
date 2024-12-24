<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
}
