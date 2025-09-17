<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class auditTester extends tester
{
    /**
     * 检查地盘审批下的数据
     * check data of audit
     *
     * @param  $tabName   tab名称 all|SR|UR|ER
     * @param  $expectNum 预期数据
     * @return mixed
     */
    public function checkAudit($tabName, $expectNum)
    {
        $form = $this->initForm('my', 'audit', array(), 'appIframe-my');
        $form->wait(3);
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

    /**
     * 在审批列表中评审
     * review in audit list
     *
     * @param  $type       类型 SR|UR|ER
     * @param  $expectNum  预期数据
     * @return mixed
     */
    public function review($type, $expectNum)
    {
        $form = $this->initForm('my', 'audit', array(), 'appIframe-my');
        $form->wait(2);
        $typeDom = $type . 'Tab';
        $numDom  = $type . 'Num';
        $typeMessage = [
            'SR'  => '研发需求',
            'UR'  => '用户需求',
            'ER'  => '业务需求'
        ];
        $form->dom->$typeDom->click();
        $form->wait(2);
        $form->dom->reviewBtn->click();
        $form->wait(2);
        $form->dom->btn($this->lang->story->review)->click();
        $form->wait(2);
        $form->dom->$typeDom->click();
        $form->wait(1);
        $num = $form->dom->$numDom->getText();
        return ($num == $expectNum)
            ? $this->success("{$typeMessage[$type]}评审成功")
            : $this->failed("{$typeMessage[$type]}评审不成功");
    }
}
