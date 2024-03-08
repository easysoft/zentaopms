<?php
include dirname(__FILE__, 3) . '/lib/init.class.php';
$driver = uiTester::factory('zentaopms', 'webdriver', $config->chrome);
$lang   = new stdclass();

class zentao
{
    public $driver;
    public $page;
    public $config;

    /**
     * Init driver, config, dom.
     *
     * @access public
     * @return void
     */
    public function __construct($pageName, $code)
    {
        global $driver;
        $driver->setCase($pageName, $code);

        $this->driver = $driver;
        $this->page   = new Page($driver);
        $this->config = $driver->config;

    }

    /**
     * Login to the test URL.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function su($account = '', $password = '')
    {
        if(!$account)  $account  = $this->config->zentaopms->defaultAccount;
        if(!$password) $password = $this->config->zentaopms->defaultPassword;

        $this->page->deleteCookie();

        $this->page->get('');
        $this->page->getErrors();
        $this->page->account->setValue($account);
        $this->page->password->setValue($password);
        $this->page->submit->click();

        $this->page->getCookie();

        return $this->page;
    }

    /**
     * open a mainMenu, navBar or URl.
     *
     * @param  string $value
     * @param  string $type mainNav|navBar|switchToUrl
     * @access public
     * @return void
     */
    public function go($value, $type = 'switchToUrl')
    {
        if(in_array($type, array('mainNav', 'navBar')))
        {
            try
            {
                $this->page->$type($value)->click();
            }
            catch(Exception $e)
            {
                $this->page->menuMoreNav->click();
                $this->page->mainNav($value)->click();
            }

            $this->page->wait(1)->switchTo('appIframe-' . $value);
        }

        if($type == 'switchToUrl') $this->page->$type($value, true);

        return $this->page;
    }

    /**
     * Create object in from page.
     *
     * @param  string $app
     * @param  object $form
     * @access public
     * @return object
     */
    public function createInFrom($app, $form)
    {
        global $lang;

        $this->page->btn($lang->$app->addBtn)->click();
        $this->page->wait(1)->getErrors("appIframe-{$app}");

        foreach($form as $formName => $value)
        {
            if(isset($value['wait']))        $this->page->wait($value['wait']);
            if(isset($value['picker']))      $this->page->$formName->picker($value['picker']);
            if(isset($value['multiPicker'])) $this->page->$formName->multiPicker($value['multiPicker']);
            if(isset($value['datePicker']))  $this->page->$formName->datePicker($value['datePicker']);
            if(isset($value['setValue']))    $this->page->$formName->setValue($value['setValue']);
            if(isset($value['click']))       $this->page->$formName->click();
        }

        $this->page->btn($lang->$app->saveBtn)->click();

        return $this->page;
    }

    /**
     * Close the browser.
     *
     * @access public
     * @return void
     */
    public function closeBrowser()
    {
        $this->driver->closeBrowser();
    }
}
