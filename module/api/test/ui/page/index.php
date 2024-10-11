<?php
class indexPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createLibBtn' => '/html/body/div[1]/div/div[1]/div[2]/a[4]',
            'createApiBtn' => '/html/body/div[1]/div/div[1]/div[2]/a[5]',
            'fstDocPath'   => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/ul/li/div/a/span[2]',
            'fstMoreBtn'   => '/html/body/div/div/div[2]/div[1]/div[1]/div[2]/main/menu/li[1]/div/nav/button',
            'fstEditBtn'   => '//*[@class="popover show fade dropdown in"]/menu/menu/li[2]/a[1]',
            'fstLibTitle'  => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div[2]/main/menu/li[1]/div/div/a'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
