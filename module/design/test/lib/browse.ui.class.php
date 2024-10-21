<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 检查各个菜单下的数据。
     * Check the data of different menu.
     *
     * @param  string $menu all|hlds|dds|dbds|ads
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkMenu($menu, $expectNum)
    {
        $form         = $this->initForm('design', 'browse', array('projecID' => 1), 'appIframe-project');
        $selectedMenu = $menu . 'Menu';
        $form->dom->$selectedMenu->click();
        $form->wait(1);
        $string    = $form->dom->designNum->getText();
        $designNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($designNum == $expectNum) return $this->success($menu . '菜单下显示数据正确');
        return $this->failed($menu . '菜单下显示数据不正确');
    }

    /**
     * 切换产品查看设计。
     * Switch product to view design.
     *
     * @param  string $product firstProduct|secondProduct
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('design', 'browse', array('projectID' => '1'), 'appIframe-project');
        $form->dom->dropMenu->click();
        $form->dom->$product->click();
        $form->wait(1);
        $string    = $form->dom->designNum->getText();
        $designNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($designNum == $expectNum) return $this->success('切换' . $product . '查看设计数据成功');
        return $this->failed('切换' . $product . '查看设计数据失败');
    }
}
