<?php
class reviewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'result' => "/html/body/div[1]/div/div/div[2]/div/form/div[2]/div/div/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
