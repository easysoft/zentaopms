<?php
class setPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'openReview'  => "//label[@for='needReview1']",
            'closeReview' => "//label[@for='needReview0']",
            'confirm'     => "//button[@z-key='confirm']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
