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
        $planNum = $form->dom->allNum->getText();//获取计划总数
        $form->wait(2);
        $form->dom->selectAllBtn->click();//全选计划
        $form->dom->batchStatusBtn->click();//点击状态
        $form->dom->$changeToDom->click();//批量修改为对应的状态
        $form->wait(1);
        $form->dom->$statusTabDom->click();//点击对应状态的tab
        $form->wait(2);
        $changeNum = $form->dom->$changeNumDom->getText();//获取修改后的计划数
        return($planNum == $changeNum) ? $this->success("批量变更为{$planStatus}状态成功") : $this->failed("批量变更为{$planStatus}状态失败");
    }

    public function batchClose($planurl)
    {
        $form = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $form->dom->allTab->click();//进入全部tab
        $planNum = $form->dom->allNum->getText();//获取计划总数
        $form->wait(2);
        $form->dom->selectAllBtn->click();//全选计划
        $form->dom->batchCloseBtn->click();//点击关闭
        $form->dom->btn($this->lang->save)->click();//保存
        $form->dom->closedTab->click();//点击已关闭tab
        $form->wait(2);
        $closeNum = $form->dom->closedNum->getText();//获取关闭后的计划数
        return($planNum == $closeNum) ? $this->success("批量关闭计划成功") : $this->failed("批量关闭计划失败");
    }
}
