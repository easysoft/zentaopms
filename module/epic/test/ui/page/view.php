<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            /*需求详情页*/
            'status'   => "//*[@class='tab-content']/div/div/div[6]/div[2]/span",
            /*激活弹窗的激活按钮*/
            'activate' => "//*[@type='submit']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
