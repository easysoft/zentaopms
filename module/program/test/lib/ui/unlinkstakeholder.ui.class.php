<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class unlinkStakeholderTester extends tester
{
    /**
     * Unlink a stakeholder of program.
     * 单个移除干系人
     *
     * @access public
     * @return object
     */
    public function unlinkStakeholder()
    {
        $form = $this->initForm('program', 'stakeholder', array('programID' => 1), 'appIframe-program');
        $linkNumBefore = $form->dom->stakeholderNum->getText();
        $form->dom->unlinkFirBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        $linkNumAfter = $form->dom->stakeholderNum->getText(); // 移除干系人后数量
        return ($linkNumAfter == $linkNumBefore - 1) ? $this->success('单个移除干系人成功') : $this->failed('单个移除干系人失败');
    }

    /**
     * Batch unlink stakeholder of program.
     * 批量移除全部干系人
     *
     * @access public
     * @return object
     */
    public function batchUnlinkStakeholders()
    {
        $form = $this->initForm('program', 'stakeholder', array('programID' => 1), 'appIframe-program');
        $form->dom->selectAllBtn->click(); // 全选干系人
        $form->dom->batchUnlinkBtn->click(); // 点击批量移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        return ($form->dom->stakeholderNum === false) ? $this->success('批量移除干系人成功') : $this->failed('批量移除干系人失败');
        }
}
