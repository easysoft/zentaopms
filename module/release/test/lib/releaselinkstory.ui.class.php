<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
