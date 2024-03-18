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
        $createPage = $this->loadPage('product', 'create');
        $createPage->name->setValue($name);
        $createPage->submit();
        return $createPage->getUrl();
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
        $createPage = $this->loadPage('product', 'create');
        $createPage->name->setValue($name);
        $createPage->submit();

        $url = $createPage->getUrl();
        if($url->method != 'browse') return failed($createPage->getFormTips());

        /* 跳转到产品需求页面，点击设置菜单查看产品设置页面。 */
        $browsePage = $this->setPage('product', 'browse');
        $browsePage->settings->click();

        $viewPage = $this->setPage('product', 'view');
        if($viewPage->productName->getText() != $name) return failed('名称错误');
        if($viewPage->type->getText() != '正常') return failed('类型错误');
        if($viewPage->acl->getText() != '公开') return failed('权限错误');

        return success();
    }
}
