<?php
class coverage
{
    /**
     * __construct
     *
     * @access private
     * @return void
     */
    public function __construct()
    {
        global $zentaoRoot;
        $this->zentaoRoot       = $zentaoRoot;
        $this->traceFile        = '';
        $this->unfilteredTraces = array('control.php', 'zen.php', 'model.php', 'tao.php');

        $this->initTraceFile();
    }

    /**
     * Init trace file.
     *
     * @access private
     * @return bool
     */
    public function initTraceFile(): bool
    {
        $tracePath     = $this->zentaoRoot . "/tmp/coverage/";
        $backtrace     = debug_backtrace();
        $runFile       = $backtrace[count($backtrace)-1]['file'];
        $moduleName    = basename(dirname(dirname(dirname($runFile))));
        $testModelName = basename(dirname($runFile));

        $this->traceFile = $tracePath . $moduleName . '_' . $testModelName. '_' . basename($runFile, '.php') . '.json';
        if(!is_dir($tracePath)) mkdir($tracePath, 0777, true);
        if(!is_file($this->traceFile)) file_put_contents($this->traceFile, json_encode(array()));

        return true;
    }

    /**
     * Start code coverage.
     *
     * @access public
     * @return void
     */
    public function startCodeCoverage(): void
    {
        xdebug_start_code_coverage();
    }

    /**
     * Save traces and restart code coverage.
     *
     * @access public
     * @return void
     */
    public function saveAndRestartCodeCoverage(): void
    {
        $traces = xdebug_get_code_coverage();
        $this->saveTraces($traces);

        xdebug_stop_code_coverage();
        xdebug_start_code_coverage();
    }

    /**
     * Load saved traces from file.
     *
     * @param  string  $key
     * @access public
     * @return array|string
     */
    public function loadTraceFromFile(string $key = ''): array|string
    {
        $report = json_decode(file_get_contents($this->traceFile), true);
        if($key == '') return $report;
        return isset($report[$key]) ? $report[$key] : array();
    }

    /**
     * Get local trace file path.
     *
     * @access public
     * @return string
     */
    public function getTraceFile(): string
    {
        return $this->traceFile;
    }

    /**
     * Reset traceFile.
     *
     * @access public
     * @return bool
     */
    public function reset()
    {
        if(!is_file($this->traceFile)) return true;
        return exec("rm $this->traceFile") !== false;
    }

    /**
     * Merge traces form local file and this called trace.
     *
     * @param  array  $traces
     * @access private
     * @return array
     */
    private function mergeTraces(array $traces): array
    {
        $savedTraces = $this->loadTraceFromFile('traces');

        if(!is_array($savedTraces)) return $traces;

        foreach($traces as $module => $moduleTraces)
        {
            if(!isset($savedTraces[$module]))
            {
                $savedTraces[$module] = $moduleTraces;
                continue;
            }

            foreach($moduleTraces as $file => $fileTraces)
            {
                if(!isset($savedTraces[$module][$file]))
                {
                    $savedTraces[$module][$file] = $fileTraces;
                    continue;
                }
                else
                {
                    $savedTraces[$module][$file] += $fileTraces;
                }
            }
        }

        return $savedTraces;
    }

    /**
     * Save traces to file.
     *
     * @param  array  $traces
     * @access public
     * @return bool
     */
    private function saveTraces(array $traces): bool
    {
        $traces = $this->filterTraces($traces);
        $traces = $this->groupTraceByModule($traces);
        $traces = $this->mergeTraces($traces);

        $log = new stdclass;
        $log->time    = date('Y-m-d H:i:s');
        $log->ztfPath = getenv('ZTF_REPORT_DIR');
        $log->traces  = $traces;

        return file_put_contents($this->traceFile, json_encode($log));
    }

    /**
     * Filter traces by file name.
     *
     * @param  array   $traces
     * @access private
     * @return array
     */
    private function filterTraces(array $traces): array
    {
        foreach($traces as $filePath => $fileTrace)
        {
            $fileName = basename($filePath);
            if(!in_array($fileName, $this->unfilteredTraces)) unset($traces[$filePath]);
        }

        return $traces;
    }

    /**
     * Group traces by module.
     *
     * @param  array   $traces
     * @access private
     * @return array
     */
    private function groupTraceByModule(array $traces): array
    {
        $groupedTraces = array();

        foreach($traces as $filePath => $fileTrace)
        {
            $moduleName = $this->getModuleByFilePath($filePath);
            $fileName   = basename($filePath);

            $groupedTraces[$moduleName][$fileName] = $fileTrace;
        }

        return $groupedTraces;
    }

