<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gitfoxZenTest extends baseTest
{
    protected $moduleName = 'gitfox';
    protected $className  = 'zen';

    /**
     * Test buildGitFoxScript returns a script path.
     *
     * @param  string $action
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return string
     */
    public function buildGitFoxScriptTest(string $action, string $os = PHP_OS, string $machine = ''): string
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action, $os, $machine]);
        return is_string($script) ? $script : '';
    }

    /**
     * Test buildGitFoxScript creates a script file.
     *
     * @param  string $action
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return int
     */
    public function buildGitFoxScriptExistsTest(string $action, string $os = PHP_OS, string $machine = ''): int
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action, $os, $machine]);
        return is_string($script) && file_exists($script) ? 1 : 0;
    }

    /**
     * Test buildGitFoxScript writes the expected command.
     *
     * @param  string $action
     * @param  string $keyword
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return int
     */
    public function buildGitFoxScriptContentTest(string $action, string $keyword, string $os = PHP_OS, string $machine = ''): int
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action, $os, $machine]);
        if(!is_string($script) || !file_exists($script)) return 0;

        $content = (string)file_get_contents($script);
        return strpos($content, $keyword) !== false ? 1 : 0;
    }

    /**
     * Test buildGitFoxScript returns the script extension of the given system.
     *
     * @param  string $action
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return string
     */
    public function buildGitFoxScriptExtensionTest(string $action, string $os = PHP_OS, string $machine = ''): string
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action, $os, $machine]);
        return is_string($script) ? pathinfo($script, PATHINFO_EXTENSION) : '';
    }

    /**
     * Test buildGitFoxScript replaces the install dir placeholder with the app gitfox dir.
     *
     * @param  string $action
     * @param  string $os
     * @param  string $machine
     * @access public
     * @return int
     */
    public function buildGitFoxScriptInstallDirTest(string $action, string $os = PHP_OS, string $machine = ''): int
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action, $os, $machine]);
        if(!is_string($script) || !file_exists($script)) return 0;

        $gitfoxDir = preg_quote($this->instance->app->getAppRoot() . 'gitfox', '/');
        $content   = (string)file_get_contents($script);
        return preg_match('/INSTALL_DIR="?' . $gitfoxDir . '"?/', $content) ? 1 : 0;
    }

}
