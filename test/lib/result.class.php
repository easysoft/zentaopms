<?php
class result
{
    public $status;
    public $message;
    public $url;

    public $pageObject;
    public $pageInfo = array();
    public $errors = array();

    public function setPage(&$page)
    {
        $this->pageObject = $page;
        $page->getUrl();
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
        $result['status']  = $this->status;
        $result['message'] = $this->message;

        return $param ? zget($result, $param, '') : $result;
    }
}

$result = new result();
