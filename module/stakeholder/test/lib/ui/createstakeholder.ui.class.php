<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
    public function createStakeholder(array $stakeholder)
    {
        $form = $this->initForm('stakeholder', 'create', array('projecID' => 1), 'appIframe-project');
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

    /**
     * Check the result after create the stakeholder.
     * 创建干系人后检查结果。
     *
     * @param  array $stakeholder
     * @access public
     * @return object
     */
    public function checkResult(array $stakeholder)
    {
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('stakeholder')) return $this->success('创建干系人表单页提示信息正确');
            return $this->failed('创建干系人表单页提示信息不正确');
        }

        /* 干系人详情页，检查干系人字段信息。*/
        $browsePage = $this->loadPage('stakeholder', 'browse');
        $browsePage->wait(2);
        $browsePage->dom->title->click();
        $viewPage = $this->loadPage('stakeholder', 'view');

        if(isset($stakeholder['type']))
        {
            $this->lang->stakeholder->fromList = (array)$this->lang->stakeholder->fromList;
            if($viewPage->dom->type->getText() != $this->lang->stakeholder->fromList[$stakeholder['type']]) return $this->failed('干系人类型错误');
        }
        if($viewPage->dom->name->getText() != $stakeholder['user']) return $this->failed('干系人姓名错误');
        return $this->success('创建干系人成功');
    }
}
