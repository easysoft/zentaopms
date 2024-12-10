
<?php
class batchcreatePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'save' => "//button[@type='button']/span[text()]/../preceding-sibling::button[@type='submit']/span[text()]"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
