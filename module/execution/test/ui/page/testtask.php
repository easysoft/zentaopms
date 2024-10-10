<?php
class testtaskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'totalCheckbox' => "//*[@id='taskTable']/div[3]/div[1]/div/label",
            'report'        => "//*[@id='taskTable']/div[3]/nav[1]/button/span",
            'total'         => "//*[@id='taskTable']/div[3]/div[2]/strong[1]",
            'waiting'       => "//*[@id='taskTable']/div[3]/div[2]/strong[2]",
            'doing'         => "//*[@id='taskTable']/div[3]/div[2]/strong[3]",
            'blocked'       => "//*[@id='taskTable']/div[3]/div[2]/strong[4]",
            'done'          => "//*[@id='taskTable']/div[3]/div[2]/strong[5]",
            'modalInfo'     => "//div[@class='modal-body']/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
