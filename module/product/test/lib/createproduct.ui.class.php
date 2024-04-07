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
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->response();
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
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'browse') return $this->failed($form->dom->getFormTips());

        /* 跳转到产品需求页面，点击设置菜单查看产品设置页面。 */
        $browsePage = $this->initPage('product', 'browse');
        $browsePage->dom->settings->click();

        $viewPage = $this->initPage('product', 'view');
        $this->app->loadLang('product');
        if($viewPage->dom->productName->getText() != $name) return $this->failed('名称错误');
        if($viewPage->dom->type->getText() != $this->lang->product->typeList['normal']) return $this->failed('类型错误');
        if($viewPage->dom->acl->getText()  != $this->lang->product->abbr->aclList['open']) return $this->failed('权限错误');

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
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($name);
        $form->dom->type->picker('多分支');
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'browse') return $this->failed($form->dom->getFormTips());

        /* 跳转到产品需求页面，点击设置菜单查看产品设置页面。 */
        $browsePage = $this->initPage('product', 'browse');
        $browsePage->dom->settings->click();

        $viewPage = $this->initPage('product', 'view');
        $this->app->loadLang('product');
        if($viewPage->dom->productName->getText() != $name) return $this->failed('名称错误');
        if($viewPage->dom->type->getText() != $this->lang->product->typeList['branch']) return $this->failed('类型错误');
        if($viewPage->dom->branchProductACL->getText() != $this->lang->product->abbr->aclList['open']) return $this->failed('权限错误');

        return $this->success();
    }
}
