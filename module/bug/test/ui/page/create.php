<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'openedBuild' => "//div[@data-name = 'openedBuild']/div[@class = 'input-group']//div[@id]",
            'steps' => "//*[@class='form-control p-0 h-auto w-full hydrated']/article[@slot = 'content']",
            'save' => "//span[text()='保存']",
            'steps' => "//zen-editor[@name='steps']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
