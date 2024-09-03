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
        $form->dom->selectAll->click();//全选需求
        $form->dom->btn($this->lang->productplan->linkStory)->click();//点击关联需求
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        return ($viewPage->dom->checkInfo === false) ? $this->failed('关联需求失败') : $this->success('关联需求成功');
    }
}
