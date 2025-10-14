<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        return($num == $tabNum) ? $this->success("切换至{$tabName}Tab成功") : $this->failed("切换至{$tabName}Tab失败");
    }

    /**
     * 导出产品
     * export product
     * @return mixed
     */
    public function export()
    {
        $allPage = $this->initForm('product', 'all', array(), 'appIframe-product');
        $allPage->dom->exportBtn->click();
        $allPage->wait(2);
        $allPage->dom->exportConfirm->click();
        $allPage->wait(2);
        return(!$allPage->dom->exportConfirm)
            ? $this->success("导出产品成功")
            : $this->failed("导出产品失败");
    }
}
