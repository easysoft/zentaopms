<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewTester extends tester
{
    public function view(array $design)
    {
        $form = $this->initForm('design', 'browse', array('projectID' => 60), 'appIframe-project');
        $browseDesignName = $form->dom->designName->getText();
        $browseProduct    = $form->dom->product->getText();
        $browseDesignType = $form->dom->designType->getText();
        $form->dom->designName->click();
        $form->wait(1);

        /* 跳转到设计详情页，检查设计字段信息。 */
        $viewPage = $this->loadPage('design', 'view');
        $viewTitle      = $viewPage->dom->designName->getText();
        $viewProduct    = $viewPage->dom->linkedProduct->getText();
        $viewDesignType = $viewPage->dom->designType->getText();
        $viewPage->wait(2);
        if($browseDesignName != $viewTitle) return $this->failed('设计名称错误');
        if($browseDesignType != $viewDesignType) return $this->failed('设计类型错误');
        if($browseProduct    != $viewProduct) return $this->failed('所属产品错误');
        return $this->success('设计详情页信息正确');
    }
}
