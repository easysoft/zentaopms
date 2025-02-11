<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class releaseLinkBugTester extends tester
{
    /**
     * Release link bug.
     * 发布关联bug
     *
     * @param  array $release
     * @access public
     */
    public function linkBug()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->resolvedBugTab->click();
        $form->dom->linkBugBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->dom->selectAllBug->click(); // 点击全选按钮
        $form->dom->linkBugBtnBottom->click();
        // 断言检查发布关联bug数量是否成功
        $viewPage = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->resolvedBugTab->click();
        $form->wait(2);
        return ($viewPage->dom->resolvedBugNum === '0') ? $this->failed('发布关联bug失败') : $this->success('发布关联bug成功');
    }

    /**
     * Unlink a bug of release.
     * 移除单个bug
     *
     * @access public
     * @return object
     */
    public function unlinkBug()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->resolvedBugTab->click();
        $form->wait(2);
        $linkNumBefore = $form->dom->resolvedBugNum->getText(); // 记录移除bug前发布关联的bug数量
        $form->dom->unlinkFirBugBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        $linkNumAfter = $form->dom->resolvedBugNum->getText(); // 记录移除bug后发布关联的bug数量
        // 断言检查单个移除bug是否成功
        return ($linkNumAfter == $linkNumBefore - 1) ? $this->success('单个移除bug成功') : $this->failed('单个移除bug失败');
    }

    /**
     * unlink all bug of release.
     * 移除全部bug
     *
     * @return object
     */
    public function batchUnlinkBug()
    {
        $form = $this->initForm('release', 'view', array('releaseID' => 1), 'appIframe-product');
        $form->dom->resolvedBugTab->click();
        $form->wait(1);
        $form->dom->allResolvedBugBtn->click(); // 全选bug
        $form->dom->batchUnlinkBugBtn->click(); // 点击批量移除按钮
        $form->wait(2);
        // 断言检查移除全部bug是否成功
        return ($form->dom->resolvedBugNum === false) ? $this->success('移除全部bug成功') : $this->failed('移除全部bug失败');
    }
}
