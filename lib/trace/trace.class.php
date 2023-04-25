<?php

class trace
{
    protected $config = array(
        'tabs' => array(
            'request'    => '请求',
            'file'       => '文件',
            'sqlQuery'   => 'SQL 查询',
            'sqlExplain' => 'SQL Explain',
        ),
        'trace' => array()
    );

    protected $app;

    protected $dao;

    public function __construct()
    {
        global $app, $dao;
        $this->app = $app;
        $this->dao = $dao;
    }

    public function getRequestInfo()
    {
        $this->config['trace']['request'] = array(
            'start'    => date('Y-m-d H:i:s', $this->app->startTime),
            'url'      => $this->app->getURI(true),
            'protocol' => $this->app->server->server_protocol,
            'method'   => $this->app->server->request_method,
            'timeUsed' => round(getTime() - $this->app->startTime, 4) * 1000,
            'memory'   => round(memory_get_peak_usage() / 1024, 1),
            'querys'   => count(dao::$querys),
            'caches'   => count(dao::$cache),
            'files'    => count(get_included_files()),
            'session'  => session_id()
        );
    }

    public function getRequestFiles()
    {
        $this->config['trace']['file'] = get_included_files();
    }

    public function getRequestSqls()
    {
        $explain = array();
        foreach(dao::$querys as $query)
        {
            $explain[] = $this->dao->explain($query, false);
        }
        $this->config['trace']['sqlQuery']   = dao::$querys;
        $this->config['trace']['sqlExplain'] = $explain;
    }

    public function getTrace()
    {
        $this->getRequestInfo();
        $this->getRequestFiles();
        $this->getRequestSqls();
        return $this->config;
    }

    public function output()
    {
        $this->getTrace();
        $lines = '';
        foreach($this->config['trace'] as $type => $content)
        {
            if($type == 'sqlExplain') continue;
            $lines .= $this->console($type, empty($content) ? array() : $content);
        }
        $js = <<<JS

<script type='text/javascript'>
{$lines}
</script>
JS;
        return $js;
    }

    protected function console(string $type, $content)
    {
        $type       = strtolower($type);
        $traceTabs  = array_keys($this->config['tabs']);
        $line       = array();
        $line[]     = $type == $traceTabs[0] ? "console.group('{$type}');" : "console.groupCollapsed('{$type}');";

        foreach((array) $content as $key => $item)
        {
            switch ($type) {
                case 'sqlquery':
                    $msg    = str_replace("\n", '\n', addslashes($item));
                    $style  = "color:#009bb4;";
                    $line[] = "console.log(\"%c{$msg}\", \"{$style}\");";

                    $explain = array();
                    foreach($this->config['trace']['sqlExplain'][$key] as $explainKey => $explainItem)
                    {
                        $explain[] = $explainKey . ': ' . $explainItem;
                    }

                    $msg    = implode(', ', $explain);
                    $style  = "color:red;";
                    $line[] = "console.log(\"%c{$msg}\", \"{$style}\");";
                    break;
                default:
                    $item   = is_string($key) ? $key . ' ' . $item : $key + 1 . ' ' . $item;
                    $msg    = json_encode($item);
                    $line[] = "console.log({$msg});";
                    break;
            }
        }
        $line[] = "console.groupEnd();";
        return implode(PHP_EOL, $line);
    }

    public function __toString(): string
    {
        return json_encode($this->getTrace());
    }
}
