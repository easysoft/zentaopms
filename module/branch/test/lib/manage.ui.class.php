<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageTester extends tester
{
    /**
     * 切换tab
     * switch tab
     *
     * @param $productID 产品ID
     * @param $tabName   tab名称
     * @param $tabNum    tab下分支数
     *
     * @return mixed
     */
    public function switchTab($productID, $tabName, $tabNum)
    {
        $tabDom     = $tabName . 'Tab';
        $tabNumDom  = $tabName . 'Num';
        $managePage = $this->initForm('branch', 'manage', $productID, 'appIframe-product');
        $managePage->dom->$tabDom->click();//点击对应的tab
        $managePage->wait(1);
        $num = $managePage->dom->$tabNumDom->getText();//对应tab下分支数量
        return ($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }
}
