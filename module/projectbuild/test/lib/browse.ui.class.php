<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 切换产品查看版本。
     * Switch products to view project builds.
     *
     * @param  string $product
     * @param  string $expectNum
     * @access public
     * @return void
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('projectbuild', 'browse', array('projectID' => '1' ), 'appIframe-project');
        $form->wait(2);
        if($product != '') $form->dom->product->picker($product);
        $form->wait(1);
