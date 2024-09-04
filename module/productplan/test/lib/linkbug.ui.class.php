<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkBugTester extends tester
{
    /**
     * 关联Bug
     * link bug
     *
     * @param $planID
     * @return mixed
     */
    public function linkBug($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->dom->btn($this->lang->productplan->linkedBugs)->click();//进入计划bug列表页
        $form->dom->btn($this->lang->productplan->linkBug)->click();//点击关联Bug按钮
        $form->wait(1);
        $form->dom->selectAllBug->click();//全选Bug
        $form->dom->btn($this->lang->productplan->linkBug)->click();//点击关联Bug
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        return ($viewPage->dom->checkInfoBug === false) ? $this->failed('关联Bug失败') : $this->success('关联Bug成功');
    }
}
