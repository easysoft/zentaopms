<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class installZenTest extends baseTest
{
    protected $moduleName = 'install';
    protected $className  = 'zen';

    /**
     * 构造函数，设置安装状态，避免加载 install 模块时中断。
     * Constructor, set the installing flag to avoid ending the request.
     *
     * @param  string $moduleName
     * @param  string $className
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $className = '')
    {
        global $app;
        $app->installing = true;

        parent::__construct($moduleName, $className);
    }

    /**
     * 设置会话中的数据库配置，保证 setDBParam 正常执行。
     * Set the database config in session for setDBParam.
     *
     * @access protected
     * @return void
     */
    protected function prepareSessionConfig(): void
    {
        global $config, $tester;

        $tester->session->myConfig = array(
            'dbDriver'   => 'mysql',
            'dbHost'     => $config->db->host,
            'dbUser'     => $config->db->user,
            'dbPassword' => isset($config->db->password) ? $config->db->password : '',
            'dbPort'     => $config->db->port,
            'dbName'     => $config->db->name,
            'dbPrefix'   => $config->db->prefix,
            'dbEncoding' => 'utf8',
            'clearDB'    => 0
        );
    }

    /**
     * 测试获取安装计划。
     * Test getInstallPlan method.
     *
     * @param  string $file
     * @param  string $edition
     * @access public
     * @return array
     */
    public function getInstallPlanTest(string $file = '', string $edition = ''): array
    {
        global $config;

        $oldEdition = $config->edition;
        if($edition) $config->edition = $edition;

        $this->prepareSessionConfig();
        $plan = $this->invokeArgs('getInstallPlan', array($file));

        $config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();

        return is_array($plan) ? $plan : array();
    }

    /**
     * 测试安装计划中 SQL 语句的数量。
     * Test the SQL count of the install plan.
     *
     * @param  string $file
     * @param  string $edition
     * @access public
     * @return int
     */
    public function getInstallPlanSQLCountTest(string $file = '', string $edition = ''): int
    {
        $plan = $this->getInstallPlanTest($file, $edition);
        return isset($plan['sqls']) ? count($plan['sqls']) : 0;
    }

    /**
     * 测试安装计划中变更内容的数量。
     * Test the changes count of the install plan.
     *
     * @param  string $file
     * @param  string $edition
     * @access public
     * @return int
     */
    public function getInstallPlanChangesCountTest(string $file = '', string $edition = ''): int
    {
        $plan = $this->getInstallPlanTest($file, $edition);
        return isset($plan['changes']) ? count($plan['changes']) : 0;
    }

    /**
     * 测试安装计划中是否包含指定内容的 SQL 语句。
     * Test whether the install plan contains the given SQL content.
     *
     * @param  string $needle
     * @param  string $file
     * @param  string $edition
     * @access public
     * @return int
     */
    public function getInstallPlanContainsTest(string $needle, string $file = '', string $edition = ''): int
    {
        $plan = $this->getInstallPlanTest($file, $edition);
        if(empty($plan['sqls'])) return 0;

        foreach($plan['sqls'] as $sql)
        {
            if(strpos($sql, $needle) !== false) return 1;
        }
        return 0;
    }
}
