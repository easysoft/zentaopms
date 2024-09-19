<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 切换tab
     * switch tab
     *
     * @param $planurl 产品ID
     * @param $tabName tab名称
     * @param $tabNum tab下计划数量
     *
     * @return mixed
     */
    public function switchTab($planurl,$tabName,$tabNum)
    {
        $tabDom     = $tabName.'Tab';
        $tabNumDom  = $tabName.'Num';
        $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $browsePage->dom->$tabDom->click();//点击对应的tab
        $num = $browsePage->dom->$tabNumDom->getText();//获取对应tab下计划数量
        return ($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }
}
