<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editPlanTester extends tester
{
    /**
     * 编辑产品计划
     * Edit  productplan
     *
     * @param  object $productplan
     * @param  array  $planurl
     * @return mixed
     */
    public function editDefault($productplan, $planurl)
    {
        // 初始化编辑表单
        $form = $this->initForm('productplan', 'edit', $planurl, 'appIframe-product');
        // 设置表单字段值
        if (isset($productplan->title))  $form->dom->title->setValue($productplan->title);
        if (isset($productplan->begin))  $form->dom->begin->datePicker($productplan->begin);
        if (isset($productplan->future)) $form->dom->future->click();
        if (isset($productplan->end))    $form->dom->end->setValue($productplan->end);
        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(3);
        //调用方法校验计划是否编辑成功
        return $this->checkEdit($productplan, $planurl, $form);
    }

    /**
     * 检查编辑计划的结果
     * Check the result of edit productplan
     *
     * @param  object $productplan
     * @param  array  $planurl
     * @param  object $form
     * @return mixed
     */
    public function checkEdit($productplan, $planurl, $form)
    {
        //编辑失败时，校验提示信息是否正确
        if ($this->response('method') != 'view')
        {
            if ($this->checkFormTips('productplan')) return $this->success('编辑计划提示信息正确');
            if ($form->dom->endTip)
            {
                //检查结束日期小于开始日期
                $endTipform = $form->dom->endTip->getText();
                $endTip     = sprintf($this->lang->error->ge, $this->lang->productplan->end, $form->dom->begin->getValue());
                return ($endTipform == $endTip) ? $this->success('日期校验正确') : $this->failed('日期校验不正确');
            }
            return $this->failed('编辑计划提示信息不正确');
        }
        else
        {
            // 进入计划详情页
            $viewPage = $this->initForm('productplan', 'view', $planurl, 'appIframe-product');
            $viewPage->waitElement($this->lang->productplan->view);
            $viewPage->dom->btn($this->lang->productplan->view)->click();
            // 检查是否编辑为待定计划
            if ($productplan->future == 'future')
            {
                $planBegin = $viewPage->dom->begin->getText();
                return ($planBegin == $this->lang->productplan->future) ? $this->success('编辑为待定计划成功') : $this->failed('编辑为待定计划失败');
            }
            // 检查计划标题是否正确
            return ($viewPage->dom->planTitle->getText() == $productplan->title) ? $this->success('编辑计划成功') : $this->failed('编辑计划失败');
        }
    }
}
