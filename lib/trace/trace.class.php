<?php
declare(strict_types=1);
/**
 * The trace class file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     trace
 * @link        https://www.zentao.net
 */

class trace
{
    /**
     * @var string[]
     */
    protected $types = array(
        'Request'   => '请求',
        'Files'     => '文件',
        'SQL Query' => 'SQL 查询'
    );

    /**
     * @var array
     */
    public $trace = array();

    protected $app;

    protected $dao;

    public function __construct()
    {
        global $app, $dao;
        $this->app = $app;
        $this->dao = $dao;
    }

    /**
     * 获取请求信息。
     * Get request info.
     *
     * @return void
     */
    public function getRequestInfo(): void
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

    /**
     * 获取请求加载的文件。
     * Get request files.
     *
     * @return void
     */
    public function getRequestFiles(): void
    {
        $this->trace['Files'] = get_included_files();
    }

    /**
     * 获取请求的 SQL 语句。
     * Get request SQLs.
     *
     * @return void
     */
    public function getRequestSqls(): void
    {
        $this->trace['SQL Query'] = dao::$querys;
    }

    /**
     * 生成请求 Trace。
     * Generate request trace.
     *
     * @return array
     */
    public function getTrace(): array
    {
        $this->getRequestInfo();
        $this->getRequestFiles();
        $this->getRequestSqls();
        return $this->trace;
    }

    /**
     * 输出 Trace。
     * Output trace.
     *
     * @return string
     */
    public function output(): string
    {
        $this->getTrace();
        $lines = '';
        foreach($this->trace as $type => $content)
        {
            if($type == 'SQL Explain') continue;
            $lines .= $this->console($type, empty($content) ? array() : (array)$content);
        }

        $lines .= $this->printSQLProfile();

        $js = <<<JS

<script type='text/javascript'>
{$lines}
</script>
JS;
        return $js;
    }

    /**
     * 拼接需要输出到 console 的内容。
     * Concat the content to output to console.
     *
     * @param string $type
     * @param array  $content
     * @return string
     */
    protected function console(string $type, array $content): string
    {
        $traceTabs = array_keys($this->types);
        $line      = array();
        $line[]    = $type == $traceTabs[0] ? "console.group('{$type}');" : "console.groupCollapsed('{$type}');";

        foreach($content as $key => $item)
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

    /**
     * 拼接 SQL Profile。
     *
     * @return string
     */
    protected function printSQLProfile(): string
    {
        $lines = array();

        $profiling = $this->dao->dbh->query('SHOW PROFILES')->fetchAll(PDO::FETCH_ASSOC);
        if(empty($profiling)) return '';

        $lines[] = 'console.groupCollapsed("SQL Profile")';
        $lines[] = 'console.table(' . json_encode($profiling) . ')';
        $lines[] = 'console.groupEnd()';

        return implode(PHP_EOL, $lines);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->getTrace());
    }
}
