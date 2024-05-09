<?php
class page
{
    public $xpath = array(
        'menuMoreNav' => '//*[@id="menuMoreNav"]/li[2]/a'
    );
    public $dom;
    public $webdriver;

    public function __construct($webdriver)
    {
        $this->webdriver = $webdriver;
        $this->dom = new dom($webdriver->driver);
        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }

    public function __call($method, $params = array())
    {
        if(method_exists($this->webdriver, $method)) return call_user_func_array(array($this->webdriver, $method), $params);

        return false;
    }
}
