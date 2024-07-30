<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';

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
        //遍历statusList,找到[进行中]的语言项
        foreach($this->lang->productplan->statusList as $key => $status)
        {
            if ($key === 'doing')
            {
                $doing = $status;
                break;
            }
        }
        //判断详情页中的状态是否为[进行中]
        if($viewPage->dom->status->getText() == $doing)
        {
            return $this->success('开始计划成功');
        }
        return $this->failed('开始计划失败');
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
        //遍历statusList,找到[已完成]的语言项
        foreach($this->lang->productplan->statusList as $key => $status)
        {
            if ($key === 'done')
            {
                $done = $status;
                break;
            }
        }
        //判断详情页中的状态是否为[已完成]
        if($viewPage->dom->status->getText() == $done)
        {
            return $this->success('完成计划成功');
        }
        return $this->failed('完成计划失败');
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
        //遍历statusList,找到[已关闭]的语言项
        foreach($this->lang->productplan->statusList as $key => $status)
        {
            if ($key === 'closed')
            {
                $closed = $status;
                break;
            }
        }
        //判断详情页中的状态是否为[已关闭]
        if($viewPage->dom->status->getText() == $closed)
        {
            return $this->success('关闭计划成功');
        }
        return $this->failed('关闭计划失败');
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
        //遍历statusList,找到[进行中]的语言项
        foreach($this->lang->productplan->statusList as $key => $status)
        {
            if ($key === 'doing')
            {
                $active = $status;
                break;
            }
        }
        //判断详情页中的状态是否为[已激活]
        if($viewPage->dom->status->getText() == $active)
        {
            return $this->success('激活计划成功');
        }
        return $this->failed('激活计划失败');
    }
}
