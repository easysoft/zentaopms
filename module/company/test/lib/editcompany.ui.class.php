<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editCompanyTester extends tester
{
    /**
     * Check the page of edit the company information.
     *
     *
     * @param  string    $name
     * @access public
     * @return object
     */
    public function editCompany($company)
    {
        $this->openURL('company', 'view',array(),'appIframe-system');
        $form = $this->loadPage('company', 'edit');
        $form->dom->editbtn->click();
