<?php
class managePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /*新建分支页*/
            'branchName' => "//*[@id='zin_branch_create_form']/div[1]/input",
            'branchDesc' => "//*[@id='zin_branch_create_form']/div[2]/textarea",
            'save'       => "//*[@id='zin_branch_create_form']/div[3]/div/button",
            /*分支列表*/
            'allTab'      => "//*[@id='featureBar']/menu/li[1]/a",
            'activeTab'   => "//*[@id='featureBar']/menu/li[2]/a",
            'closedTab'   => "//*[@id='featureBar']/menu/li[3]/a",
            'secStatus'   => "//*[@id='table-branch-manage']/div[2]/div[2]/div/div[5]/div/span",
            'editBtn'     => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[1]",
            'closeBtn'    => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[2]",
            'activateBtn' => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}

