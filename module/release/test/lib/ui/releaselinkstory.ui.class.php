<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class releaseLinkStoryTester extends tester
{
    /**
     * Release link story.
     * 发布关联需求
     *
     * @param  array $release
     * @access public
     */
    public function linkStory()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->linkStoryBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->dom->selectAllStory->click(); // 点击全选按钮
        $form->dom->linkStoryBtnBottom->click();
        // 断言检查发布关联需求数量是否成功
        $viewPage = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        return ($viewPage->dom->finishedStoryNum === '0') ? $this->failed('发布关联需求失败') : $this->success('发布关联需求成功');
    }

    /**
     * Unlink a story of release.
     * 发布单个移除需求
     *
     * @access public
     * @return object
     */
    public function unlinkStory()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $linkNumBefore = $form->dom->finishedStoryNum->getText(); // 记录移除需求前发布关联的需求数量
        $form->dom->unlinkFirBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        $linkNumAfter = $form->dom->finishedStoryNum->getText(); // 记录移除需求后发布关联的需求数量
        // 断言检查单个移除需求是否成功
        return ($linkNumAfter == $linkNumBefore - 1) ? $this->success('单个移除需求成功') : $this->failed('单个移除需求失败');
    }

    /**
     * unlink all story of release.
     * 移除全部需求
     *
     * @return object
     */
    public function batchUnlinkStory()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->wait(1);
        $form->dom->allFinishedStoryBtn->click(); // 全选需求
        $form->dom->batchUnlinkBtn->click(); // 点击批量移除按钮
        $form->wait(2);
        // 断言检查移除全部需求是否成功
        return ($form->dom->finishedStoryNum === false) ? $this->success('批量移除需求成功') : $this->failed('批量移除需求失败');
    }
}
