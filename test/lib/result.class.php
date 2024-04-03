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
     * Set success result.
     *
     * @access public
     * @return object
     */
    function success()
    {
        $this->status = 'SUCCESS';

        return $this->response();
    }

    /**
     * Set failure result.
     *
     * @param  string    $message
     * @access public
     * @return object
     */
    function failed($message)
    {
        $this->status  = 'FAILED';
        $this->message = $message;
        if(!empty($this->pageObject)) $this->errors = $this->pageObject->dom->getErrorsInPage();

        return $this->response();
    }

    /**
     * Get response of the test case.
     *
     * @access public
     * @param  string $param
     * @return void
     */
    public function response($param = '')
    {
        if(!empty($this->errors))
        {
            foreach($this->errors as $error)
            {
                foreach($error as $message) echo str_replace("\n", '', $message) . PHP_EOL;
            }

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
