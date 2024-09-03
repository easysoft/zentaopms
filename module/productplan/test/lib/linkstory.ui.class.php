<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkStoryTester extends tester
{
    /**
     * 关联需求
     * link story
     *
     * @param $planID
     * @return mixed
     */
    public function linkStory($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->dom->btn($this->lang->productplan->linkStory)->click();//点击关联需求按钮
        $form->wait(1);
        $form->dom->selectAllStory->click();//全选需求
        $form->dom->btn($this->lang->productplan->linkStory)->click();//点击关联需求
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        return ($viewPage->dom->checkInfoStory === false) ? $this->failed('关联需求失败') : $this->success('关联需求成功');
    }

    /**
     * 移除单个需求
     * unlink story
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkStory($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $linkNum = (int) explode(' ', $form->dom->storyLinkNum->getText())[1];//计划当前关联的需求数
        $form->dom->unlinkFirStory->click();//移除第一个需求
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(1);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $linkNumAfter = (int) explode(' ', $viewPage->dom->storyLinkNum->getText())[1];//计划移除需求后，关联的需求数
        return ($linkNum -1 == $linkNumAfter) ? $this->success('移除单个需求成功') : $this->failed('移除单个需求失败');
    }

    /**
     * 移除全部需求
     * unlink allstory
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkAllStory($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->wait(1);
        $form->dom->allLinkedStory->click();//全选需求
        $form->dom->btn($this->lang->productplan->unlinkStoryAB)->click();//点击移除
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        return ($viewPage->dom->checkInfoStory === false) ? $this->success('移除全部需求成功') : $this->failed('移除全部需求失败');
    }
    /**
     * 移除单个需求
     * unlink story
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkStory($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $linkNum = (int) explode(' ', $form->dom->linkNum->getText())[1];//计划当前关联的需求数
        $form->dom->unlinkFirBtn->click();//移除第一个需求
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(1);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $linkNumAfter = (int) explode(' ', $viewPage->dom->linkNum->getText())[1];//计划移除需求后，关联的需求数
        return ($linkNum -1 ==$linkNumAfter) ? $this->success('移除单个需求成功') : $this->failed('移除单个需求失败');
    }
    /**
     * 移除全部需求
     * unlink allstory
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkAllStory($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->wait(1);
        $form->dom->selectAllLink->click();//全选需求
        $form->dom->btn($this->lang->productplan->unlinkStoryAB)->click();//点击移除
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        return ($viewPage->dom->checkInfo === false) ? $this->success('移除全部需求成功') : $this->failed('移除全部需求失败');
    }
}
