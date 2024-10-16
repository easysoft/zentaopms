<?php
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\WebDriverTargetLocator;
use Facebook\WebDriver\WebDriverDimension;

require_once('vendor/autoload.php');

/**
 * Webdriver engine class.
 *
 * @copyright Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    zhouxin
 * @package
 * @license   LGPL
 * @version   $Id$
 * @Link      http://www.zentao.net
 */
class webdriver
{
    public $driver;

    /**
     * Initialize webdriver.
     *
     * @param  array  $chromeOptions
     * @access public
     * @return void
     */
    public function __construct($chromeOptions)
    {
        $this->driver = $this->initBrowser($chromeOptions);
    }

    /**
     * Init webdriver.
     *
     * @param  object    $browser
     * @access public
     * @return object
     */
    public function initBrowser($browser)
    {
        $capabilities = DesiredCapabilities::chrome();

        $options = new ChromeOptions();
        $options->addArguments($browser->options);
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create($browser->host, $capabilities);
    }

    /**
     * Open url.
     *
     * @param  string  $url
     * @access public
     * @return object
     */
    public function openURL($url)
    {
        $url = trim($url);
        $this->driver->get($url);

        return $this;
    }

    /**
     * Get title of current page.
     *
     * @access public
     * @return string
     */
    public function getPageTitle()
    {
        return $this->driver->getTitle();
    }

    /**
     * Get url of current page.
     *
     * @access public
     * @return string
     */
    public function getPageUrl()
    {
        $url = $this->driver->getCurrentURL();
        return $url;
    }

    /**
     * Set browser language.
     *
     * @access public
     * @return object
     */
    public function setLang()
    {
        global $lang, $app;

        $langName = $this->driver->manage()->getCookieNamed('lang')->getValue();
        if(substr($langName, 0, 2) == 'zh')
        {
            $app->clientLang = 'zh-cn';
        }
        elseif($langName)
        {
            $app->clientLang = 'en';
        }

        return $lang;
    }

    /**
     * Set browser window size.
     *
     * @param  int    $width
     * @param  int    $height
     * @access public
     * @return mixed
     */
    public function setWindowSize($width, $height)
    {
        $this->driver->manage()->window()->setSize(new WebDriverDimension($width, $height));
    }

    /**
     * Screenshot in page.
     *
     * @param  string  $imageFile
     * @access public
     * @return void
     */
    public function screenshot($imageFile)
    {
        $this->driver->takeScreenshot($imageFile);
    }

    /**
     * Get cookie in browser.
     *
     * @param  string $name
     * @access public
     * @return oid
     */
    public function getCookie($name = '')
    {
        if($name) return $this->driver->manage()->getCookieNamed($name)->getValue();

        return $this->driver->manage()->getCookies();
    }

    /**
     * Get a cookie list.
     *
     * @param  string $cookieFile
     * @access public
     * @return array
     */
    public function getCookieList()
    {
        $cookies   = $this->getCookie();

        $cookieArr = array();
        foreach($cookies as $cookie) $cookieArr[] = $cookie->toArray();

        return $cookieArr;
    }

    /**
     * Save the cookie in the cookie file.
     *
     * @param  string $cookieFile
     * @access public
     * @return array|bool
     */
    public function saveCookie($cookieFile)
    {
        if(!$cookieFile) return;

        $cookies = $this->getCookie();
        if(empty($cookie)) $cookies = $this->wait(1)->getCookie();

        $cookiesArray = array_map(function($cookie) {
            return $cookie->toArray();
        }, $cookies);

        $isSave = file_put_contents($cookieFile, json_encode($cookiesArray));

        return $isSave;
    }

    /**
     * Add a cookie.
     *
     * @param  array    $cookie
     * @access public
     * @return void
     */
    public function addCookie($cookie)
    {
        $this->driver->manage()->addCookie($cookie);
        return $this;
    }

    /**
     * Delete cookies in page.
     *
     * @access public
     * @return void
     */
    public function deleteCookie($name = '')
    {
        if($name)
        {
            $this->driver->manage()->deleteCookieNamed($name);
        }
        else
        {
            $this->driver->manage()->deleteAllCookies();
        }

        return $this;
    }

    /**
     * Wait.
     *
     * @param  int    $seconds
     * @access public
     * @return object
     */
    public function wait($seconds = 3)
    {
        sleep($seconds);

        return $this;
    }

