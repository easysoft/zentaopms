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
        $scm = strtolower($repo->SCM);
        $className = $scm . 'Repo';
        if($scm == 'git') $scm = 'gitrepo';
        if(!class_exists($className)) require($scm . '.class.php');
        $this->engine = new $className($repo->client, in_array($scm, array('gitlab', 'gitfox')) ? $repo->apiPath : $repo->path, $repo->account, $repo->password, $repo->encoding, $repo);
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
        if(!scm::checkRevision($revision)) return array();
        return $this->engine->ls($path, $revision);
    }

    /**
     * Get tags.
     *
     * @param  string $path     svn use path, git service filter return data.
     * @param  string $revision
     * @param  bool   $onlyDir
     * @param  int    $limit
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function tags($path = '', $revision = 'HEAD', $onlyDir = true, int $limit = 0, int $pageID = 1)
    {
        if(!scm::checkRevision($revision)) return array();
        return $this->engine->tags($path, $revision, $onlyDir, $limit, $pageID);
    }

    /**
     * Get branch.
     *
     * @access public
     * @param  string $showDetail
     * @param  int    $limit
     * @param  int    $pageID
     * @return array
     */
    public function branch(string $showDetail = '', int $limit = 0, int $pageID = 1)
    {
        return $this->engine->branch($showDetail, $limit, $pageID);
    }

    /**
     * Create a branch.
     *
     * @param  string $branchName
     * @param  string $ref
     * @access public
     * @return bool
     */
    public function createBranch($branchName = '', $ref = 'master')
    {
        if(get_class($this->engine) == 'subversion') return false;

        return $this->engine->createBranch($branchName, $ref);
    }

    /**
     * 创建标签。
     * Creates a new tag with the given name and optional comment.
     *
     * @param  string $tagName The name of the tag to be created.
     * @param  string $ref     The revision from which the tag is created, it can be a commit SHA, another tag name, or branch name.
     * @param  string $comment An optional comment for the tag.
     * @return array|false  Returns false if the engine is Subversion, otherwise returns the result of the createTag method of the engine object.
     */
    public function createTag($tagName, $ref, $comment = '')
    {
        if(!in_array(get_class($this->engine), array('gitlabRepo', 'gitfoxRepo'))) return false;

        return $this->engine->createTag($tagName, $ref, $comment);
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
        if(!scm::checkRevision($fromRevision)) return array();
        if(!scm::checkRevision($toRevision))   return array();

        return $this->engine->log($path, $fromRevision, $toRevision);
    }

    /**
     * Blame file.
     *
     * @param  string $path
     * @param  string $revision
     * @param  bool   $showComment
     * @access public
     * @return array
     */
    public function blame($path, $revision, $showComment = true)
    {
        if(!scm::checkRevision($revision)) return array();
        return $this->engine->blame($path, $revision, $showComment);
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
    public function diff($path, $fromRevision = 0, $toRevision = 'HEAD', $parse = 'yes', $extra = '')
    {
        if(!scm::checkRevision($fromRevision) and $extra != 'isBranchOrTag') return array();
        if(!scm::checkRevision($toRevision) and $extra != 'isBranchOrTag')   return array();

        if(!$extra) $diffs = $this->engine->diff($path, $fromRevision, $toRevision);
        if($extra)
        {
            if(get_class($this->engine) == 'gitlab') $diffs = $this->engine->diff($path, $fromRevision, $toRevision, '', $extra);
            if(get_class($this->engine) != 'gitlab') $diffs = $this->engine->diff($path, $fromRevision, $toRevision, $extra);
        }

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
        if(!scm::checkRevision($revision)) return false;
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
        if(!scm::checkRevision($revision)) return false;
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
        if(!scm::checkRevision($lastVersion)) return false;
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
        if(!scm::checkRevision($version)) return array();
        return $this->engine->getCommits($version, $count, $branch);
    }

    /**
     * Get commits by MR branches.
     *
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function getMRCommits($sourceBranch, $targetBranch)
    {
        return $this->engine->getMRCommits($sourceBranch, $targetBranch);
    }

    /**
     * Get clone url.
     *
     * @access public
     * @return void
     */
    public function getCloneUrl()
    {
        return $this->engine->getCloneUrl();
    }

    /**
     * Check revision
     *
     * @param  int|string $revision
     * @static
     * @access public
     * @return bool
     */
    public static function checkRevision($revision)
    {
        if(preg_match('/[^a-z0-9\-_\.\^\w][\x{4e00}-\x{9fa5}]/ui', $revision)) return false;
        return true;
    }

    /**
     * Get download url.
     *
     * @param  string $branch
     * @param  string $savePath
     * @param  string $ext
     * @access public
     * @return string
     */
    public function getDownloadUrl($branch = '', $savePath = '', $ext = 'zip')
    {
        return $this->engine->getDownloadUrl($branch, $savePath, $ext);
    }

    /**
     * Get all files.
     *
     * @param  string $path
     * @param  string $revision
     * @access public
     * @return string
     */
    public function getAllFiles($path = '', $revision = 'HEAD')
    {
        return $this->engine->getAllFiles($path, $revision);
    }

    /**
     * Get files by commit.
     *
     * @param  string  $commit
     * @access public
     * @return array
     */
    public function getFilesByCommit($revision)
    {
        return $this->engine->getFilesByCommit($revision);
    }

    /**
     * Create mr by api.
     *
     * @param  object $MR
     * @param  string $openID
     * @param  string $assignee
     * @access public
     * @return null|object
     */
    public function createMR($MR, $openID = '', $assignee = '')
    {
        return $this->engine->createMR($MR, $openID, $assignee);
    }

    /**
     * Get a mr by api.
     *
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function getSingleMR($MRID)
    {
        return $this->engine->getSingleMR($MRID);
    }

    /**
     * Get pipeline list by api.
     *
     * @access public
     * @return array
     */
    public function pipelines()
    {
        return $this->engine->pipelines();
    }

    /**
     * 格式化时间。
     * Format date.
     *
     * @param  int|string $date
     * @param  bool       $hasTime
     * @param  bool       $isEnd
     * @access private
     * @return string
     */
    private function formatDate($date, $hasTime = false, $isEnd = false)
    {
        $format = $hasTime ? 'Y-m-d H:i:s' : 'Y-m-d';
        if(is_numeric($date))
        {
            if($date > 100000000) return date($format, $date);

            $date = $isEnd ? "{$date}-12-31" : "{$date}-01-01";
            if($isEnd) $date = date('Y-m-d', strtotime($date) + 86400);
        }
        elseif($isEnd)
        {
            $date = date('Y-m-d', strtotime($date) + 86400);
        }

        return date($format, strtotime($date));
    }

    /**
     * 根据开始时间和结束时间获取提交时间和提交人。
     * Get commit count by date.
     *
     * @param  int    $startDate
     * @param  int    $endDate
     * @access public
     * @return array
     */
    public function getCommitByDate($startDate, $endDate)
    {
        $startDate = trim($startDate, '-');
        $endDate   = trim($endDate, '-');
        return $this->engine->getCommitByDate($this->formatDate($startDate), $this->formatDate($endDate, false, true));
    }

    public function __call($funcName, $arguments)
    {
        if(method_exists($this->engine, $funcName)) return call_user_func_array(array($this->engine, $funcName), $arguments);
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
    if(DIRECTORY_SEPARATOR == '/') $cmd = str_replace('\\', '\\\\', $cmd);
    foreach($codes as $code) $cmd = str_replace($code, "\\{$code}", $cmd);
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
