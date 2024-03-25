<?php
class page
{
    public $driver;
    public $lang;
    public $xpathName;
    public $doms;
    public $xpath;
    public $timeout = 5;

    public function __construct()
    {
        global $driver, $lang;
        $this->driver = $driver;
        $this->lang   = $lang;
        $this->doms   = array(
            'menuMoreNav' => '//*[@id="menuMoreNav"]/li[2]/a'
        );
    }

    /**
     * Get element of mainNav menu.
     *
     * @param  string $app
     * @access public
     * @return object
     */
    public function mainNav($app)
    {
        $xpath = "//li[@data-app='$app']/a";
        $this->driver->waitElement($xpath, $this->timeout)->getElement($xpath);

        return $this;
    }

    /**
     * Get element of navbar.
     *
     * @param  string $nav
     * @access public
     * @return object
     */
    public function navbar($nav)
    {
        $xpath = "//a[@data-id='$nav']";
        $this->driver->waitElement($xpath, $this->timeout)->getElement($xpath);

        return $this;
    }

    /**
     * Get xpath of the zTable.
     *
     * @param  string    $colName
     * @param  string    $index
     * @access public
     * @return object
     */
    public function dTable($colName, $index)
    {
        $xpath = "//div[@class='dtable-cells-container']//[@data-col='$colName'][$index]";

        return $xpath;
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
        $this->driver->waitElement($xpath, $this->timeout)->getElement($xpath);

        return $this;
    }

    /**
     * Get the xpath of picker.
     *
     * @param  string    $value
     * @access public
     * @return object
     */
    public function picker($value)
    {
        $this->driver->picker($this->xpath, $value);

        return $this;
    }

    /**
     * Get the xpath of multiPicker.
     *
     * @param  string    $value
     * @access public
     * @return void
     */
    public function multiPicker($value)
    {
        $this->driver->multiPicker($this->xpath, $value);

        return $this;
    }

    /**
     * Use datePicker in webDriver.
     *
     * @param  string    $value
     * @access public
     * @return void
     */
    public function datePicker($value)
    {
        $this->driver->datePicker($this->xpathName, $value);
    }

    /**
     * Convert incoming page elements to xpath.
     *
     * @param  string $name
     * @access public
     * @return void
     */
    public function __get($name)
    {
        $this->xpathName = $name;
        if(isset($this->doms[$name]))
        {
            $xpath = $this->doms[$name];
        }
        else
        {
            $xpath = $this->generateXPath($name);
        }

        $this->xpath = $xpath;
        $this->driver->waitElement($xpath, $this->timeout)->getElement($xpath);
        return $this;
    }

    /**
     * Get xpath of an element.
     *
     * @param  string    $name
     * @access public
     * @return void
     */
    public function generateXPath($name)
    {
        $element = '//*[@name="' . $name . '"]';

        try
        {
            $this->driver->getElement($element);
        }
        catch (Exception $e)
        {
            try
            {
                $element = '//*[@name="' . $name . '[]' . '"]';
                $this->driver->getElement($element);
            }
            catch (Exception $e)
            {
                $element = '//*[@id="' . $name . '"]';
                try
                {
                    $this->driver->getElement($element);
                }
                catch (Exception $e)
                {
                    $element = $name;
                }
            }
        }

        return $element;
    }

    /**
     * Get tips in page form.
     *
     * @access public
     * @return array
     */
    public function getFormTips()
    {
        $elements = $this->driver->getElementList('xpath://div[contains(@class, "form-tip")]');

        $tips = array();
        foreach($elements as $element)
        {
            $id = $element->getAttribute('id');
            $tips[$id] = $element->getText();
        }

        return $tips;
    }

    /**
     * Methods in the public webdriver class.
     *
     * @param  string $method
     * @param  array  $params
     * @access public
     * @return void
     */
    public function __call($method, $params = array())
    {
        if(method_exists($this->driver, $method)) return call_user_func_array(array($this->driver, $method), $params);

        return false;
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
                $this->$type($value)->click();
            }
            catch(Exception $e)
            {
                $this->menuMoreNav->click();
                $this->mainNav($value)->click();
            }

            $this->wait(1)->switchTo('appIframe-' . $value);
        }

        if($type == 'switchToUrl') $this->$type($value, true);

        return $this;
    }
}
