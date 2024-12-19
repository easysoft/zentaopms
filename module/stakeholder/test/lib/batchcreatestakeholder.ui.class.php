<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateStakeholderTester extends tester
{
    /**
     * Batch create stakeholder by copy from department.
     * 通过复制部门人员来批量创建干系人。
     *
     * @param  string  $expectNum
     * @access public
     * @return object
     */
    public function copyFromDept($expectNum)
    {
        $form = $this->initForm('stakeholder', 'batchCreate', array('projecID' => 2), 'appIframe-project');
        $form->wait(1);
        $form->dom->selectDept->picker('研发部');
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        /* 干系人列表检查干系人数 */
        $browsePage     = $this->loadPage('stakeholder', 'browse');
        $string         = $browsePage->dom->number->getText();
        $stakeholderNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($stakeholderNum == $expectNum) return $this->success('批量创建干系人成功');
        return $this->failed('批量创建干系人失败');
    }

    /**
     * Batch create stakeholder by import from parent program.
     * 通过从父项目集导入来批量创建干系人。
     *
     * @param  string  $expectNum
     * @access public
     * @return object
     */
    public function importFromProgram($expectNum)
    {
        $form = $this->initForm('stakeholder', 'batchCreate', array('projecID' => 2), 'appIframe-project');
        $form->dom->importBtn->click();
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        /* 干系人列表检查干系人数 */
        $browsePage     = $this->loadPage('stakeholder', 'browse');
        $string         = $browsePage->dom->number->getText();
        $stakeholderNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($stakeholderNum == $expectNum) return $this->success('批量创建干系人成功');
        return $this->failed('批量创建干系人失败');
    }
}
