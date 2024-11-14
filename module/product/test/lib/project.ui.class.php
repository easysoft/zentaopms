<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class projectTester extends tester
{
    /**
     * 切换标签
     * switch tab
     *
     * @param $projecturl url
     * @param $tabName    tab名称
     * @param $expected   tab下项目数
     * @return mixed
     */
    public function switchTab($projecturl, $tabName, $expected)
    {
        $tabDom      = $tabName . 'Tab';
        $tabNumDom   = $tabName . 'Num';
        $projectPage = $this->initForm('product', 'project', $projecturl, 'appIframe-product');
        $projectPage->dom->$tabDom->click();//点击对应的tab
        $projectPage->wait(1);
        $num = $projectPage->dom->$tabNumDom->getText();//获取对应tab下项目数
        return($num == $expected)
            ? $this->success($tabName . '标签下项目数显示正确')
            : $this->failed($tabName . '标签下项目数显示不正确');
    }

    /*
     * 关联项目
     * link project
     *
     * @param $projecturl      url
     * @param $expectedProject 项目名称
     * @return mixed
     */
    public function linkProject($projecturl, $expectedProject)
    {
        $projectPage = $this->initForm('product', 'project', $projecturl, 'appIframe-product');
        $projectPage->dom->linkBtn->click();
        $projectPage->wait(1);
        if (isset($expectedProject)) $projectPage->dom->project->picker($expectedProject);
        $projectPage->wait(1);
        $projectPage->dom->btn($this->lang->save)->click();
        $projectPage->wait(1);
        return ($projectPage->dom->firstProject->getText() == $expectedProject)
            ? $this->success('产品关联项目成功')
            : $this->failed('产品关联项目失败');
    }
}
