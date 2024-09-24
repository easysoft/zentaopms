<?php
class bugPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'        => "//*[@id='featureBar']/menu/li[1]/a",
            'unresolvedTab' => "//*[@id='featureBar']/menu/li[2]/a",
            'num'           => "//*[@id='table-execution-bug']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstCheckbox'   => "//*[@id='table-execution-bug']/div[2]/div[1]/div/div[1]/div/div",
            'firstAssignedTo' => "//*[@id='table-execution-bug']/div[2]/div[2]/div/div[7]/div/a/span",
            /* 批量操作按钮 */
            'batchAssignBtn' => "//*[@id='table-execution-bug']/div[3]/nav[1]/button",
            'assignToAdmin'  => "//li//div[text() = 'admin']",
            /* bug指派弹窗 */
            'submitBtn' => "//*[@id='assignedTo']/../../div[4]/div/button",
            /*1.5级产品导航 */
            'productNav'    => "//*[@id='pick-execution-menu']",
            'firstProduct'  => "(//div[@data-type='product'])[1]",
            'secondProduct' => "(//div[@data-type='product'])[2]",
            'thirdProduct'  => "(//div[@data-type='product'])[3]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
