<?php
class result
{
    public $result;

    public $caseTitle;
    public $caseCode;
    public $reportFile;
    public $reportURL;
    public $pageObject;
    public $errors = array();


    public function create($caseTitle, $caseCode)
    {
        $this->caseTitle = $caseTitle;
        $this->caseCode  = $caseCode;
    }

    /**
     * Create and init report.
     *
     * @access public
     * @return void
     */
    public function initReport($caseTitle, $caseCode)
    {
        global $config;
        $reportType     = $config->reportType;
        $reportTemplate = $config->reportTemplate[$reportType];

        $reportPath = $config->reportRoot . DS . $caseCode . DS;
        $suffix     = ($reportType == 'markdown') ? 'md' : $reportType;
        $this->reportFile = $reportPath . date('ymdhis') . '.' . $suffix;
        $this->reportURL  = str_replace($config->reportRoot, $config->reportWebRoot, $this->reportFile);

        if(!is_dir($reportPath)) mkdir($reportPath, 0777, true);

        $reportContent = str_replace(array('{TITLE}', '{CODE}'), array($caseTitle, $caseCode), $reportTemplate);

        file_put_contents($this->reportFile, $reportContent);
    }

    /**
     * Export test report of a page.
     *
     * @param  array  $alertsMessage
     * @access public
     * @return void
     */
    public function saveReport($message)
    {
        $message .= "\n";
        file_put_contents($this->reportFile, $message, FILE_APPEND);
    }

    /**
     * Print close tag.
     *
     * @access public
     * @return void
     */
    public function endReport()
    {
        $message = '</div></body></html>';
        $this->saveReport($message);
    }

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
        $result['caseTitle']     = $this->caseTitle;
        $result['caseCode']      = $this->caseCode;
        $result['reportFile']    = $this->reportFile;
        $result['reportWebRoot'] = $this->reportURL;
        $result['errors']        = $this->errors;
        $result['page']          = $this->pageObject;

        return $param ? zget($result, $param, '') : $result;
    }
}

$result = new result();
