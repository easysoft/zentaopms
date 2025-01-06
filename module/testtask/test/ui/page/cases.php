<?php
class casesPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allCasesNum' => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            'num'         => "//*[@id='table-testtask-cases']/div[3]/div[2]/strong",
            'firstSuite'  => "//menu/menu/li[1]/a/div/div",
            /* 列表 */
            'firstCheckbox'      => "//*[@id='table-testtask-cases']/div[2]/div[1]/div/div[1]/div/div",
            'firstAssignedTo'    => "//*[@id='table-testtask-cases']/div[2]/div[2]/div/div[2]/div/a/span",
            'dropDownBtn'        => "//*[@id='table-testtask-cases']/div[3]/nav[1]/nav/button[2]",
            'batchUnlinkBtn'     => "//menu/menu/li[1]/a/div/div",
            'batchAssignedToBtn' => "//*[@id='table-testtask-cases']/div[3]/nav[1]/button[1]",
            'secondUser'         => "//menu/menu/li[2]/a/div/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
