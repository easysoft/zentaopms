<?php
class batchRunPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'modalText' => "//*[@class='modal-content']/div[2]/text()",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
