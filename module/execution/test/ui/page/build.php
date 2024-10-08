<?php
class buildPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'num' => "//nav[@class='pager']/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
