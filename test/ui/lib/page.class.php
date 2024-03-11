<?php
class page
{
    public $driver;
    public $xpathName;
    public $doms;
    public $xpath;
    public $timeout = 5;

    public function __construct($driver)
    {
        $this->driver = $driver;
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
}
