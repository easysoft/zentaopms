<?php
class result
{
    public $status;
    public $pageObject;
    public $pageInfo = array();
    public $errors = array();

    public function setPage(&$page)
    {
        $this->pageObject = $page;
    }

    public function getPageInfo()
    {
        if(empty($this->pageObject)) return $this->pageInfo;

        $url   = $this->pageObject->getUrl();
        if(!$url) return $this->pageInfo;

        $title = $this->pageObject->getTitle();
        $parseURL = parse_url($url);
        if(isset($parseURL['query']))
        {
            $query = $parseURL['query'];
            parse_str($query, $queryParams);
            $module = $queryParams['m'];
            $method = $queryParams['f'];
        }
        else
        {
            $path = $parseURL['path'];
            $pathParts = explode('/', trim($path, '/'));
            $module = $pathParts[0];
            $method = $pathParts[1];
        }

        $this->pageInfo['url']    = $url;
        $this->pageInfo['title']  = $title;
        $this->pageInfo['module'] = $module;
        $this->pageInfo['method'] = $method;

        return $this->pageInfo;
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
        $result['errors'] = $this->errors;
        $result['page']   = $this->pageObject;
        $result['url']    = $this->pageInfo['module'] . '-' . $this->pageInfo['method'];
        $result['status'] = $this->status;

        return $param ? zget($result, $param, '') : $result;
    }
}

$result = new result();
