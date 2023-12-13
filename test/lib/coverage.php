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
        $this->backtrace        = debug_backtrace()[count(debug_backtrace())-1];
        $this->runFile          = $this->backtrace['file'];
        $this->moduleName       = basename(dirname(dirname(dirname($this->runFile))));
        $this->testType         = basename(dirname($this->runFile));
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
        $tracePath = $this->zentaoRoot . "/tmp/coverage/";

        $this->traceFile = $tracePath . $this->moduleName . '_' . $this->testType . '_' . basename($this->runFile, '.php') . '.json';
        if(!is_dir($tracePath)) mkdir($tracePath, 0777, true);
        if(!is_file($this->traceFile))
        {
            if(is_writable($tracePath)) file_put_contents($this->traceFile, json_encode(array()));
        }

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
     * Save traces to file.
     *
     * @param  array   $traces
     * @access private
     * @return bool
     */
    private function saveTraces(array $traces): bool
    {
        if(!file_exists($this->traceFile)) return false ;

        $traces = $this->filterTraces($traces);
        $traces = $this->groupTraceByModule($traces);
        $traces = $this->computeMethodCoverage($traces);

        $log = new stdclass;
        $log->time    = date('Y-m-d H:i:s');
        $log->ztfPath = getenv('ZTF_REPORT_DIR');
        $log->traces  = $traces;
        if(!$log->traces) return false;

        return file_put_contents($this->traceFile, json_encode($log));
    }

    /**
     * Compute the line coverage test method.
     * @param  array      $traces
     * @access private
     * @return array|false
     */
    private function computeMethodCoverage(array $traces): array|false
    {
        if(!isset($traces['executeLines'] )) return false;
        $executeLines = $traces['executeLines'];

        $testMethod  = $this->getMethodInfo();
        $methodLines = $this->getMethodLines();
        $startLine   = (int)$testMethod->getStartLine();
        $endLine     = (int)$testMethod->getEndLine();
        foreach($executeLines as $executeLine => $executeCount)
        {
            if((int)$executeLine < $startLine || (int)$executeLine > $endLine) unset($executeLines[$executeLine]);
        }
        $traces['executeLines'] = $executeLines;

        $methodCoverage = round(count($executeLines)/$methodLines, 2);
        $traces['coverage'] = $methodCoverage === true ? 1 : $methodCoverage;
        if($traces['coverage'] > 1) $traces['coverage'] = 1;
        return $traces;
    }

    /**
     * Test method for total number of rows.
     *
     * @access private
     * @return int
     */
    private function getMethodLines(): int
    {
        $testMethod = $this->getMethodInfo();
        $funcFile   = $this->getClassFile();

        $startLine = $testMethod->getStartLine();
        $endLine   = $testMethod->getEndLine();
        $funcLines = array_slice(explode(PHP_EOL, file_get_contents($funcFile)), $startLine, $endLine - $startLine);
        $lines     = $this->getEffectiveLines(implode(PHP_EOL, $funcLines));

        return $lines;
    }

    /**
     * Get test method info.
     *
     * @access private
     * @return object
     */
    private function getMethodInfo(): object
    {
        $class = $this->getClassInfo();
        foreach($class->getMethods() as $method)
        {
            $methodName = $method->getName();
            if(strtolower($methodName) == basename($this->runFile, '.php')) $testMethod = $method;
        }

        return $testMethod;
    }

    /**
     * Get the total number of methods in class.
     *
     * @param  string  $moduleName
     * @param  string  $type
     * @access private
     * @return int
     */
    private function getClassMethodCount(string $moduleName, string $type): int
    {
        if(file_exists($this->getClassFile($moduleName, $type)) === false) return 0;
        $classFileContents = file_get_contents($this->getClassFile($moduleName, $type));
        preg_match_all('/function\s+\w+\s*\(/', $classFileContents, $matches);
        $methodCount = count($matches[0]);

        return $methodCount;
    }

    /**
     * Get test method file.
     *
     * @param  string  $moduleName
     * @param  string  $type
     * @access private
     * @return string
     */
    private function getClassFile(string $moduleName = '', string $type = ''): string
    {
        if(!$type)       $type       = $this->testType;
        if(!$moduleName) $moduleName = $this->moduleName;
        return "{$this->zentaoRoot}/module/{$moduleName}/{$type}.php";
    }

    /**
     * Get test class Info.
     *
     * @param  string  $moduleName
     * @param  string  $type
     * @access private
     * @return object
     */
    private function getClassInfo(string $moduleName = '', string $type = ''): object
    {
        if(!$type)       $type       = $this->testType;
        if(!$moduleName) $moduleName = $this->moduleName;
        $class = new ReflectionClass($moduleName . ucfirst($type));
        if($type == 'control') $class = new ReflectionClass($moduleName);

        return $class;
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
            $fileName   = basename($filePath, '.php');
            $groupedTraces['module'] = $this->moduleName;
            $groupedTraces['type']   = $this->testType;
            $groupedTraces['method'] = $this->getMethodInfo()->getName();

            if($moduleName == $this->moduleName && $fileName == $this->testType) $groupedTraces['executeLines'] = $fileTrace;
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
    private function getModuleByFilePath(string $filePath): string
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
        <th>control 方法</th>
        <th>control 覆盖方法</th>
        <th>zen 方法</th>
        <th>zen 覆盖方法</th>
        <th>model 方法</th>
        <th>model 覆盖方法</th>
        <th>tao 方法</th>
        <th>tao 覆盖方法</th>
        <th>control 覆盖率</th>
        <th>zen 覆盖率</th>
        <th>model 覆盖率</th>
        <th>tao 覆盖率</th>
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
    private function genStatsTableByModule(string $module, array $moduleTraces)
    {
        $modelMethodesCount   = $this->getClassMethodCount($module, 'model');
        $controlMethodesCount = $this->getClassMethodCount($module, 'control');
        $zenMethodesCount     = $this->getClassMethodCount($module, 'zen');
        $taoMethodesCount     = $this->getClassMethodCount($module, 'tao');
        $controlCoverageCount = isset($moduleTraces['control']) ? count($moduleTraces['control']) : 0;
        $zenCoverageCount     = isset($moduleTraces['zen']) ? count($moduleTraces['zen']) : 0;
        $modelCoverageCount   = isset($moduleTraces['model']) ? count($moduleTraces['model']) : 0;
        $taoCoverageCount     = isset($moduleTraces['tao']) ? count($moduleTraces['tao']) : 0;

        $moduleCoverageList = array();

        foreach($moduleTraces as $type => $methods)
        {
            $moduleCoverageList[$type] = 0;
            foreach($methods as $methodCoverage) $moduleCoverageList[$type] += $methodCoverage['coverage'];
        }

        $summaryTable  = '<tr>' . PHP_EOL;
        $summaryTable .= "<th>$module</th>" . PHP_EOL;
        $summaryTable .= "<td>$controlMethodesCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$controlCoverageCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$zenMethodesCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$zenCoverageCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$modelMethodesCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$modelCoverageCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$taoMethodesCount</td>" . PHP_EOL;
        $summaryTable .= "<td>$taoCoverageCount</td>" . PHP_EOL;

        foreach($this->unfilteredTraces as $fileType)
        {
            $fileType = str_replace('.php', '', $fileType);
            $methodCount = $this->getClassMethodCount($module, $fileType);

            $summaryTable .= isset($moduleCoverageList[$fileType]) ? "<td><a href='?module=$module&file=$fileType'>" . round($moduleCoverageList[$fileType] / $methodCount, 2) * 100 . '%</a></td>' . PHP_EOL : '<td>0%</td>' . PHP_EOL;
        }
        $summaryTable .= '</tr>' . PHP_EOL;

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
    private function genCoverageTableByFile(string $module, string $file, array $fileTraces): string
    {
        $file = $this->getClassFile($module, $file);
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

        $coverageLineList = array();
        foreach($fileTraces as $method => $methodInfo)
        {
            foreach($methodInfo['executeLines'] as $executeLine => $count) $coverageLineList[$executeLine] = $count;
        }

        foreach($content as $line => $code)
        {
            /* The function file() give the line number start from 0, so we offset to end one more on index.  */
            $isCalled    = in_array($line + 1, array_keys($coverageLineList));
            $calledTimes = $isCalled ? '<span style="color: green;">' . $coverageLineList[$line + 1] . '</span>' : '<span style="color: red;">0</span>';

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
     * @return array|string|false
     *
     */
    public function loadTraceFromFiles(string $filePath, string $key = ''): array|string|false
    {
        $tracesFiles = glob("{$filePath}/*.json");
        if(!$tracesFiles) return false ;

        $tracesList  = array();
        foreach($tracesFiles as $file)
        {
            $tracesInfo = json_decode(file_get_contents($file), true);
            if(!isset($tracesInfo['traces'])) continue;

            $traces = $tracesInfo['traces'];
            $tracesList['traces'][$traces['module']][$traces['type']][$traces['method']]['executeLines'] = $traces['executeLines'];
            $tracesList['traces'][$traces['module']][$traces['type']][$traces['method']]['coverage']     = $traces['coverage'];

            $tracesList['time']    = $tracesInfo['time'];
            $tracesList['ztfPath'] = $tracesInfo['ztfPath'];
        }

        if($key == '') return $tracesList;
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
        $reportHtml = empty($file) ? '<style>td { border: 1px solid #ccc;  padding: 8px;  text-align: center;}</style>' . PHP_EOL : '<style>td { border: 1px solid #ccc;  padding: 8px;}</style>' . PHP_EOL;
        if(empty($file))
        {
            $reportHtml .= $this->genModuleStatsReport($traces);
        }
        else
        {
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
