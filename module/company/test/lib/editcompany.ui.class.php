<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editCompanyTester extends tester
{
    /**
     * Check the page of edit the company information.
     *
     * @param  string    $company
     * @access public
     * @return object
     *
     */
    public function editCompany($company)
    {
        $this->openURL('company', 'view',array(),'appIframe-system');
        $form = $this->loadPage('company', 'edit');
        $form->dom->editbtn->click();

        $form->dom->name->setValue($company->name);
        $form->dom->phone->setValue($company->phone);
        $form->dom->address->setValue($company->address);
        $form->dom->zipcode->setValue($company->zipcode);
        $form->dom->primary->click();
        $form->wait(1);
        $form->dom->savebtn->click();

        return $this->success('编辑公司信息成功');
    }
}
