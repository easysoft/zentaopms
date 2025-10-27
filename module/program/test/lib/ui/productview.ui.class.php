<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 产品视角下创建产品。
     * Create a product in the module of program.
     *
     * @param  string $programName
     * @access public
     * @return void
     */
    public function createProgramProduct($programs, $products)
    {
        /* 提交表单。 */
        $this->openUrl('program', 'productview');
        $form = $this->loadPage('program', 'productview');
        $form->dom->caretBtn->click();
        $form->dom->addProduct->click();
        $form->wait(1);

        /*创建项目集下产品*/
        $form->dom->switchToIframe('');
        $form->dom->switchToIframe('appIframe-product');
        $form->dom->program->picker($programs->program);
        $form->dom->name->setValue($products->programProduct);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /*检查产品是否显示在了产品视角下*/
        $this->openUrl('program', 'productview');
        $form = $this->loadPage('program', 'productview');
        $form->dom->fstProgram->click();
        $form->wait(1);
        if($form->dom->fstProduct->getText() != $products->programProduct) return $this->failed('产品视角下创建产品失败');
        $this->openUrl('program', 'productView');
        return $this->success('产品视角下创建产品成功');
    }

    /**
     * 产品视角下维护产品线。
     * Manage product line in the product view.
     *
     * @param  string $programName
     * @access public
     * @return void
     */
    public function manageProductLine($programs, $productLines)
    {
        /*维护表单*/
        $this->openUrl('program', 'productview');
        $form = $this->loadPage('program', 'productview');
        $form->dom->caretBtn->click();
        $form->dom->manageProductLine->click();
        $form->wait(1);

        $form->dom->productLineName->setValue($productLines->productLine);
        $form->dom->ownProgram->picker($programs->program);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();

        /*检查产品线是否创建成功*/
        $this->openUrl('program', 'productview');
        $form = $this->loadPage('program', 'productView');
        $form->dom->search(array("项目集名称,=,{$programs->program}"));
        $form->wait(1);
        if($form->dom->productLine->getText() != $productLines->productLine) return $this->failed('维护产品线失败');
        $this->openUrl('program', 'productView');

        return $this->success('维护产品线成功');
    }
}
