<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class workTester extends tester
{
    /**
     * 检查地盘待处理下的数据
     * check data of work
     *
     * @param $type      数据类型 task|SR|UR|ER|bug|case|request
     * @param $tabName   tab名称  firstTab|secondTab
     * @param $expectNum 预期数据
     * @return mixed
     */
    public function checkWork($type, $tabName, $expectNum)
    {
        $form = $this->initForm('my', 'work', array(), 'appIframe-my');
        $form->wait(2);
        $numDom = $tabName . 'Num';
        $typeMessage = [
            'task'    => '任务',
            'SR'      => '研发需求',
            'UR'      => '用户需求',
            'ER'      => '业务需求',
            'bug'     => 'Bug',
            'case'    => '用例',
            'request' => '测试单'
        ];
        $tabMessage = [
            'firstTab'  => '指派给我tab',
            'secondTab' => '待我评审tab'
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
