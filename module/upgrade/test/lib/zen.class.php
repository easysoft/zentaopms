<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class upgradeZenTest extends baseTest
{
    protected $moduleName = 'upgrade';
    protected $className  = 'zen';

    /**
     * 构造函数，加载 upgrade 模型供 zen 层方法调用。
     * Constructor, load upgrade model for the zen methods.
     *
     * @param  string $moduleName
     * @param  string $className
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $className = '')
    {
        parent::__construct($moduleName, $className);

        $this->instance->loadModel('upgrade');
    }

    /**
     * 设置版本信息并返回旧版本信息。
     * Set the version information and return the old version information.
     *
     * @param  string $installedVersion
     * @param  string $version
     * @access protected
     * @return array
     */
    protected function setVersions(string $installedVersion, string $version): array
    {
        global $config;

        $oldVersions = array(
            'installedVersion' => isset($config->installedVersion) ? $config->installedVersion : '',
            'version'          => $config->version,
            'errors'           => upgradeModel::$errors
        );

        if($installedVersion) $config->installedVersion = $installedVersion;
        if($version)          $config->version          = $version;
        upgradeModel::$errors = array();

        return $oldVersions;
    }

    /**
     * 恢复版本信息。
     * Restore the version information.
     *
     * @param  array  $oldVersions
     * @access protected
     * @return void
     */
    protected function restoreVersions(array $oldVersions): void
    {
        global $config;

        $config->installedVersion = $oldVersions['installedVersion'];
        $config->version          = $oldVersions['version'];
        upgradeModel::$errors     = $oldVersions['errors'];
    }

    /**
     * 测试获取要升级到的版本列表。
     * Test get upgrade versions.
     *
     * @param  string $fromVersion
     * @param  string $installedVersion
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeVersionsTest(string $fromVersion, string $installedVersion = '22_7', string $version = '22_7'): string
    {
        $oldVersions = $this->setVersions($installedVersion, $version);

        $versions = $this->invokeArgs('getUpgradeVersions', array($fromVersion));

        $this->restoreVersions($oldVersions);
        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($versions));
    }

    /**
     * 测试获取要升级到的版本列表时记录的错误信息。
     * Test the errors of getUpgradeVersions.
     *
     * @param  string $fromVersion
     * @param  string $installedVersion
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeVersionsErrorTest(string $fromVersion, string $installedVersion = '22_7', string $version = '22_7'): string
    {
        $oldVersions = $this->setVersions($installedVersion, $version);

        $this->invokeArgs('getUpgradeVersions', array($fromVersion));
        $errors = upgradeModel::$errors;

        $this->restoreVersions($oldVersions);
        return implode(',', $errors);
    }

    /**
     * 测试获取升级变更内容的条数。
     * Test the count of the upgrade changes.
     *
     * @param  string $fromVersion
     * @param  string $toVersion
     * @param  string $installedVersion
     * @param  string $version
     * @access public
     * @return int
     */
    public function getUpgradeChangesCountTest(string $fromVersion, string $toVersion, string $installedVersion = '22_7', string $version = '22_7'): int
    {
        $oldVersions = $this->setVersions($installedVersion, $version);

        $changes = $this->invokeArgs('getUpgradeChanges', array($fromVersion, $toVersion));

        $this->restoreVersions($oldVersions);
        return count($changes);
    }

    /**
     * 测试获取升级变更内容中第一条变更的字段值。
     * Test the field value of the first upgrade change.
     *
     * @param  string $fromVersion
     * @param  string $toVersion
     * @param  string $field
     * @param  string $installedVersion
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeChangesFieldTest(string $fromVersion, string $toVersion, string $field, string $installedVersion = '22_7', string $version = '22_7'): string
    {
        $oldVersions = $this->setVersions($installedVersion, $version);

        $changes = $this->invokeArgs('getUpgradeChanges', array($fromVersion, $toVersion));

        $this->restoreVersions($oldVersions);

        $change = reset($changes);
        if(empty($change) || !isset($change[$field])) return 'none';

        return (string)$change[$field];
    }

    /**
     * 测试升级变更内容中是否包含指定的升级方法。
     * Test whether the upgrade changes contain the given method.
     *
     * @param  string $fromVersion
     * @param  string $toVersion
     * @param  string $method
     * @access public
     * @return int
     */
    public function getUpgradeChangesHasMethodTest(string $fromVersion, string $toVersion, string $method): int
    {
        $oldVersions = $this->setVersions('22_7', '22_7');

        $changes = $this->invokeArgs('getUpgradeChanges', array($fromVersion, $toVersion));

        $this->restoreVersions($oldVersions);

        foreach($changes as $change)
        {
            if(isset($change['method']) && $change['method'] === $method) return 1;
        }
        return 0;
    }
}
