<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchChangeStatusTester extends tester
{
    /**
     * 批量修改计划状态
     * batch change plan status
     *
     * @param $planStatus 计划状态
     * @param $planurl 产品ID
     *
     * @return mixed
     */
    public function batchChangeStatus($planStatus, $planurl)
    {
        $changeToDom  = $planStatus.'Status';
        $statusTabDom = $planStatus.'Tab';
        $changeNumDom = $planStatus.'Num';
        $form = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $form->dom->allTab->click();//进入全部tab
