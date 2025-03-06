<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'programName'     => '//*[@id="projectviews"]/div[2]/div[1]/div/div/div/a',
            'endDate'         => '//*[@id="projectviews"]/div[2]/div[2]/div/div[6]/div',
            'personnelNav'    => "//*[@id='navbar']//a[@data-id='personnel']",
            'whitelistNav'    => '//*[@id="mainNavbar"]//a[@data-id="whitelist"]',
            'whitelistUser'   => '//*[@id="table-personnel-whitelist"]/div[2]/div[1]/div/div[2]/div',
            'editBtn'         => '//*[@id="projectviews"]/div[2]/div[3]/div/div[1]/div/nav/a[2]',
            'secEditBtn'      => '//*[@id="projectviews"]/div[2]/div[3]/div/div[2]/div/nav/a[2]',
            'fstStartBtn'     => '//*[@id="projectviews"]/div[2]/div[3]/div/div[1]/div/nav/a[1]',
            'closeBtn'        => '//*[@id="projectviews"]/div[2]/div[3]/div/div[1]/div/nav/a[1]',
            'closeConfirm'    => '//*[@class="panel-body"]/form/div[3]/div/button',
            'fstStatus'       => '//*[@id="projectviews"]/div[2]/div[2]/div[1]/div[1]/div[1]/span',
            'startProgramBtn' => '//*[@class="panel-body"]/form/div[3]/div/button',
            'addChildBtn'     => '//*[@id="projectviews"]/div[2]/div[3]/div/div[1]/div/nav/a[3]',
            'fstdeleteBtn'    => '//*[@id="projectviews"]/div[2]/div[3]/div/div[1]/div/nav/a[4]',
            'checkoutPrompt'  => '//*[@class="modal-dialog"]/div/div[2]/div[2]',
            'deleteCancel'    => '//*[@class="modal modal-async load-indicator modal-alert modal-trans show in"]/div/div[1]/div[3]/nav/button[2]',
            'deleteConfirm'   => '//*[@class="modal modal-async load-indicator modal-alert modal-trans show in"]/div/div[1]/div[3]/nav/button[1]',
            'undeleteConfirm' => '//*[@class="modal-content"]/div[3]/nav/button[2]',
            'formText'        => '//*[@id="projectviews"]/div/div/div',
            'fstProgramName'  => '/html/body/div[1]/div/div[2]/div[2]/div/div[2]/div[1]/div/div/div/a',
            'addProject'      => '/html/body/div[1]/div/div[1]/div[2]/a[1]',
            'allTab'          => '//*[@id="mainMenu"]/div[1]/menu[1]/li[1]/a[1]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
