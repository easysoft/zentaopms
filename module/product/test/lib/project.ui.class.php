<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class projectTester extends tester
{
    /**
     * 切换tab
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
}
