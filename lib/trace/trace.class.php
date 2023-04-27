<?php

class trace
{
    protected $types = array(
        'Request'     => '请求',
        'Files'       => '文件',
        'SQL Query'   => 'SQL 查询',
        'SQL Explain' => 'SQL Explain',
    );

    public $trace = array();

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
        $this->trace['Request'] = array(
            'start'    => date('Y-m-d H:i:s', (int)$this->app->startTime),
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
        $this->trace['Files'] = get_included_files();
    }

    public function getRequestSqls()
    {
        $explain = array();
        /**
        foreach(dao::$querys as $query)
        {
            $explain[] = $this->dao->explain($query, false);
        }
        */
        $this->trace['SQL Query']   = dao::$querys;
        $this->trace['SQL Explain'] = $explain;
    }

    public function getTrace()
    {
        $this->getRequestInfo();
        $this->getRequestFiles();
        $this->getRequestSqls();
        return $this->trace;
    }

    public function output()
    {
        $this->getTrace();
        $lines = '';
        foreach($this->trace as $type => $content)
        {
            if($type == 'SQL Explain') continue;
            $lines .= $this->console($type, empty($content) ? array() : $content);
        }

        $lines .= $this->printSQLProfile();

        $js = <<<JS

<script type='text/javascript'>
{$lines}
</script>
JS;
        return $js;
    }

    protected function console(string $type, $content)
    {
        $traceTabs = array_keys($this->types);
        $line      = array();
        $line[]    = $type == $traceTabs[0] ? "console.group('{$type}');" : "console.groupCollapsed('{$type}');";

        foreach((array) $content as $key => $item)
        {
            switch ($type) {
                case 'SQL Query':
                    $msg    = str_replace("\n", '\n', addslashes($item));
                    $style  = "color:#009bb4;";
                    $line[] = "console.log(\"%c{$msg}\", \"{$style}\");";

                    $explain = array();
                    if(!empty($this->trace['SQL Explain']))
                    {
                        foreach($this->trace['SQL Explain'][$key] as $explainKey => $explainItem)
                        {
                            $explain[] = $explainKey . ': ' . $explainItem;
                        }
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

    protected function printSQLProfile()
    {
        $lines = array();

        $profiling = $this->dao->dbh->query('SHOW PROFILES')->fetchAll(PDO::FETCH_ASSOC);
        if(empty($profiling)) return '';

        $lines[] = 'console.groupCollapsed("SQL Profile")';
        $lines[] = 'console.table(' . json_encode($profiling) . ')';
        $lines[] = 'console.groupEnd()';

        return implode(PHP_EOL, $lines);
    }

    public function __toString(): string
    {
        return json_encode($this->getTrace());
    }
}
