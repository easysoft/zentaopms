<?php
class createProductTester extends tester
{
    public function createWithoutName()
    {
        $this->login();
        $createPage = $this->loadPage('product', 'create');
        return $createPage->submit();
    }

    public function checkLocatePage()
    {
        $this->login();
        $createPage = $this->loadPage('product', 'create');
        $createPage->name->setValue('正常产品' . time());
        return $createPage->submit();
    }

    public function createDefault()
    {
        $name = '默认产品' . time();

        $this->login();
        $createPage = $this->loadPage('product', 'create');
        $createPage->name->setValue($name);
        $createPage->submit();

        $browsePage = $this->setPage('product', 'browse');
        $browsePage->settings->click();

        $viewPage = $this->setPage('product', 'view');
        if($viewPage->productName->getText() != $name) return failed('名称错误');
        if($viewPage->type->getText() != '正常') return failed('类型错误');
        if($viewPage->acl->getText() != '公开') return failed('权限错误');

        return success('SUCCESS');
    }
}
