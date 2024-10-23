<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'editBtn'             => "//*[@id='table-projectrelease-browse']/div[2]/div[3]/div/div/div/nav/a[4]/i",
            'waitTab'             => "//*[@id='featureBar']/menu/li[2]/a/span[1]",
            'releasedTab'         => "//*[@id='featureBar']/menu/li[3]/a/span[1]",
            'terminatedTab'       => "//*[@id='featureBar']/menu/li[4]/a/span[1]",
            'releaseBtn'          => "//*[@id='table-projectrelease-browse']/div[2]/div[3]/div/div/div/nav/a[3]/i",
            'releaseSubmit'       => "//*[@id='zin_projectrelease_publish_form']/div[4]/div/button/span",
            'terminateBtn'        => "//*[@class='dtable-cell-content']/nav/a[3]/i",
            'terminateConfirm'    => "//*[@class='modal-footer']/nav/button/span",
            'activeBtn'           => "//*[@class='toolbar']/a[3]/i",
            'activeConfirm'       => "//*[@class='modal-footer']/nav/button/span",
            'releaseName'         => "//*[@class='dtable-cells-container']/div[2]/div/a",
            'releaseNameBrowse'   => "//*[@id='table-projectrelease-browse']/div[2]/div/div/div[2]/div/a",
            'releaseStatusBrowse' => "//*[@id='table-projectrelease-browse']/div[2]/div[2]/div/div[3]/div/span",
            'planDateBrowse'      => "//*[@id='table-projectrelease-browse']/div[2]/div[2]/div/div[4]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
