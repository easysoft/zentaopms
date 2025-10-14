<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class contributeTester extends tester
{
    /**
     * 检查地盘贡献下的数据
     * check data of contribute
     *
     * @param $type      数据类型 task|SR|UR|ER|bug|case|request|review
     * @param $tabName   tab名称  firstTab|secondTab|thirdTab|fourthTab|fifthTab|sixthTab
     * @param $expectNum 预期数据
     * @return mixed
     */
    public function checkContribute($type, $tabName, $expectNum)
    {
        $form = $this->initForm('my', 'contribute', array(), 'appIframe-my');
        $form->wait(2);
        $numDom = $tabName . 'Num';
        $typeMessage = [
            'task'    => '任务',
            'SR'      => '研发需求',
            'UR'      => '用户需求',
            'ER'      => '业务需求',
            'bug'     => 'Bug',
            'case'    => '用例',
            'request' => '测试单',
            'review'  => '审批'
        ];
        $tabMessage = [
            'firstTab'  => '第1个tab',
            'secondTab' => '第2个tab',
            'thirdTab'  => '第3个tab',
            'fourthTab' => '第4个tab',
            'fifthTab'  => '第5个tab',
            'sixthTab'  => '第6个tab'
        ];
        $form->dom->$type->click();//点击3级导航中对应的数据类型
        $form->wait(1);
        $form->dom->$tabName->click();//点击对应的tab
        $form->wait(1);
        $num = $form->dom->$numDom->getText();
        $form->wait(2);
        if (isset($typeMessage[$type]) && isset($tabMessage[$tabName]))
        {
            return ($num == $expectNum)
                ? $this->success("{$typeMessage[$type]}的{$tabMessage[$tabName]}下数据显示正确")
                : $this->failed("{$typeMessage[$type]}的{$tabMessage[$tabName]}下数据显示不正确");
        }
    }
}
