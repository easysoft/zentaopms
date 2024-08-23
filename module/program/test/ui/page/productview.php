<?php
class productViewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'productProgram'    => '//*[@class="panel panel-form page-form is-lite"]/div[2]/div/form/div[1]/div[1]/div',
            'caretBtn'          => '//*[@id="actionBar"]/div/button',
            'addProduct'        => '//*[@class="popover show fade dropdown in"]/menu/menu/li[2]/a',
            'productLine'       => '//*[@id="productviews"]/div[2]/div[1]/div[1]/div[3]/div[1]',
            'manageProductLine' => '//*[@class="popover show fade dropdown in"]/menu/menu/li[3]/a',
            'productLineName'   => '//*[@id="zin_product_manageline_form"]/div[2]/div[1]/div[1]/input[1]',
            'ownProgram'        => '//*[@id="zin_product_manageline_form"]/div[2]/div[1]/div[2]/div[1]/div[1]',
            'fstProgram'        => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[1]/div/div[1]/div/a[1]',
            'fstProduct'        => '/html/body/div[1]/div/div[2]/div/div/div[2]/div[1]/div/div/div/a',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
