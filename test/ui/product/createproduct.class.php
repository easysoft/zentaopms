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
        $createPage->name->setValue('正常产品');
        return $createPage->submit();
    }

    public function createDefault()
    {
        $name = '默认产品';

        $this->login();
        $createPage = $this->loadPage('product', 'create');
        $createPage->name->setValue($name);
        $result = $createPage->submit();

        if($result->status != 'SUCCESS') return $result;

        $browsePage = $this->setPage('product', 'browse');
        $browsePage->settings->click();

        $viewPage = $this->setPage('product', 'view');
        if($viewPage->name != $name) return failed('名称错误');
        if($viewPage->acl != '私有') return failed('权限错误');

        return success('SUCCESS');
    }
}
