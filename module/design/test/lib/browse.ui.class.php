<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
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
}
