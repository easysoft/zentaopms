<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    public function switchTab($planurl,$tabName,$tabNum)
    {
        $tabDom  = $tabName.'Tab';
        $tabNumDom  = $tabName.'Num';
        $browsePage = $this->initForm('productplan', 'browse', $planurl, 'appIframe-product');
        $browsePage->dom->$tabDom->click();
        $num = $browsePage->dom->$tabNumDom->getText();
        return ($num == $tabNum) ? $this->success("切换至{$tab}Tab成功") : $this->failed("切换至{$tab}Tab失败");
    }
}
