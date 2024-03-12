<?php
class user
{
    /**
     * Login to the test URL.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function login($account = '', $password = '')
    {
        global $config;
        $page = new Page();
        if(!$account)  $account  = $config->defaultAccount;
        if(!$password) $password = $config->defaultPassword;

        $page->deleteCookie();

        $page->get('');
        $page->getErrors();
        $page->account->setValue($account);
        $page->password->setValue($password);
        $page->submit->click();

        $page->getCookie();

        return $page;
    }
}