    /**
     * Close browser.
     *
     * @access public
     * @return void
     */
    public function closeBrowser()
    {
        $this->driver->quit();
    }

    /**
     * Close the window.
     *
     * @access public
     * @return mixed
     */
    public function closeWindow()
    {
        $this->driver->close();
    }


    /**
     * Get page source.
     *
     * @access public
     * @return string
     */
    public function getPageSource()
    {
        return $this->driver->getPageSource();
    }

    /**
     * Get window handles.
     *
     * @access public
     * @return mixed
     */
    public function getWindowHandles()
    {
        return $this->driver->getWindowHandles();
    }

    /**
     * Try to open new window/tab.
     *
     * @param  string $type 'tab'|'window'|''
     * @access public
     * @return object $driver
     */
    public function newWindow($type = '')
    {
        if($type == 'tab')
        {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_TAB);
        }
        elseif($type == 'window')
        {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_WINDOW);
        }
        else
        {
            $this->driver->switchTo()->newWindow();
        }

        return $this->driver;
    }

    /**
     * Refresh page.
     *
     * @access public
     * @return object
     */
    public function refresh()
    {
        $this->driver->navigate()->refresh();
        return $this;
    }

    /**
     * Scroll page, default distance is 100 px.
     *
     * @param  int    $scrollDistance
     * @access public
     * @return void
     */
    public function scroll($scrollDistance = 100)
    {
        $this->driver->executeScript("window.scrollBy(0, $scrollDistance);");
        return $this;
    }
}

class dom
{
    public $driver;
    public $element;
    public $xpath = array();

    /**
     * Get the driver of the page.
     *
     * @param  object    $driver
     * @access public
     * @return void
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    /**
     * Assemble xpath for element.
     *
     * @param  string    $name
     * @access public
     * @return object
     */
    public function __get($name)
    {
        $xpathList = array('//*[@name="' . $name . '"]',
            '//*[@id="' . $name . '"]',
            '//*[@name="' . $name . '[]' . '"]'
        );

        if(!empty($this->xpath)) $xpathNameList = array_keys($this->xpath);
        if(in_array($name, $xpathNameList))
        {
            try
            {
                $this->waitElement($this->xpath[$name])->getElement($this->xpath[$name]);
            }
            catch (Exception $e)
            {
                return false;
            }
        }
        else
        {
            foreach($xpathList as $xpath)
            {
                try
                {
                    $this->waitElement($xpath)->getElement($xpath);
                    $this->xpath[$name] = $xpath;
                    break;
                }
                catch (Exception $e)
                {
                    $this->xpath[$name] = '';
                }
            }
        }

        if(!$this->xpath[$name]) return false;
        return $this;
    }

    /**
     * Open a app tab.
     *
     * @param  string $app
     * @access public
     * @return object
     */
    public function openAppTab($appTab)
    {
        $xpath = "//li[@data-app='$appTab']/a";
        $this->waitElement($xpath)->getElement($xpath)->click();
        sleep(1);
        $this->switchToIframe("appIframe-{$appTab}");

        return $this;
    }

    /**
     * open a navbar.
     *
     * @param  string $nav
     * @access public
     * @return object
     */
    public function openNavbar($nav)
    {
        $xpath = "//a[@data-id='$nav']";
        $this->waitElement($xpath)->getElement($xpath)->click();

        return $this;
    }

    /**
     * Get element of a button.
     *
     * @param  string $value
     * @param  string $type
     * @access public
     * @return object
     */
    public function btn($value, $type = 'text')
    {
        $xpath = $type == 'text' ? "//*[text()='$value']" : $value;
        $this->waitElement($xpath)->getElement($xpath);

        return $this;
    }

    /**
     * Get tips in page form.
     *
     * @access public
     * @param  int     waitTime
     * @return array
     */
    public function getFormTips($waitTime = 1)
    {
        sleep($waitTime);
        $this->getElementList('//div[contains(@class, "form-tip")]');

        $tips = array();
        foreach($this->element as $element)
        {
            $text = $element->getText();
            if(!$text) continue;

            $id = $element->getAttribute('id');
            $tips[$id] = $text;

            $value = $element->findElement(WebDriverBy::xpath('./parent::*//input'))->getAttribute('value');
            if($value) $tips[$id] .= '|' . $value;
        }

        return $tips;
    }

