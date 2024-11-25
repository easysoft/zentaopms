<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class auditTester extends tester
{
    public function checkAudit($tabName, $expectNum)
    {
        $form = $this->initForm('my', 'audit', array(), 'appIframe-my');
        $form->wait(2);
        $tabDom = $tabName . 'Tab';
        $numDom = $tabName . 'Num';
        $tabMessage = [
            'all' => '全部',
            'SR'  => '研发需求',
            'UR'  => '用户需求',
            'ER'  => '业务需求'
        ];
        $form->dom->$tabDom->click();//切换tab
        $form->wait(1);
        $num = $form->dom->$numDom->getText();
        $form->wait(2);
        if (isset($tabMessage[$tabName]))
        {
            return ($num == $expectNum)
                ? $this->success("{$tabMessage[$tabName]}tab下数据显示正确")
                : $this->failed("{$tabMessage[$tabName]}tab下数据显示不正确");
        }
}
