<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageTester extends tester
{
    public function switchTab($productID, $tabName, $tabNum)
    {
        $tabDom    = $tabName . 'Tab';
        $tabNumDom = $tabName . 'Num';
        $managePage = $this->initForm('branch', 'manage', $productID, 'appIframe-product');
        $managePage->dom->$tabDom->click();//点击对应的tab
        $num = $managePage->dom->$tabNumDom->getText();//获取对应tab下分支数量
        return ($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }
}
