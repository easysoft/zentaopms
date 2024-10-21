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

    /**
     * 移除单个Bug
     * unlink bug
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkBug($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->dom->btn($this->lang->productplan->linkedBugs)->click();//进入计划bug列表页
        $linkNum = (int) explode(' ', $form->dom->bugLinkNum->getText())[1];//计划当前关联的Bug数
        $form->dom->unlikFirBug->click();//移除第一个Bug
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(1);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $viewPage->dom->btn($this->lang->productplan->linkedBugs)->click();//进入计划bug列表页
        $linkNumAfter = (int) explode(' ', $viewPage->dom->bugLinkNum->getText())[1];//计划移bug后，关联的bug数
        return ($linkNum -1 == $linkNumAfter) ? $this->success('移除单个Bug成功') : $this->failed('移除单个Bug失败');
    }

    /**
     * 移除全部bug
     * unlink allbug
     *
     * @param $planID
     * @return mixed
     */
    public function unLinkAllBug($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->dom->btn($this->lang->productplan->linkedBugs)->click();//进入计划bug列表页
        $form->wait(1);
        $form->dom->allLinkedBug->click();//全选bug
        $form->dom->btn($this->lang->productplan->unlinkAB)->click();//点击移除
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $viewPage->dom->btn($this->lang->productplan->linkedBugs)->click();//进入计划bug列表页
        return ($viewPage->dom->checkInfoBug === false) ? $this->success('移除全部Bug成功') : $this->failed('移除全部Bug失败');
    }
}
