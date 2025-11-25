<?php
class forgetpasswordPage extends page
{
    public function __construct($webdriver)
    {
        global $tester;
        parent::__construct($webdriver);
        $this->xpath = array(
            // 容器
            'main'        => "//*[@id='main']",
            'mainContent' => "//*[@id='mainContent']",

            // 头部动作：切换到管理员重置
            'resetLink'   => "//*[@id='mainContent']//a[normalize-space()='{$tester->lang->user->resetPwdByAdmin}']",

            // 表单元素
            'account'     => "//input[@name='account']",
            'email'       => "//input[@name='email']",
            'submitBtn'   => "//*[@id='mainContent']//button[@type='submit']",
            'gobackBtn'   => "//*[@id='mainContent']//*[contains(@class,'not-open-url')]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}