    /**
     * Get current fileTrace belog to which module.
     *
     * @param  string  filePath    eg: /home/liuyongkai/sites/local/max/max41/module/bug/model/bug.php
     * @access private
     * @return string  moduleName  eg: bug
     */
    private function getModuleByFilePath($filePath): string
    {
        $moduleName = '';
        preg_match('/\/module\/(\w+)\//', $filePath, $matches);
        $moduleName = $matches[1];

        return $moduleName;
    }

    /**
     * Generate module stats report.
     *
     * @param  array  $traces
     * @access public
     * @return string
     */
    private function genModuleStatsReport(array $traces): string
    {
        $summaryTable  = <<<EOT
<table border=1 id='summaryTable'>
  <thead>
    <tr>
        <th>模块</th>
        <th>执行行数</th>
        <th>可执行行数</th>
        <th>总行数</th>
        <th>control</th>
        <th>zen</th>
        <th>model</th>
        <th>tao</th>
        <th>模块</th>
    </tr>
  </thead>
  <tbody>
EOT;

        foreach($traces as $module => $moduleTraces)
        {
            $summaryTable .= $this->genStatsTableByModule($module, $moduleTraces);
        }
        $summaryTable .= '</tbody></table>' . PHP_EOL;

        return $summaryTable;
    }

    /**
     * Generate stats table by module.
     *
     * @param  string  $module
     * @param  array   $moduleTraces
     * @access private
     * @return void
     */
    private function genStatsTableByModule($module, $moduleTraces)
    {
        $executedLines      = 0;
        $effectiveLines     = 0;
        $totalLines         = 0;
        $coveragePercent    = 0;
        $moduleSummaryTable = '';
        $summaryTable       = '';

        foreach($moduleTraces as $file => $fileTraces)
        {
            $fileName            = str_replace('.php', '', $file);
            $file                = $this->zentaoRoot . '/module/' . $module . '/' . $file;
            $content             = file_get_contents($file);
            $fileExecutedLines   = count($fileTraces);
            $fileEffectiveLines  = $this->getEffectiveLines($content);
            $fileTotalLines      = substr_count($content, PHP_EOL);
            $fileCoveragePercent = array();
            $fileCoveragePercent[$fileName] = ($fileEffectiveLines > 0) ? round($fileExecutedLines / $fileEffectiveLines * 100, 2) : 0;

            $executedLines  += $fileExecutedLines;
            $effectiveLines += $fileEffectiveLines;
            $totalLines     += $fileTotalLines;
        }

        $coveragePercent = ($effectiveLines > 0) ? round($executedLines / $effectiveLines * 100, 2) : 0;

        $summaryTable .= "<th>$module</th>" . PHP_EOL;
        $summaryTable .= '<td>' . $executedLines . '</td>' . PHP_EOL;
        $summaryTable .= "<td>$effectiveLines</td>" . PHP_EOL;
        $summaryTable .= "<td>$totalLines</td>" . PHP_EOL;
        foreach($this->unfilteredTraces as $fileType)
        {
            $fileType = str_replace('.php', '', $fileType);

            $summaryTable .= isset($fileCoveragePercent[$fileType]) ? "<td><a href='?module=$module&file=$fileType'>" . $fileCoveragePercent[$fileType] . '%</a></td>' . PHP_EOL : '<td>0%</td>' . PHP_EOL;
        }
        $summaryTable .= "<td>$coveragePercent%</td>" . PHP_EOL;
        $summaryTable .= '</tr>' . PHP_EOL;
        $summaryTable .= $moduleSummaryTable;

        return $summaryTable;
    }

    /**
     * Generate coverage report by module.
     *
     * @param  string  $module
     * @param  string  $file
     * @param  array   $fileTraces
     * @access private
     * @return string
     */
    private function genCoverageTableByFile($module, $file, $fileTraces): string
    {
        $file = $this->zentaoRoot . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $file;
        $coverageTable = <<<EOT
<table border="1">
  <caption id="$file">$file</caption>
  <thead>
    <tr>
        <th>行号</th>
        <th>代码</th>
        <th>调用次数</th>
    </tr>
</thead>
<tbody>
EOT;
        $content = file($file);

        foreach($content as $line => $code)
        {
            /* The function file() give the line number start from 0, so we offset to end one more on index.  */
            $isCalled    = in_array($line + 1, array_keys($fileTraces));
            $calledTimes = $isCalled ? '<span style="color: green;">' . $fileTraces[$line + 1] . '</span>' : '<span style="color: red;">0</span>';

            $coverageTable .= '<tr>' . PHP_EOL;
            $coverageTable .= '<td style="text-align: center;">' . $line + 1 . '</td>' . PHP_EOL;
            $coverageTable .= "<td style='text-align: left;'><code>" . htmlspecialchars($code) . "</code></td>" . PHP_EOL;
            $coverageTable .= "<td style='text-align: center;'>$calledTimes</td>" . PHP_EOL;
            $coverageTable .= '</tr>' . PHP_EOL;
        }
        $coverageTable .= '</tbody></table>' . PHP_EOL;

        return $coverageTable;
    }

