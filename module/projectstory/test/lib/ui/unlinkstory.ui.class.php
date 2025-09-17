<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class unLinkStoryTester extends tester
{
    /**
     * Unlink a story of project.
     * 项目单个移除需求
     *
     * @access public
     * @return object
     */
    public function unlinkStory()
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => 1), 'appIframe-project');
        $form->dom->allTab->click();
        $unlinkNumBefore = $form->dom->allTabNum->getText(); // 记录移除需求前项目下的需求数量
        $form->dom->unlinkFirBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        $unlinkNumAfter = $form->dom->allTabNum->getText(); // 记录移除需求后版本关联的需求数量
        // 断言检查单个移除需求是否成功
        return ($unlinkNumAfter == $unlinkNumBefore - 1) ? $this->success('单个移除需求成功') : $this->failed('单个移除需求失败');
    }

    /**
     * unlink all story of project.
     * 移除全部需求
     *
     * @return object
     */
    public function batchUnlinkStory()
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => 1), 'appIframe-project');
        $form->dom->allTab->click();
        $form->dom->selectAllBtn->click(); // 全选需求
        $form->wait(2);
        $form->dom->batchUnlinkBtn->click(); // 点击批量移除按钮
        $form->wait(2);
        // 断言检查移除全部需求是否成功
        return ($form->dom->allTabNum->getText() === '0') ? $this->success('移除全部需求成功') : $this->failed('移除全部需求失败');
    }
}
