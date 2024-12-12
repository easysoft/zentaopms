<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createStakeholderTester extends tester
{
    /**
     * Create a stakeholder.
     * 创建干系人表单。
     *
     * @param  array  $stakeholder
     * @access public
     * @return object
     */
    public function createstakeholder(array $stakeholder)
    {
        $form = $this->initform('stakeholder', 'create', array('projecid' => 1), 'appiframe-project');
        /* 根据传的值来判断是否点击公司同事/外部人员单选按钮 */
        if(isset($stakeholder['type']))
        {
            $from = 'type' . $stakeholder['type'];
            $form->dom->$from->click();
            $form->wait(1);
        }
        /* 根据传的值来判断是否点击“是”单选按钮 */
        if(isset($stakeholder['key']))
        {
            $key = $stakeholder['key'];
            $form->dom->$key->click();
            $form->wait(1);
        }
        if(isset($stakeholder['user'])) $form->dom->user->setvalue($stakeholder['user']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->checkResult($stakeholder);
    }
}
