<?php
class createProductTester extends tester
{
    /**
     * Check the page jump after creating the product.
     *
     * @param  string    $name
     * @access public
     * @return object
     */
    public function checkLocatePage($name)
    {
        $this->login();
        $formPage = $this->formPage('product', 'create');
        $formPage->dom->name->setValue($name);
        $formPage->dom->btn($this->lang->save)->click();
        return $this->parseCurrentUrl();
    }

    /**
     * Create a default product.
     *
     * @param  string    $name
     * @access public
     * @return object
     */
    public function createDefault($name)
    {
        /* 提交表单。 */
        $this->login();
        $formPage = $this->formPage('product', 'create');
        $formPage->dom->name->setValue($name);
        $formPage->dom->btn($this->lang->save)->click();
        $formPage->dom->wait(1);

        $this->parseCurrentUrl();
        if($this->method != 'browse') return $this->failed($formPage->dom->getFormTips());

        /* 跳转到产品需求页面，点击设置菜单查看产品设置页面。 */
        $browsePage = $this->initPage('product', 'browse');
        $browsePage->dom->settings->click();

        $viewPage = $this->initPage('product', 'view');
        if($viewPage->dom->productName->getText() != $name) return $this->failed('名称错误');
        if($viewPage->dom->type->getText() != '正常') return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != '公开') return $this->failed('权限错误');

        return $this->success();
    }

    /**
     * Create a multi-branch product
     *
     * @param  string    $name
     * @access public
     * @return object
     */
    public function createMultiBranch($name)
    {
        /* 提交表单。 */
        $this->login();
        $formPage = $this->formPage('product', 'create');
        $formPage->dom->name->setValue($name);
        $formPage->dom->type->picker('多分支');
        $formPage->dom->btn($this->lang->save)->click();
        $formPage->dom->wait(1);

        $this->parseCurrentUrl();
        if($this->method != 'browse') return $this->failed($formPage->dom->getFormTips());

        /* 跳转到产品需求页面，点击设置菜单查看产品设置页面。 */
        $browsePage = $this->initPage('product', 'browse');
        $browsePage->dom->settings->click();

        $viewPage = $this->initPage('product', 'view');
        if($viewPage->dom->productName->getText() != $name) return $this->failed('名称错误');
        if($viewPage->dom->type->getText() != '多分支') return $this->failed('类型错误');
        if($viewPage->dom->branchProductACL->getText() != '公开') return $this->failed('权限错误');

        return $this->success();
    }
}
