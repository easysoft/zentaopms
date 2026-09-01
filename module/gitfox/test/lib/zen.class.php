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
     * @access public
     * @return string
     */
    public function buildGitFoxScriptTest(string $action): string
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action]);
        return is_string($script) ? $script : '';
    }

    /**
     * Test buildGitFoxScript creates a script file.
     *
     * @param  string $action
     * @access public
     * @return int
     */
    public function buildGitFoxScriptExistsTest(string $action): int
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action]);
        return is_string($script) && file_exists($script) ? 1 : 0;
    }

    /**
     * Test buildGitFoxScript writes the expected command.
     *
     * @param  string $action
     * @param  string $keyword
     * @access public
     * @return int
     */
    public function buildGitFoxScriptContentTest(string $action, string $keyword): int
    {
        $script = $this->invokeArgs('buildGitFoxScript', [$action]);
        if(!is_string($script) || !file_exists($script)) return 0;

        $content = (string)file_get_contents($script);
        return strpos($content, $keyword) !== false ? 1 : 0;
    }
}
