<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'typeStore' => '(//*[@id="typestore"]/following-sibling::label)[2]',
            'typeExternal' => '(//*[@id="typeexternal"]/following-sibling::label)[2]',
            'saveButton' => '(//button[@type="submit"])[2]',
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
