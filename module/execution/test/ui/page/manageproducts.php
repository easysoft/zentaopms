<?php
class manageproductsPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 已关联的产品 */
            'producta' => "//*[@id='manageProducts']/div[1]/div[1]/div[2]/button[1]",
            /* 未关联的产品 */
            'productb' => "//*[@id='manageProducts']/div[1]/div[2]/div[2]/button[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
