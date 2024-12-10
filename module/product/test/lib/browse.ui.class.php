<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 检查产品各需求列表下的数据
     * check data of work
     * @param $url       url参数
     * @param $type      类型 SR|UR|ER
     * @param $tab       tab名
     * @param $expectNum 预期数量
     * @return mixed
     */
    public function checkData($url, $type, $tab, $expectNum)
    {
        $form = $this->initForm('product', 'browse', $url, 'appIframe-product');
        $form->wait(2);
        $numDom = $tab . 'Num';
        if (!$form->dom->$tab)
        {
            $form->dom->more->click();
            $form->wait(1);
        }
        $form->dom->$tab->click();
        $form->wait(2);
        $num = $form->dom->$tab ? $form->dom->$numDom->getText() : $form->dom->moreNum->getText();
    }
}