    /**
     * Get traces list from save path.
     *
     * @param  string            $filePath
     * @param  string            $key
     * @return array|string|bool
     *
     */
    public function loadTraceFromFiles(string $filePath, string $key = ''): array|string|bool
    {
        $tracesFiles = glob("{$filePath}/*.json");
        if(!$tracesFiles) return false ;

        $tracesList  = array();
        foreach($tracesFiles as $file)
        {
            $traces = json_decode(file_get_contents($file), true);
            $tracesList = array_merge_recursive($tracesList, $traces);
        }

        $tracesList['time']    = $tracesList['time'][count($tracesList['time'])-1];
        $tracesList['ztfPath'] = $tracesList['ztfPath'][count($tracesList['ztfPath'])-1];
        if($key == '') return $report;
        return isset($tracesList[$key]) ? $tracesList[$key] : array();
    }


    /**
     * Generate summary report.
     *
     * @param  string $module
     * @param  string $file
     * @return string
     */
    public function genSummaryReport(string $module='', string $file=''): string
    {
        /* Get trace from file. */
        $tracesPath = $this->zentaoRoot . '/tmp/coverage';
        $traces     = $this->loadTraceFromFiles($tracesPath, 'traces');

        /* Generate report. */
        $reportHtml = empty($file) ? '<style>td { border: 1px solid #ccc;  padding: 8px;  text-align: center;}</style>' . PHP_EOL: '<style>td { border: 1px solid #ccc;  padding: 8px;}</style>' . PHP_EOL;
        if(empty($file))
        {
            $reportHtml .= $this->genModuleStatsReport($traces);
        }
        else
        {
            $file       .= '.php';
            $reportHtml .= $this->genCoverageTableByFile($module, $file, $traces[$module][$file]);
        }
        $reportHtml .= '</body></html>';

        return $reportHtml;
    }

    /**
     * Get ztf report.
     *
     * @access public
     * @return object
     */
    public function getZtfReport(): object|false
    {
        $reportFile = $this->getZtfReportFile();
        if(!$reportFile) return false;

        $content = file_get_contents($reportFile);
        $report  = json_decode($content);
        if(!is_object($report) || !isset($report->funcResult)) return false;

        $report->logFile    = $reportFile;
        $report->funcResult = '';
        $report->log        = '';

        $report->time        = date('Y-m-d H:i:s', $report->endTime);
        $report->passPercent = round($report->pass / $report->total * 100, 2);
        $report->failPercent = round($report->fail / $report->total * 100, 2);
        $report->skipPercent = round($report->skip / $report->total * 100, 2);
        return $report;
    }

    /**
     * Get ztf report.
     *
     * @access public
     * @return string|false
     */
    public function getZtfReportFile(): string|false
    {
        $latestTime = 0;
        $latestFile = '';
        $tracePath  = $this->zentaoRoot . "/tmp/coverage/";
        $reportPath = $this->loadTraceFromFiles($tracePath, 'ztfPath');

        exec("find $reportPath -type f -name result.json", $files, $returnCode);
        if($returnCode !== 0 || empty($files)) return false;

        foreach($files as $file)
        {
            if(is_file($file) && filemtime($file) > $latestTime)
            {
                $latestFile = $file;
                $latestTime = filemtime($file);
            }
        }

        return $latestFile;
    }

    /**
     * Get the effective lines of code.
     *
     * @param  int    $content
     * @access public
     * @return int
     */
    private function getEffectiveLines(string $content): int
    {
        $content = preg_replace('#/\*.*?\*/#s', '', $content);

        $lines   = 0;
        $content = preg_replace('/\r\n|\r/', "\n", $content);
        $content = trim($content);
        $content = explode("\n", $content);
        foreach($content as $line)
        {
            if(trim($line) === '') continue;
            if(trim($line) === '{') continue;
            if(trim($line) === '}') continue;
            $lines++;
        }

        return $lines;
    }
}
