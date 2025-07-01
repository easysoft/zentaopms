<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'editBtn'             => "//*[@id='projectreleases']/div[2]/div[3]/div/div/div/nav/a[4]/i",
            'waitTab'             => "//*[@id='featureBar']/menu/li[2]/a/span[1]",
            'releasedTab'         => "//*[@id='featureBar']/menu/li[3]/a/span[1]",
            'terminatedTab'       => "//*[@id='featureBar']/menu/li[4]/a/span[1]",
            'releaseBtn'          => "//*[@id='projectreleases']/div[2]/div[3]/div/div/div/nav/a[3]/i",
            'releaseSubmit'       => "//*[@id='zin_projectrelease_publish_form']/div[4]/div/button/span",
            'terminateBtn'        => "//*[@class='dtable-cell-content']/nav/a[3]/i",
            'terminateConfirm'    => "//*[@class='modal-dialog']/div/div[3]/nav/button[1]/span",
            'activeBtn'           => "//*[@class='toolbar']/a[3]/i",
            'activeConfirm'       => "//*[@class='modal-dialog']/div/div[3]/nav/button[1]/span",
            'releaseName'         => "//*[@id='projectreleases']/div[2]/div[1]/div/div[3]/div",
            'releaseNameBrowse'   => "//*[@id='projectreleases']/div[2]/div[1]/div/div[3]/div/a",
            'releaseStatusBrowse' => "//*[@id='projectreleases']/div[2]/div[2]/div/div[3]/div/span",
            'planDateBrowse'      => "//*[@id='projectreleases']/div[2]/div[2]/div/div[4]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