    /**
     * Find elements.
     *
     * @param  string $selector
     * @access public
     * @return object
     */
    public function getElementList($selector = '')
    {
        if(!$selector) return $this->element;

        $this->element = $this->driver->findElements(WebDriverBy::xpath($selector));

        return $this;
    }

    /**
     * Find element.
     *
     * @param  string $selector
     * @access public
     * @return object
     */
    public function getElement($selector = '')
    {
        if(!$selector) return $this->element;

        $this->element = $this->driver->findElement(WebDriverBy::xpath($selector));

        return $this;
    }

    /**
     * Take screenshot.
     *
     * @param  string $imageFile
     * @access public
     * @return object
     */
    public function screenshotByElement($imageFile)
    {
        $this->element->takeElementScreenshot($imageFile);

        return $this;
    }

    /**
     * Switch iframe.
     *
     * @param  mixed  $id  Can pass iframe's id or identifier, if pass nothing, then switch to default content.
     * @access public
     * @return void
     */
    public function switchToIframe($selector = '')
    {
        if($selector === '')
        {
            $this->driver->switchTo()->defaultContent();
        }
        elseif(is_numeric($selector))
        {
            $this->driver->switchTo()->frame($selector);
        }
        else
        {
            try
            {
                $frame = $this->driver->findElement(WebDriverBy::id($selector));
                $this->driver->switchTo()->frame($frame);
            }
            catch(Exception $e)
            {
                $frame = $this->driver->findElement(WebDriverBy::xpath($selector));
                $this->driver->switchTo()->frame($frame);
            }
        }

        return $this;
    }

    /**
     * Switch To window or tab.
     *
     * @param  string    $handler
     * @access public
     * @return mixed
     */
    public function switchToWindow($handler)
    {
        $this->driver->switchTo()->window($handler);

        return $this;
    }

    /**
     * Get value of one element.
     *
     * @access public
     * @return string|bool
     */
    public function getValue()
    {
        return $this->element->getAttribute('value');
    }

    /**
     * Set value of one element.
     *
     * @param  string $value
     * @access public
     * @return string|bool
     */
    public function setValue($value)
    {
        try
        {
            $this->clear();
            $this->element->sendKeys($value);
        }
        catch(Exception $e)
        {
            $this->driver->executeScript("arguments[0].defaultValue='{$value}';", array($this->element));
        }

        return $this;
    }

    /**
     * Get attribute of one element.
     *
     * @param  string  $attribute
     * @param  string  $mode  xpath|css|id|class|name|tag|link
     * @access public
     * @return string
     */
    public function attr($attribute)
    {
        return $this->element->getAttribute($attribute);
    }

    /**
     * Get text of one element.
     *
     * @access public
     * @return string
     */
    public function getText()
    {
        $text = $this->element->getText();
        return $text;
    }

    /**
     * Find element and click it.
     *
     * @access public
     * @return object
     */
    public function click()
    {
        try
        {
            $this->element->click();
        }
        catch(Exception $e)
        {
            $this->driver->executeScript("arguments[0].click();", array($this->element));
        }

        return $this;
    }

    /**
     * Click by mouse.
     *
     * @param  bool   $isDouble
     * @access public
     * @return array
     */
    public function clickByMouse($isDouble = false)
    {
        $action = new WebDriverActions($this->driver);
        if($isDouble)
        {
            $action->moveToElement($this->element)->doubleClick()->perform();
        }
        else
        {
            $action->moveToElement($this->element)->click()->perform();
        }

        return $this;
    }

    /**
     * clear value for element.
     *
     * @access public
     * @return object
     */
    public function clear()
    {
        $this->element->clear();
        return $this;
    }

    /**
     * hover an element, Some class like .dropdown-hover can be hovered.
     *
     * @access public
     * @return object
     */
    public function hover()
    {
        $coordinates = $this->element->getCoordinates();
        $this->driver->getMouse()->mouseMove($coordinates);

        return $this;
    }

