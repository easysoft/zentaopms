<?php
class page extends webDriver
{
    public $doms;
    public $xpathName;
    public $timeout = 5;

    public function __construct()
    {
        $this->doms      = array(
            'menuMoreNav' => '//*[@id="menuMoreNav"]/li[2]/a'
        );
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
        $this->waitElement($xpath, $this->timeout)->getElement($xpath)->click();
        $this->wait(1)->switchToIframe("appIframe-{$appTab}");

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
        $this->waitElement($xpath, $this->timeout)->getElement($xpath)->click();

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
        $this->waitElement($xpath, $this->timeout)->getElement($xpath);

        return $this;
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
        if($name == 'dom') return $this;

        $this->xpathName = $name;
        if(isset($this->doms[$name]))
        {
            $xpath = $this->doms[$name];
        }
        else
        {
            $xpath = $this->generateXPath($name);
        }

        $this->waitElement($xpath, $this->timeout)->getElement($xpath);
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
            $this->getElement($element);
        }
        catch (Exception $e)
        {
            try
            {
                $element = '//*[@name="' . $name . '[]' . '"]';
                $this->getElement($element);
            }
            catch (Exception $e)
            {
                $element = '//*[@id="' . $name . '"]';
                try
                {
                    $this->getElement($element);
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
        $elements = $this->getElementListInPage('xpath://div[contains(@class, "form-tip")]');

        $tips = array();
        foreach($elements as $element)
        {
            $id = $element->getAttribute('id');
            $tips[$id] = $element->getText();
        }

        return $tips;
    }
}
