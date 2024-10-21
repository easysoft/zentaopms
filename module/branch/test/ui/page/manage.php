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
            'allTab'       => "//*[@id='featureBar']/menu/li[1]/a",
            'activeTab'    => "//*[@id='featureBar']/menu/li[2]/a",
            'closedTab'    => "//*[@id='featureBar']/menu/li[3]/a",
            'allNum'       => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            'activeNum'    => "//*[@id='featureBar']/menu/li[2]/a/span[2]",
            'closedNum'    => "//*[@id='featureBar']/menu/li[3]/a/span[2]",
            'secName'      => "//*[@id='table-branch-manage']/div[2]/div[1]/div/div[4]/div",
            'secDesc'      => "//*[@id='table-branch-manage']/div[2]/div[2]/div/div[8]/div",
            'secStatus'    => "//*[@id='table-branch-manage']/div[2]/div[2]/div/div[5]/div/span",
            'selectAllBtn' => "//*[@id='table-branch-manage']/div[3]/div[1]/div/label",
            'batchEditBtn' => "//*[@id='table-branch-manage']/div[3]/nav[1]/button[1]",
            'editBtn'      => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[1]",
            'closeBtn'     => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[2]",
            'activateBtn'  => "//*[@id='table-branch-manage']/div[2]/div[3]/div/div[2]/div/nav/a[2]",
            'confirmBtn'   => "//button[@z-key='confirm']",
            /*编辑分支页*/
            'editName' => "//*[@id='zin_branch_edit_1_form']/div[1]/input",
            'editDesc' => "//*[@id='zin_branch_edit_1_form']/div[3]/textarea",
            'editSave' => "//*[@id='zin_branch_edit_1_form']/div[4]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
