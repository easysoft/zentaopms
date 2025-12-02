<?php
class resetPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $this->xpath = array(
            // 管理员密码重置文件提示框
            'createPanel' => "//*[@id='mainContent']//*[contains(@class,'create-file-panel')]",
            'infoAlert'   => "//*[@id='mainContent']//*[contains(@class,'alert-info')]",
            'gobackBtnCF' => "//*[@id='mainContent']//*[contains(@class,'not-open-url')]",
            'resetFileName' => "//*[@id='mainContent']/div/div/div[2]/div[1]/h5[2]/span",

            // 管理员密码重置对话框
            'account'     => "//input[@name='account']",
            'password1'   => "//*[@id='password1']",
            'password2'   => "//*[@id='password2' or @name='password2']",
            'submitBtn'   => "//button[@type='submit']",
            'verifyRand'  => "//input[@name='verifyRand']",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}
