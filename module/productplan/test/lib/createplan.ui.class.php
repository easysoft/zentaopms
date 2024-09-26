<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createPlanTester extends tester
{
    /**
     * 创建产品计划
     * Create  productplan
     *
     * @param  object $productplan
     * @param  array  $planurl
     * @return mixed
     */
    public function createDefault($productplan, $planurl)
    {
        // 初始化创建表单
        $form = $this->initForm('productplan', 'create', $planurl, 'appIframe-product');

        // 设置表单字段值
        if (isset($productplan->parent)) $form->dom->parent->picker($productplan->parent);
        if (isset($productplan->title))  $form->dom->title->setValue($productplan->title);
        if (isset($productplan->begin))  $form->dom->begin->datePicker($productplan->begin);
        if (isset($productplan->future)) $form->dom->future->click();
        if (isset($productplan->end))    $form->dom->end->datePicker($productplan->end);

        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(3);
        //调用方法校验计划是否创建成功
        return $this->checkCreate($productplan, $planurl, $form);
    }

    /**
     * 检查产品计划的创建结果
     * Check the result of create productplan
     *
     * @param  object $productplan
     * @param  array  $planurl
     * @param  object $form
     * @return mixed
     */
    public function checkCreate($productplan, $planurl, $form)
    {
        //创建失败时，校验提示信息是否正确
        if ($this->response('method') != 'browse')
        {
            if ($this->checkFormTips('productplan')) return $this->success('创建计划表单页提示信息正确');
            if ($form->dom->endTip)
            {
                //检查结束日期小于开始日期
                $endTipform = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->error->ge, $this->lang->productplan->end, $form->dom->begin->getValue());
                return ($endTipform == $endTip) ? $this->success('日期校验正确') : $this->failed('日期校验不正确');
            }
            return $this->failed('创建计划表单页提示信息不正确');
        }
        else
        {
            // 加载产品计划列表页面
            $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
            //计划创建成功后的检查
            $browsePage->dom->search->click();
            $browsePage->dom->value1->setValue($productplan->title);
            $browsePage->dom->searchBtn->click();
            $browsePage->wait(2);
            // 获取搜索结果的第一个计划ID
            $planParam = array();
            $planParam['planID'] = $browsePage->dom->firstID->getText();

            // 进入计划详情页
            $viewPage = $this->initForm('productplan', 'view', $planParam, 'appIframe-product');
            $viewPage->waitElement($this->lang->productplan->view);
            $viewPage->dom->btn($this->lang->productplan->view)->click();

            // 检查是否成功创建子计划
            if (isset($productplan->parent))
            {
                $planname = $viewPage->dom->parent->getText();
                return strpos($planname, $productplan->parent) !== false ? $this->success('创建子计划成功') : $this->failed('创建子计划失败');
            }
            // 检查是否成功创建待定计划
            if ($productplan->future == 'future')
            {
                $planBegin = $viewPage->dom->begin->getText();
                return ($planBegin == $this->lang->productplan->future) ? $this->success('创建待定计划成功') : $this->failed('创建待定计划失败');
            }
            // 检查计划标题是否正确
            return ($viewPage->dom->planTitle->getText() == $productplan->title) ? $this->success('创建计划成功') : $this->failed('创建计划失败');
        }
    }
}
