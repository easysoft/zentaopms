<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkStoryTester extends tester
{
    /**
     * 检查当执行未关联产品时关联需求的提示信息
     * Check info of linkstory when the execution linked no product.
     *
     * @access public
     * @return object
     */
    public function checkNoProductInfo()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '3'), 'appIframe-execution');
        $form->dom->btn($this->lang->execution->linkStory)->click();
        $form->wait(1);
        if($form->dom->alertModal('text') == $this->lang->execution->errorNoLinkedProducts) return $this->success('执行未关联产品时提示正确');
        return $this->failed('执行未关联产品时提示不正确');
    }

    /**
     * 关联需求
     * Link story
     *
     * @access public
     * @return object
     */
    public function linkStory()
    {
        $form = $this->initForm('execution', 'linkstory', array('execution' => '2'), 'appIframe-execution');
        $name = $form->dom->firstName->getText();
        $form->dom->firstCheckbox->click();
        $form->dom->saveBtn->click();
        $form->wait(1);

        $storyView = $this->loadPage('execution', 'story');
        $storyView->dom->search(array("{$this->lang->story->name},=,{$name}"));
        $storyView->wait(1);
        if($storyView->dom->firstName->getText() == $name) return $this->success('关联需求成功');
        return $this->failed('关联需求失败');
    }
}
