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
        $form->wait(2);
        $form->dom->selectAllBtn->click();//全选计划
        $form->dom->batchEditBtn->click();//点击编辑按钮
        $firstID = $form->dom->idIndex_static_0->getText();//获取第一个ID
        $beginInputDom = "begin[{$firstID}]";
        $endInputDom   = "end[{$firstID}]";
        //设置表单字段
        if (isset($productplan->title))  $form->dom->title_0->setValue($productplan->title);
        if (isset($productplan->begin))  $form->dom->$beginInputDom->datePicker($productplan->begin);
        if (isset($productplan->end))    $form->dom->$endInputDom->datePicker($productplan->end);
        $form->dom->btn($this->lang->save)->click();//保存
        $form->wait(2);
        return $this->checkBatchEdit($productplan, $form, $firstID);
    }
}