    /**
     * Print Errors of current page.
     *
     * @param  mixed  $iframe  If pass this param, driver will skip to specified iframe.
     * @access public
     * @return array
     */
    public function getErrorsInPage($iframe = '')
    {
        $errors = array();

        $this->driver->switchTo()->defaultContent();
        if(!empty($this->getErrorsInAlert())) $errors[] = $this->getErrorsInAlert();
        if(!empty($this->getErrorsInZinBar())) $errors[] = $this->getErrorsInZinBar();

        $hasException = false;
        for($identifier = 0; $identifier < 10, $hasException == false; $identifier++)
        {
            try
            {
                $this->switchToIframe($identifier);
            }
            catch(Exception $e)
            {
                $hasException = true;
            }

            if($hasException == false)
            {
                if(!empty($this->getErrorsInAlert())) $errors[] = $this->getErrorsInAlert();
                if(!empty($this->getErrorsInZinBar())) $errors[] = $this->getErrorsInZinBar();
            }
        }

        $this->driver->switchTo()->defaultContent();
        if(!empty($iframe)) $this->switchToIframe($iframe);
        if(empty($errors)) return;

        return $errors;
    }

    /**
     * Get errors in zinbar.
     *
     * @access public
     * @return array
     */
    public function getErrorsInZinBar()
    {
        $errors = array();

        try
        {
            $parentDiv = $this->driver->findElement(WebDriverBy::xpath('//*[@id="zinbar"]/div/div[3]'));
            $errorDivs = $parentDiv->findElements(WebDriverBy::tagName('div'));

            foreach($errorDivs as $errorDiv)
            {
                $errorInfo = $errorDiv->findElement(WebDriverBy::xpath('div[1]'))->getText();
                $errorLine = $errorDiv->findElement(WebDriverBy::xpath('div[2]/strong'))->getText();
                $errorFile = $errorDiv->findElement(WebDriverBy::xpath('div[2]/span'))->getText();
                $errors[] = "Error: $errorInfo\nLine: $errorLine $errorFile";
            }

            return $errors;
        }
        catch(Exception $e)
        {
            return $errors;
        }
    }

    /**
     * Get errors in page alert.
     *
     * @access public
     * @return array
     */
    public function getErrorsInAlert()
    {
        $errors = array();
        $alerts = $this->driver->findElements(WebDriverBy::cssSelector('pre.alert'));
        foreach($alerts as $alert)
        {
            $alertInput = $alert->findElement(WebDriverBy::tagName('input'))->getAttribute('value');
            $errors[]   = $alert->getText() . $alertInput;
        }

        return $errors;
    }

