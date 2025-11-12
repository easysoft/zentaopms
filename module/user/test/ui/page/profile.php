<?php
class profilePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            // 顶部用户选择器
            'userPicker' => '//div[contains(@class, "picker-select-single")]',
            //详情字段
            'realname'   => '//*[@id="mainContent"]/div/table[1]/tbody/tr[1]/td[1]',
            'gender'     => '//*[@id="mainContent"]/div/table[1]/tbody/tr[1]/td[2]',
            'account'    => '//*[@id="mainContent"]/div/table[1]/tbody/tr[2]/td[1]',
            'email'      => '//*[@id="mainContent"]/div/table[1]/tbody/tr[2]/td[2]/a',
            'dept'       => '//*[@id="mainContent"]/div/table[1]/tbody/tr[3]/td[1]',
            'role'       => '//*[@id="mainContent"]/div/table[1]/tbody/tr[3]/td[2]',
            'join'       => '//*[@id="mainContent"]/div/table[1]/tbody/tr[4]/td[1]',
            'group'      => '//*[@id="mainContent"]/div/table[1]/tbody/tr[4]/td[2]',
            'mobile'     => '//*[@id="mainContent"]/div/table[2]/tbody/tr[1]/td[1]',
            'weixin'     => '//*[@id="mainContent"]/div/table[2]/tbody/tr[1]/td[2]',
            'phone'      => '//*[@id="mainContent"]/div/table[2]/tbody/tr[2]/td[1]',
            'qq'         => '//*[@id="mainContent"]/div/table[2]/tbody/tr[2]/td[2]',
            'zipcode'    => '//*[@id="mainContent"]/div/table[2]/tbody/tr[3]/td[1]',
            'address'    => '//*[@id="mainContent"]/div/table[2]/tbody/tr[3]/td[2]',
            'commiter'   => '//*[@id="mainContent"]/div/table[3]/tbody/tr[1]/td[1]',
            'skype'      => '//*[@id="mainContent"]/div/table[3]/tbody/tr[1]/td[2]/a',
            'visits'     => '//*[@id="mainContent"]/div/table[3]/tbody/tr[2]/td[1]',
            'whatsapp'   => '//*[@id="mainContent"]/div/table[3]/tbody/tr[2]/td[2]',
            'last'       => '//*[@id="mainContent"]/div/table[3]/tbody/tr[3]/td[1]',
            'slack'      => '//*[@id="mainContent"]/div/table[3]/tbody/tr[3]/td[2]',
            'ip'         => '//*[@id="mainContent"]/div/table[3]/tbody/tr[4]/td[1]',
            'dingding'   => '//*[@id="mainContent"]/div/table[3]/tbody/tr[4]/td[2]'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}