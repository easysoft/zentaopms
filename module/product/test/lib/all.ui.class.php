<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class allTester extends tester
{
    /**
     * 切换tab
     * switch tab
     * @param $tabName tab名称
     * @param $tabNum  tab下产品数量
     * @return mixed
     */
    public function switchTab($tabName,$tabNum)
    {
        $tabDom    = $tabName.'Tab';
        $tabNumDom = $tabName.'Num';
        $allPage   = $this->initForm('product', 'all', array(), 'appIframe-product');
        $allPage->dom->$tabDom->click();//点击对应的tab
        $num = $allPage->dom->$tabNumDom->getText();//获取对应tab下产品数量
        return ($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }
}
