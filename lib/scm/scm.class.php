<?php
class scm
{
    public $engine;

    /**
     * Set engine.
     * 
     * @param  object $repo 
     * @access public
     * @return void
     */
    public function setEngine($repo)
    {
        $className     = $repo->SCM;
        if($className == 'Git') $className = 'GitRepo';
        if(!class_exists($className)) require(strtolower($className) . '.class.php');
        $this->engine  = new $className($repo->client, $repo->path, $repo->account, $repo->password, $repo->encoding);
    }

    /**
     * List files. 
     * 
     * @param  string $path 
     * @param  string $revision 
     * @access public
     * @return array
     */
    public function ls($path, $revision = 'HEAD')
    {
        return $this->engine->ls($path, $revision);
    }

    /**
     * Get tags.
     * 
     * @param  string $path 
     * @param  string $revision 
     * @param  bool   $onlyDir 
     * @access public
     * @return array
     */
    public function tags($path, $revision = 'HEAD', $onlyDir = true)
    {
        return $this->engine->tags($path, $revision, $onlyDir);
    }

    /**
     * Get branch.
     * 
     * @access public
     * @return array
     */
    public function branch()
    {
        return $this->engine->branch();
    }

    /**
     * Get log.
     * 
     * @param  string $path 
     * @param  string $fromRevision 
     * @param  string $toRevision 
     * @param  int    $count 
     * @access public
     * @return array
     */
    public function log($path, $fromRevision = 0, $toRevision = 'HEAD', $count = 0)
    {
        return $this->engine->log($path, $fromRevision, $toRevision);
    }

    /**
     * Blame file.
     * 
     * @param  string $path 
     * @param  string $revision 
     * @access public
     * @return array
     */
    public function blame($path, $revision)
    {
        return $this->engine->blame($path, $revision);
    }

    /**
     * Get last log.
     * 
     * @param  string $path 
     * @param  int    $count 
     * @access public
     * @return array
     */
    public function getLastLog($path, $count = 10)
    {
        return $this->engine->getLastLog($path, $count);
    }

    /**
     * Diff file.
     * 
     * @param  string $path 
     * @param  string $fromRevision 
     * @param  string $toRevision 
     * @param  string $parse 
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision = 0, $toRevision = 'HEAD', $parse = 'yes')
    {
        $diffs = $this->engine->diff($path, $fromRevision, $toRevision);

        if($parse  != 'yes') return implode("\n", $diffs);
        return $this->engine->parseDiff($diffs);
    }

    /**
     * Cat file.
     * 
     * @param  string $entry 
     * @param  string $revision 
     * @access public
     * @return string
     */
    public function cat($entry, $revision = 'HEAD')
    {
        return $this->engine->cat($entry, $revision); 
    }

    /**
     * Get info.
     * 
     * @param  string $entry 
     * @param  string $revision 
     * @access public
     * @return object
     */
    public function info($entry, $revision = 'HEAD')
    {
        return $this->engine->info($entry, $revision);
    }

    /**
     * Exec scm cmd.
     * 
     * @param  string $cmd 
     * @access public
     * @return array
     */
    public function exec($cmd)
    {
        return $this->engine->exec($cmd);
    }

    /**
     * Get commit count 
     * 
     * @param  int    $commits 
     * @param  string $lastVersion 
     * @access public
     * @return int
     */
    public function getCommitCount($commits = 0, $lastVersion = 0)
    {
        return $this->engine->getCommitCount($commits, $lastVersion); 
    }

    /**
     * Get latest revision.
     * 
     * @access public
     * @return string
     */
    public function getLatestRevision()
    {
        return $this->engine->getLatestRevision(); 
    }

    /**
     * Get first revision.
     * 
     * @access public
     * @return string
     */
    public function getFirstRevision()
    {
        return $this->engine->getFirstRevision(); 
    }

    /**
     * Get commits.
     * 
     * @param  string $version 
     * @param  int    $count 
     * @param  string $branch 
     * @access public
     * @return array
     */
    public function getCommits($version = '', $count = 0, $branch = '')
    {
        return $this->engine->getCommits($version, $count, $branch);
    }
}

/**
 * Escape command. 
 * 
 * @param  string $cmd 
 * @access public
 * @return string
 */
function escapeCmd($cmd)
{
   $codes = array('#', '&', ';', '`', '|', '*', '?', '~', '<', '>', '^', '[', ']', '{', '}', '$', ',', '\x0A', '\xFF');
   if(DIRECTORY_SEPARATOR == '/') $codes[] = '\\';
   foreach($codes as $code) $cmd = str_replace($code, '\\' . $code, $cmd);
   return $cmd;
}

/**
 * Execute command. 
 * 
 * @param  string $cmd 
 * @param  string $return 
 * @param  int    $result 
 * @param  string $type 
 * @access public
 * @return array|string
 */
function execCmd($cmd, $return = 'string', &$result = 0, $type = 'utf-8')
{
    if(file_exists(dirname(__FILE__) . '/config.php')) include dirname(__FILE__) . '/config.php';
    if($type != 'utf-8') $cmd = iconv('utf-8', $type . '//TRANSLIT', $cmd);

    $debug = (isset($config->debug) and $config->debug);
    if($debug and strpos($cmd, '2>&1') === false) $cmd = $cmd . ' 2>&1';

    ob_start();
    passthru($cmd, $result);
    $output = ob_get_clean();
    if($debug and $result)
    {
        a('The command is ' . $cmd);
        a('The result is ' . $result);
        a($output);
    }

    /* When output is empty and with chinese then try execute again in windows. */
	if(strtolower(substr(PHP_OS, 0, 3)) == 'win' and empty($output) and $type == 'utf-8' and preg_match("/[\x7f-\xff]/", $cmd)) $output = execCmd($cmd, 'string', $result, 'gbk');
	if($return == 'array') return explode("\n", trim($output));
    return $output;
}
