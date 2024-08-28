<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'copyButton' => "//div[@id='instanceInfoContainer']//button",
            'toast'      => "//div[text()='复制成功']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
