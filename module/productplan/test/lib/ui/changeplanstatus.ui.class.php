<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';

class changePlanStatus extends tester
{
    /**
     * 开始计划
     * start plan
     *
     * @param $planID 计划ID
     * @return mixed
     */
    public function startPlan($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //点击开始计划按钮
        $form->dom->btn($this->lang->productplan->start)->click();
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(3);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //进入计划详情页
        $form->dom->btn($this->lang->productplan->view)->click();
        $form->wait(2);
        $doing = $this->lang->productplan->statusList->doing;//进行中的语言项
        //判断详情页中的状态是否为[进行中]
        return ($viewPage->dom->status->getText() == $doing) ? $this->success('开始计划成功') : $this->failed('开始计划失败');
    }

    /**
     * 完成计划
     * finish plan
     *
     * @param $planID 计划ID
     * @return mixed
     */
    public function finishPlan($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //点击完成计划按钮
        $form->dom->btn($this->lang->productplan->finish)->click();
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(3);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //进入计划详情页
        $form->dom->btn($this->lang->productplan->view)->click();
        $form->wait(2);
        $done = $this->lang->productplan->statusList->done;//已完成的语言项
        //判断详情页中的状态是否为[已完成]
        return ($viewPage->dom->status->getText() == $done) ? $this->success('完成计划成功') : $this->failed('完成计划失败');
    }

    /*
     * 关闭计划
     * close plan
     *
     * @param $planID 计划ID
     * @return mixed
     */
    public function closePlan($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //点击关闭计划按钮
        $form->dom->btn($this->lang->productplan->close)->click();
        $form->wait(1);
        $form->dom->btn($this->lang->productplan->closeAB)->click();//弹窗中点关闭
        $form->wait(3);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //进入计划详情页
        $form->dom->btn($this->lang->productplan->view)->click();
        $form->wait(2);
        $closed = $this->lang->productplan->statusList->closed;//已关闭的语言项
        //判断详情页中的状态是否为[已关闭]
        return ($viewPage->dom->status->getText() == $closed) ? $this->success('关闭计划成功') : $this->failed('关闭计划失败');
    }

    /*
     * 激活计划
     * active plan
     *
     * @param $planID 计划ID
     * @return mixed
     */
    public function activePlan($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //点击激活计划按钮
        $form->dom->btn($this->lang->productplan->activate)->click();
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(3);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        //进入计划详情页
        $form->dom->btn($this->lang->productplan->view)->click();
        $form->wait(2);
        $active = $this->lang->productplan->statusList->doing;//进行中的语言项
        //判断详情页中的状态是否为[进行中]
        return ($viewPage->dom->status->getText() == $active) ? $this->success('激活计划成功') : $this->failed('激活计划失败');
    }

    /**
     * 删除计划
     * delete plan
     *
     * @param $planID 计划ID
     * @return mixed
     */
    public function deletePlan($planID)
    {
        $form = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->dom->btn($this->lang->delete)->click();//点击删除按钮
        $form->wait(1);
        $form->dom->confirm->click();//弹窗中点确认
        $form->wait(1);
        $viewPage = $this->initForm('productplan', 'view', $planID, 'appIframe-product');
        $form->wait(1);
        $deleted = $this->lang->productplan->deleted;//已删除的语言项
        //判断详情页是否有[已删除]的标签
        return ($viewPage->dom->delTag->getText() == $deleted) ? $this->success('删除计划成功') : $this->failed('删除计划失败');
    }
}
