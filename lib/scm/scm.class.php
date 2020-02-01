<?php
class scm
{
    public $engine;

    public function setEngine($repo)
    {
        $className     = $repo->SCM;
        if(!class_exists($className)) require(strtolower($className) . '.class.php');
        $this->engine  = new $className($repo->client, $repo->path, $repo->account, $repo->password, $repo->encoding);
    }

    public function ls($path, $revision = 'HEAD')
    {
        return $this->engine->ls($path, $revision);
    }

    public function branch()
    {
        return $this->engine->branch();
    }
    
    public function log($path, $fromRevision = 0, $toRevision = 'HEAD', $count = 0)
    {
        return $this->engine->log($path, $fromRevision, $toRevision);
    }

    public function blame($path, $revision)
    {
        return $this->engine->blame($path, $revision);
    }

    public function getLastLog($path, $count = 10)
    {
        return $this->engine->getLastLog($path, $count);
    }

    public function diff($path, $fromRevision = 0, $toRevision = 'HEAD', $parse = 'yes')
    {
        $diffs = $this->engine->diff($path, $fromRevision, $toRevision);

        if($parse  != 'yes') return implode("\n", $diffs);
        return $this->engine->parseDiff($diffs);
    }

    public function cat($entry, $revision = 'HEAD')
    {
        return $this->engine->cat($entry, $revision); 
    }

    public function info($entry, $revision = 'HEAD')
    {
        return $this->engine->info($entry, $revision);
    }

    public function getCommitCount($commits = 0, $lastVersion = 0)
    {
        return $this->engine->getCommitCount($commits, $lastVersion); 
    }

    public function getLatestRevision()
    {
        return $this->engine->getLatestRevision(); 
    }

    public function getFirstRevision()
    {
        return $this->engine->getFirstRevision(); 
    }

    public function getCommits($version = '', $count = 0, $branch = '')
    {
        return $this->engine->getCommits($version, $count, $branch);
    }

    public $commitCommandRegx = '/\s*([a-z]+)\s+((?:build)|(?:story)|(?:task)|(?:bug))\s+#((?:\d|,)+)\s*/i';
    public $tagCommandRegx = '/build[\-_]#((?:\d|,)+)/i';

    /**
     * Parse the comment of git, extract object id list from it.
     *
     * @param  string    $comment
     * @param  array     $allCommands
     * @access public
     * @return array
     */
    public function parseComment($comment, &$allCommands)
    {
        $pattern = $this->commitCommandRegx;
        $matches = array();
        preg_match_all($pattern, $comment,$matches);

        $stories = array();
        $tasks   = array();
        $bugs    = array();

        if(count($matches) > 1 && count($matches[1]) > 0)
        {
            $i = 0;
            foreach($matches[1] as $action)
            {
                $action = $matches[1][$i];
                $entityType = $matches[2][$i];
                $entityIds = $matches[3][$i];

                $currArr = $allCommands[$entityType][$action];
                if (empty($currArr)) {
                    $currArr = [];
                }
                $newArr = explode(",", $entityIds);
                $allCommands[$entityType][$action] = array_keys(array_flip($currArr) + array_flip($newArr));

                if ($entityType === 'story') {
                    $stories = array_merge($stories, $newArr);
                }
                if ($entityType === 'task') {
                    $tasks = array_merge($tasks, $newArr);
                }
                if ($entityType === 'bug') {
                    $bugs = array_merge($bugs, $newArr);
                }

                $i++;
            }
        }

        return array('stories' => $stories, 'tasks' => $tasks, 'bugs' => $bugs);
    }

    /**
     * Parse the tag, extract task list from it.
     *
     * @param  string    $comment
     * @param  array     $jobToBuild
     * @access public
     */
    public function parseTag($comment, &$jobToBuild)
    {
        $pattern = $this->tagCommandRegx;
        $matches = array();
        preg_match($pattern, $comment,$matches);

        if(count($matches) > 0)
        {
            $entityIds = $matches[1];

            if (empty($taskToBuild)) {
                $taskToBuild = [];
            }
            $newArr = explode(",", $entityIds);
            $jobToBuild = array_keys(array_flip($taskToBuild) + array_flip($newArr));
        }
    }
}

function escapeCmd($cmd)
{
   $codes = array('#', '&', ';', '`', '|', '*', '?', '~', '<', '>', '^', '[', ']', '{', '}', '$', ',', '\x0A', '\xFF');
   if(DIRECTORY_SEPARATOR == '/') $codes[] = '\\';
   foreach($codes as $code) $cmd = str_replace($code, '\\' . $code, $cmd);
   return $cmd;
}

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