    /**
     * Wait and retry until condition occured.
     *
     * @param  string  $condition  format: [title|url]:xxx for example: title:地盘 url:a.com
     * @param  int     $seconds    Wait at most seconds
     * @param  int     $interval   Retry every interval
     * @access public
     * @return object
     */
    public function waitUntil($condition, $seconds = 5, $interval = 500)
    {
        if(preg_match('/^(title:|url:).*/', $condition, $matches) !== 0)
        {
            $type      = str_replace(':', '', $matches[1]);
            $condition = str_replace("$type:", '', $condition);

            switch($type)
            {
                case 'title':
                    $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::titleMatches("/$condition/"));
                    break;
                case 'url':
                    $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::urlContains("$condition"));
                    break;
            }
        }
        else
        {
            $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::titleMatches("/$condition/"));
        }

        return $this;
    }

    /**
     * Wait an element appear, or implicit wait.
     *
     * @param  int    $seconds
     * @access public
     * @return object
     */
    public function waitElement($selector, $seconds = 2, $type = 'normal')
    {
        if($type == 'implicit')
        {
            $this->driver->manage()->timeouts()->implicitlyWait($seconds);
        }
        else
        {
            $this->driver->wait($seconds, 500)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($selector)));
        }

        return $this;
    }

    /**
     * Operate element in modal.
     *
     * @param  string    $type
     * @param  string    $value
     * @access public
     * @return void|string
     */
    public function modal($type = '', $value = '')
    {
        if($type == 'text')
        {
            return $this->driver->executeScript("return arguments[0].innerText;", [$this->element]);
        }
        elseif($type == 'sendKeys')
        {
            return $this->driver->executeScript("arguments[0].sendKeys('$value');", [$this->element]);
        }
        else
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[0].click();');
        }
    }

    /**
     * Operate alert modal.
     *
     * @param  string $action
     * @access public
     * @return void
     */
    public function alertModal($action = '')
    {
        if($action == 'text')
        {
            return $this->driver->executeScript('return $(".modal-alert .modal-body div")[0].innerText;');
        }
        elseif($action == 'dismiss')
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[1].click();');
        }
        else
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[0].click();');
        }
    }

    /**
     * Get element's coordinate on page.
     *
     * @access public
     * @return array
     */
    public function getElementCoordinate()
    {
        $x = $this->element->getCoordinates()->onPage()->getX();
        $y = $this->element->getCoordinates()->onPage()->getY();
        return array($x, $y);
    }

    /**
     * select by selecter
     *
     * @param  string $type     value|index|text|ptext:like
     * @param  string $value
     * @access public
     * @return void
     */
    public function select($type, $value)
    {
        $select = new WebDriverSelect($this->element);

        if($type == 'value') $select->selectByValue($value);
        if($type == 'index') $select->selectByIndex($value);
        if($type == 'text')  $select->selectByVisibleText($value);
        if($type == 'ptext') $select->selectByVisiblePartialText($value);
    }

    /**
     * get select value or text
     *
     * @param  string $type     value|text
     * @access public
     * @return void
     */
    public function getSelect($type = '')
    {
        $select = new WebDriverSelect($this->element);

        if($type == 'value') return $select->getFirstSelectedOption()->getAttribute('value');
        return $select->getFirstSelectedOption()->getText();
    }

    /**
     * Scroll the browser scroll bar. By default, scroll to the top.
     *
     * @param  int    $x
     * @param  int    $y
     * @access public
     * @return void
     */
    public function scrollTo($x = 0, $y = 10000)
    {
        $js = "window.scrollTo({$x},{$y})";
        $this->driver->executeScript($js);
    }

    /**
     * Scroll to selector.
     *
     * @access public
     * @return void
     */
    public function scrollToElement()
    {
        $js = "arguments[0].scrollIntoView(false);";
        $arguments = array($this->element);

        $this->driver->executeScript($js, $arguments);
    }

    /**
     * Select a option of picker.
     *
     * @param  string $value
     * @access public
     * @return object
     */
    public function picker($value, $selectNumber = 0)
    {
        $picker = $this->element->findElement(WebDriverBy::xpath('parent::div'));
        $picker->click();
        sleep(1);

        $pickerList = array(
            'search' => '//*[@class="picker-search"]/input',
            'selections' => '//*[@class="picker-selections"]/input',
            'form' =>'//*[@class="form-group-wrapper"]/div[@class="pick pick-pri form-control is-open focus"]',
        );

        foreach($pickerList as $xpath => $xpathValue)
        {
            try
            {
                $pickerInput = $picker->findElement(WebDriverBy::xpath($xpathValue));
                $pickerInput->click();
                $pickerInput->sendKeys(trim($value));
                sleep(1);

                $pickerID = substr($picker->getAttribute('id'), 5);

                if($xpath != 'form')
                {
                    try
                    {
                        $selectXpath = "//*[@id='pick-pop-{$pickerID}']//li[not(contains(@class, 'is-not-match'))]";
                        if($selectNumber) $selectXpath .= "[{$selectNumber}]";
                        $this->driver->findElement(WebDriverBy::xpath("{$selectXpath}//a"))->click();
                    }
                    catch(Exception $xpathException)
                    {
                        $this->driver->findElement(WebDriverBy::xpath("//a[@title='{$value}']"))->click();
                    }
                }
                else
                {
                    $this->driver->findElement(WebDriverBy::xpath("//button[@data-pick-value={$value}]"))->click();
                }
                break;
            }
            catch (Exception $selectionException)
            {
                continue;
            }
        }

        return $this;
    }

    /**
     * Select multi options of picker.
     *
     * @param  array    $values
     * @access public
     * @return void
     */
    public function multiPicker($values)
    {
        $picker = $this->element->findElement(WebDriverBy::xpath('parent::div'));
        $picker->click();
        sleep(1);

        foreach($values as $value)
        {
            $pickerInput = $picker->findElement(WebDriverBy::xpath('//*[@class="picker-multi-selections"]//input'));
            $pickerInput->click();
            $pickerInput->sendKeys(trim($value));
            sleep(1);

            $pickerID = substr($picker->getAttribute('id'), 5);
            $this->driver->findElement(WebDriverBy::xpath("//*[@id='pick-pop-$pickerID']//span[@class='is-match-keys']"))->click();
            $pickerInput->clear();
        }
    }

    /**
     * Select a option of no search picker.
     *
     * @param  string|int $value
     * @param  string     $type ''|date|menu
     * @access public
     * @return void
     */
    public function noSearchPicker($value, $type = '')
    {
        $picker = $this->element->findElement(WebDriverBy::xpath('/parent::div'));
        $picker->click();

        $pickerID = $picker->getAttribute('id');

        if($type == 'date')
        {
            sleep(1);
            return $picker->findElement(WebDriverBy::xpath('/input'))->sendKeys($value);
        }
        elseif($type == 'menu')
        {
            return $this->driver->findElement(WebdriverBy::xpath("//*[@id='pick-pop-$pickerID']/menu/menu/li[$value]"))->click();
        }

        return $this->driver->findElement(WebdriverBy::xpath("//*[@id='pick-pop-$pickerID']/div/button[$value]"))->click();
    }

    /**
     * Search value in search form.
     *
     * @param  array  $searchList
     * @param  string $groupAndOr and|or
     * @access public
     * @return void
     */
    public function search($searchList, $groupAndOr = '')
    {
        $this->waitElement('//button[@data-toggle="searchform"]')->getElement('//button[@data-toggle="searchform"]')->click();

        $searchContainer  = '//div[contains(@class, "search-form-container")]/form';
        $searchSquareBtn  = $searchContainer . '/div[2]/div//button[contains(@class, "square")]';
        $leftSearchGroup  = $searchContainer . '/div/div/table/tbody';
        $rightSearchGroup = $searchContainer . '/div/div[3]/table/tbody';
        $searchBtn        = $searchContainer . '/div[2]/button';
        $restBtn          = $searchContainer . '/div[2]/button[2]';

        $this->waitElement($searchContainer);
        $this->waitElement($restBtn)->getElement($restBtn)->click();

        if($groupAndOr)
        {
            $this->getElement('//*[@name="groupAndOr"]');
            if($groupAndOr == 'and')
            {
                $this->noSearchPicker(1, 'menu');
            }
            else
            {
                $this->noSearchPicker(2, 'menu');
            }
        }

        if(count($searchList) > 2) $this->getElement($searchSquareBtn)->click();

        foreach($searchList as $key => $value)
        {
            $index   = $key + 1;
            $trIndex = floor($key / 2) + 1;
            list($field, $operator, $value) = explode(',', $value);

            $fieldXpath    = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[2]/div/input" : $leftSearchGroup . "/tr[$trIndex]/td[2]/div/input";
            $operatorXpath = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[3]/div" : $leftSearchGroup . "/tr[$trIndex]/td[3]/div";
            $valueXpath    = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[4]" : $leftSearchGroup . "/tr[$trIndex]/td[4]";

            $this->getElement($fieldXpath)->picker($field);
            sleep(1);
            $this->getElement($operatorXpath)->click();
            $operatorID = $this->element->getAttribute('id');
            $operatorID = substr($operatorID, 5);
            $this->getElement("//*[@id='pick-pop-$operatorID']/menu/menu//*[contains(text(), '$operator')]")->click();
            sleep(1);

            $valueTage = $this->driver->findElement(WebDriverBy::xpath($valueXpath . '/*[1]'))->getTagName();
            if($valueTage == 'input')
            {
                $this->getElement("$valueXpath/input")->setValue($value);
            }
            else
            {
                $this->getElement("$valueXpath//input")->picker($value);
            }
        }

        $this->getElement($searchBtn)->click();
    }

    /**
     * Set date in datePicker.
     *
     * @param  string    $value
     * @access public
     * @return object
     */
    public function datePicker($value)
    {
        $name = $this->element->getAttribute('name');
        if(!$name) return false;

        if(strpos($name, '[') !== false) $name = '"' . $name . '"';
        $this->driver->executeScript("return $('[name={$name}]').zui('datePicker').$.setValue('$value')");
        return $this;
    }

    /**
     * 获取picker控件的选项
     * Get picker items.
     *
     * @param  string  $pickerName
     * @access public
     * @return void
     */
    public function getPickerItems($pickerName)
    {
        return $this->driver->executeScript('return $("[name=' . $pickerName . ']").zui("picker").options.items;');
    }
}
