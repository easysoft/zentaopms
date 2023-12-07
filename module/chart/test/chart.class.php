<?php
class chartTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('chart');
    }

    /**
     * 测试处理图表数据。
     * Test process the data of the chart.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function processChartTest(string $testType): object
    {
        $chart = $this->objectModel->getByID(1018);
        $chart->langs    = '';
        $chart->filters  = '';
        $chart->settings = '';
        $chart->fields   = '{"name":{"name":"\u9879\u76ee\u540d\u79f0","object":"project","field":"name","type":"string"},"closedDate":{"name":"\u5173\u95ed\u65e5\u671f","object":"project","field":"closedDate","type":"date"},"daterate":{"name":"daterate","object":"project","field":"daterate","type":"number"}}';

        if($testType == 'sqlNull')        $chart->sql = null;
        if($testType == 'trimSQL')        $chart->sql = "SELECT id FROM zt_project WHERE type='program' AND parent=0 AND deleted='0';";
        if($testType == 'decodeLangs')    $chart->langs = '{"name":{"zh-cn":"\u9879\u76ee\u540d\u79f0","zh-tw":"","en":"","de":"","fr":""}}';
        if($testType == 'decodeFilters')  $chart->filters = '[{"field":"closedDate","type":"date","name":"\u5173\u95ed\u65e5\u671f","default":{"begin":"","end":""}}]';
        if($testType == 'decodeSettings') $chart->settings = '[{"type":"cluBarY","xaxis":[{"field":"name","name":"\u9879\u76ee\u540d\u79f0","group":""}],"yaxis":[{"field":"daterate","name":"daterate","valOrAgg":"sum"}]}]';

        return $this->objectModel->processChart($chart);
    }

    /**
     * 测试处理数据库查询结果。
     * Test process rows.
     *
     * @param  string $date
     * @access public
     * @return array
     */
    public function processRowsTest(string $date, string $group, string $metric): array
    {
        $defaultSql = 'select * from zt_story';
        $rows = $this->objectModel->getRows($defaultSql, array(), $date, $group, $metric, 'count');
        return $this->objectModel->processRows($rows, $date, $group, $metric);
    }

    /**
     * 测试按钮是否可点击。
     * Test isClickable.
     *
     * @param  int $chartID
     * @access public
     * @return string
     */
    public function isClickableTest(int $chartID, string $action): string
    {
        $chart = $this->objectModel->getByID($chartID);
        return $this->objectModel->isClickable($chart, $action) ? 'true' : 'false';
    }
}
