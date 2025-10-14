<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class switchTab extends tester
{
    /**
     * 运营界面目标列表切换Tab
     * switch storylist tab in lite
     *
     * @param $projectID 项目id
     * @param $tabName   tab名称 allTab|openTab|draftTab|reviewingTab|changingTab
     * @param $expectNum 预期数据
     * @return mixed
     */
    public function switchTab($projectID, $tabName, $expectNum)
    {
        $this->switchVision('lite', 5);
        $form    = $this->initForm('projectstory', 'story', $projectID, 'appIframe-project');
        $numDom  = $tabName . 'Num';
        $tabLang = [
            'allTab'       => '全部',
            'openTab'      => '未关闭',
            'draftTab'     => '草稿',
            'reviewingTab' => '评审中',
            'changingTab'  => '变更中'
        ];
        $form->wait(1);
        $form->dom->$tabName->click();
        $form->wait(1);
        $num = $form->dom->$numDom->getText();
        return ($num == $expectNum)
            ? $this->success("{$tabLang[$tabName]}Tab下数据显示正确")
            : $this->failed("{$tabLang[$tabName]}Tab下数据显示不正确");
    }
}
