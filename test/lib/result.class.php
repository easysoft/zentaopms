<?php
class result
{
    public $status;
    public $message;
    public $url;
    public $module;
    public $method;

    public $pageObject;
    public $errors = array();

    /**
     * Set page info.
     *
     * @param  int    $page
     * @access public
     * @return void
     */
    public function setPage(&$page)
    {
        $this->pageObject = $page;
    }

    /**
     * Get results of the case.
     *
     * @access public
     * @return void
     */
    public function get($param = '')
    {
        if(!empty($this->errors))
        {
            foreach($this->errors as $error) echo str_replace("\n", '', $error) . PHP_EOL;

            return $param ? '' : array();
        }

        $result = array();
        $result['errors']  = $this->errors;
        $result['page']    = $this->pageObject;
        $result['url']     = $this->url;
        $result['method']  = $this->method;
        $result['module']  = $this->module;
        $result['status']  = $this->status;
        $result['message'] = $this->message;

        return $param ? zget($result, $param, '') : $result;
    }
}

$result = new result();
