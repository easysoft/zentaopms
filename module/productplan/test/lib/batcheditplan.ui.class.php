<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditPlanTester extends tester
{
    /**
     * 批量编辑产品计划
     * Batch edit  productplan
     *
     * @param  object $productplan
     * @param  array  $planurl
     * @return mixed
     */
    public function batchEditPlan($productplan, $planurl)
    {
        $form = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $form->wait(1);
        $form->dom->selectAllBtn->click();//全选计划
        $form->wait(1);
        $form->dom->batchEditBtn->click();//点击编辑按钮
        $firstID = $form->dom->idIndex_static_0->getText();//获取第一个ID
        $beginInputDom = "begin[{$firstID}]";
        $endInputDom   = "end[{$firstID}]";
        //设置表单字段
        if (isset($productplan->title))  $form->dom->title_0->setValue($productplan->title);
        if (isset($productplan->begin))  $form->dom->$beginInputDom->datePicker($productplan->begin);
        if (isset($productplan->end))    $form->dom->$endInputDom->datePicker($productplan->end);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(2);
        return $this->checkBatchEdit($productplan, $form, $firstID);
    }
    /**
     * 检查批量编辑计划的结果
     * Check the result of batch edit productplan
     *
     * @param  object $productplan
     * @param  object $form
     * @param  string $firstID
     * @return mixed
     */
    public function checkBatchEdit($productplan, $form, $firstID)
    {
        //编辑失败时校验错误提示信息
        if ($this->response('method') != 'browse')
        {
            $titleTip = sprintf($this->lang->error->notempty, $this->lang->productplan->title);
            $firstTitleTipDom = "title[{$firstID}]Tip";//第一行的名称提示信息
            $firstBeginTipDom = "begin[{$firstID}]Tip";//第一行的日期校验提示信息
            if ($form->dom->$firstBeginTipDom)
            {
                $beginTipform = $form->dom->$firstBeginTipDom->getText();
                $beginTip     = sprintf($this->lang->productplan->beginGeEnd, $firstID);
                return ($beginTipform == $beginTip) ? $this->success('日期校验提示信息正确') : $this->failed('日期校验提示信息不正确');
            }
            $titleTipform = $form->dom->$firstTitleTipDom->getText();
            return ($titleTipform == $titleTip) ? $this->success('计划名称必填提示信息正确') : $this->failed('计划名称提示信息不正确');
        }
        else
        {
            $viewUrl['planID'] = $firstID;
            $viewPage = $this->initForm('productplan', 'view', $viewUrl, 'appIframe-product');
            $viewPage->waitElement($this->lang->productplan->view);
            $viewPage->dom->btn($this->lang->productplan->view)->click();//进入计划详情页
            $viewPage->wait(1);
            return ($viewPage->dom->planTitle->getText() == $productplan->title) ? $this->success('编辑计划成功') : $this->failed('编辑计划失败');
        }
    }
}
