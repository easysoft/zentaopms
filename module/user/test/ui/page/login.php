<?php
class loginPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 页面与表单容器
            'main'        => "//*[@id='main']",
            'loginForm'   => "//*[@id='loginForm']",
            'avatar'      => '//*[@id="userMenu-toggle"]/div/div',

            // 表单元素
            'account'     => "//*[@id='account']",
            'password'    => "//*[@id='password']",
            'submit'      => "//*[@id='submit']",

            // 其他提示与链接
            'loginExpired' => "//*[contains(@class,'loginExpired')]",
            'resetPassword' => "//*[@class='resetPassword']",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}