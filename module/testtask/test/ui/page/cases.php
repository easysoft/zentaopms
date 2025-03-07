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
            'firstId'            => "//*[@id='table-testtask-cases']/div[2]/div[1]/div/div[1]/div",
            'firstAssignedTo'    => "//*[@id='table-testtask-cases']/div[2]/div[2]/div/div[2]/div/a/span",
            'firstResult'        => "(//div[@data-type='status'])[2]",
            'firstRunBtn'        => "//*[@id='table-testtask-cases']/div[2]/div[3]/div/div[1]/div/nav//a[contains(@href, 'index.php?m=testtask&f=runCase')]",
            'dropDownBtn'        => "//*[@id='table-testtask-cases']/div[3]/nav[1]/nav/button[2]",
            'batchUnlinkBtn'     => "//menu/menu/li[1]/a/div/div",
            'batchAssignedToBtn' => "//*[@id='table-testtask-cases']/div[3]/nav[1]/button[1]",
            'batchRunBtn'        => "//*[@id='table-testtask-cases']/div[3]/nav[1]/button[2]",
            'secondUser'         => "//menu/menu/li[2]/a/div/div",
            'lastCheckbox'       => "//*[@id='table-testtask-cases']/div[2]/div[1]/div/div[last()-1]/div/div",
            'lastResult'         => "//*[@id='table-testtask-cases']/div[2]/div[2]/div/div[@data-type='status'][last()]",
            'lastRunBtn'         => "//*[@id='table-testtask-cases']/div[2]/div[3]/div/div[5]/div/nav//a[contains(@href, 'index.php?m=testtask&f=runCase')]",
            /* 执行弹窗 */
            'result'    => "//*[@id='caseStepForm']/table/tbody/tr[1]/td[3]/div/div/input",
            'submitBtn' => "//*[@id='caseStepForm']//button[@type='submit']",
            'close'     => "//*[@id='runCaseModal']/div/div[2]/button/span",
            /* 批量执行modal */
            'modalText' => "//*[@class='modal-body']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
