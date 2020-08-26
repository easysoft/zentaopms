<?php
class GitRepo
{
    public $client;
    public $root;

    /**
     * Construct 
     * 
     * @param  string $client 
     * @param  string $root 
     * @param  string $username 
     * @param  string $password 
     * @param  string $encoding 
     * @access public
     * @return void
     */
    public function __construct($client, $root, $username, $password, $encoding = 'UTF-8')
    {
        putenv('LC_CTYPE=en_US.UTF-8');

        $this->client = $client;
        $this->root   = rtrim($root, DIRECTORY_SEPARATOR);
        $this->branch = isset($_COOKIE['repoBranch']) ? $_COOKIE['repoBranch'] : '';

        chdir($this->root);
        exec("{$this->client} config core.quotepath false");
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
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $sub  = ''; 
        chdir($this->root);
        if(!empty($path)) $sub = ":$path";
        if(!empty($this->branch))$revision = $this->branch;
        $cmd = escapeCmd("$this->client ls-tree -l $revision$sub");
        $list = execCmd($cmd . ' 2>&1', 'array', $result);
        if($result) return array();

        $infos   = array();
        foreach($list as $entry)
        {
            list($mod, $kind, $revision, $size, $name) = preg_split('/[\t ]+/', $entry);

            /* Get commit info. */
            $pathName = ltrim($path . DIRECTORY_SEPARATOR . $name, DIRECTORY_SEPARATOR);
            $cmd      = escapeCmd("$this->client log -1 $this->branch -- $pathName");
            $commit   = execCmd($cmd, 'array');
            $logs    = $this->parseLog($commit);

            if($size > 1024 * 1024)
            {
                $size = round($size / (1024 * 1024), 2) . 'MB';
            }
            else if($size > 1024)
            {
                $size = round($size / 1024, 2) . 'KB';
            }
            else
            {
                $size .= 'Bytes'; 
            }

            $info = new stdClass();
            $info->name     = $name;
            $info->kind     = $kind == 'tree' ? 'dir' : 'file';
            $info->revision = $logs ? $logs[0]->revision : $revision;
            $info->size     = $size;
            $info->account  = $logs ? $logs[0]->committer : '';
            $info->date     = $logs ? $logs[0]->time : '';
            $info->comment  = $logs ? $logs[0]->comment : '';
            $infos[] = $info;
            unset($info);
        }

        /* Sort by kind */
        foreach($infos as $key => $info) $kinds[$key] = $info->kind;
        if($infos) array_multisort($kinds, SORT_ASC, $infos);

        return $infos;
    }

    /**
     * Get tags 
     * 
     * @param  string $path 
     * @param  string $revision 
     * @access public
     * @return array
     */
    public function tags($path, $revision = 'HEAD')
    {
        chdir($this->root);
        $cmd  = escapeCmd("$this->client tag --sort=taggerdate");
        $list = execCmd($cmd . ' 2>&1', 'array', $result);
        if($result) return array();
        return $list;
    }

    /**
     * Get branch 
     * 
     * @access public
     * @return array
     */
    public function branch()
    {
        chdir($this->root);

        /* Get local branch. */
        $cmd  = escapeCmd("$this->client branch");
        $list = execCmd($cmd . ' 2>&1', 'array', $result);
        if($result) return array();

        $branches = array();
        foreach($list as $localBranch)
        {
            if($localBranch[0] == '*') $localBranch = substr($localBranch, 1);

            $localBranch = trim($localBranch);
            if(empty($localBranch))continue;
            $branches[$localBranch] = $localBranch;
        }
        asort($branches);
        return $branches;
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
        $path     = ltrim($path, DIRECTORY_SEPARATOR);
        $revision = $this->branch ? $this->branch : 'HEAD';

        chdir($this->root);
        $list = execCmd(escapeCmd("$this->client log -10 $revision -- $path"), 'array');
        $logs = $this->parseLog($list);

        return $logs;
    }

    /**
     * Get logs
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
        $path  = ltrim($path, DIRECTORY_SEPARATOR);
        $count = $count == 0 ? '' : "-n $count";
        /* compatible with svn. */
        if($fromRevision == 'HEAD' and $this->branch) $fromRevision = $this->branch;
        if($toRevision   == 'HEAD' and $this->branch) $toRevision   = $this->branch;
        if($fromRevision === $toRevision)
        {
            $logs = array();
            chdir($this->root);

            $list = execCmd(escapeCmd("$this->client log --stat=1024 --name-status --stat-name-width=1000 -1 $fromRevision -- $path"), 'array');
            $logs = $this->parseLog($list);
            return $logs;
        }

        if(!$fromRevision)
        {
            $revisions = " $toRevision";
        }
        else
        {
            $revisions = "$fromRevision..$toRevision"; 
        }
        chdir($this->root);
        $list = execCmd(escapeCmd("$this->client log  --stat=1024 --name-status --stat-name-width=1000 $count $revisions -- $path"), 'array');
        $logs = $this->parseLog($list);

        return $logs;
    }

    /**
     * Blame file
     * 
     * @param  string $path 
     * @param  string $revision 
     * @access public
     * @return array
     */
    public function blame($path, $revision)
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        chdir($this->root);
        $list = execCmd(escapeCmd("$this->client blame -l $revision -- $path"), 'array');

        $blames   = array();
        $revLine  = 0;
        $revision = '';
        foreach($list as $line)
        {
            if(empty($line)) continue;
            if($line[0] == '^') $line = substr($line, 1);
            preg_match('/^([0-9a-f]{39,40})\s.*\((\S+)\s+([\d-]+)\s(.*)\s(\d+)\)(.*)$/U', $line, $matches);

            if(isset($matches[1]) and $matches[1] != $revision)
            {
                $blame = array();
                $blame['revision']  = $matches[1];
                $blame['committer'] = $matches[2];
                $blame['time']      = $matches[3];
                $blame['line']      = $matches[5];
                $blame['lines']     = 1;
                $blame['content']   = strpos($matches[6], ' ') === false ? $matches[6] : substr($matches[6], 1);

                $revision         = $matches[1];
                $revLine          = $matches[5];
                $blames[$revLine] = $blame;
            }
            elseif(isset($matches[5]))
            {
                $blame            = array();
                $blame['line']    = $matches[5];
                $blame['content'] = strpos($matches[6], ' ') === false ? $matches[6] : substr($matches[6], 1);

                $blames[$matches[5]] = $blame;
                $blames[$revLine]['lines'] ++;
            }
        }
        return $blames;
    }

    /**
     * Diff file.
     * 
     * @param  string $path 
     * @param  string $fromRevision 
     * @param  string $toRevision 
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision)
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        chdir($this->root);
        if($toRevision == 'HEAD' and $this->branch) $toRevision = $this->branch;
        if($fromRevision == '^') $fromRevision = $toRevision . '^';
        if(strpos($fromRevision, '^') !== false)
        {
            $list = execCmd(escapeCmd("$this->client log -2 $toRevision --pretty=format:%H -- $path"), 'array');
            if(isset($list[1])) $fromRevision = $list[1];
        }
        $lines = execCmd(escapeCmd("$this->client diff $fromRevision $toRevision -- $path"), 'array');
        return $lines;
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
        chdir($this->root);
        if($revision == 'HEAD' and $this->branch) $revision = $this->branch;
        $cmd     = escapeCmd("$this->client show $revision:$entry");
        $content = execCmd($cmd);
        if(is_array($content)) $content = implode("\n", $content);
        return $content;
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
        chdir($this->root);
        if($revision == 'HEAD' and $this->branch) $revision = $this->branch;
        $path   = ltrim($entry, DIRECTORY_SEPARATOR);
        $cmd    = escapeCmd("$this->client ls-tree $revision -- $path");
        $result = execCmd($cmd);
        $kind   = '';
        if($result)
        {
            $results = explode("\n", trim($result));
            if(count($results) >= 2)
            {
                $kind = 'dir';
            }
            else
            {
                list($mode, $type) = explode(' ', $results[0]);
                $kind = $type == 'tree' ? 'dir' : 'file';
            }
        }

        $list = execCmd(escapeCmd("$this->client log -1 $revision --pretty=format:%H -- $path"), 'array');
        $revision = $list[0];
        $info     = new stdclass();
        $info->kind     = $kind; 
        $info->path     = $entry; 
        $info->revision = $revision; 
        $info->root     = $this->root; 
        return $info;
    }

    /**
     * Exec git cmd.
     * 
     * @param  string $cmd 
     * @access public
     * @return array
     */
    public function exec($cmd)
    {
        chdir($this->root);
        return execCmd(escapeCmd("$this->client $cmd"), 'array');
    }

    /**
     * Parse diff.
     * 
     * @param  array $lines 
     * @access public
     * @return array
     */
    public function parseDiff($lines)
    {
        if(empty($lines)) return array();
        $diffs   = array();
        $num     = count($lines);
        $endLine = end($lines);
        if(strpos($endLine, '\ No newline at end of file') === 0) $num -= 1;

        $newFile = false;
        for($i = 0; $i < $num; $i ++)
        {
            $diffFile = new stdclass();
            if(strpos($lines[$i], "diff --git ") === 0)
            {
                $fileInfo = explode(' ',$lines[$i]);
                $fileName = substr($fileInfo[2], strpos($fileInfo[2], '/') + 1);
                $diffFile->fileName = $fileName;
                for($i++; $i < $num; $i ++)
                {
                    $diff = new stdclass();
                    /* Fix bug #1757. */
                    if($lines[$i] == '+++ /dev/null') $newFile = true;
                    if(strpos($lines[$i], '+++', 0) !== false) continue;
                    if(strpos($lines[$i], '---', 0) !== false) continue;
                    if(strpos($lines[$i], '======', 0) !== false) continue;
                    if(preg_match('/^@@ -(\\d+)(,(\\d+))?\\s+\\+(\\d+)(,(\\d+))?\\s+@@/A', $lines[$i]))
                    {
                        $startLines = trim(str_replace(array('@', '+', '-'), '', $lines[$i]));
                        list($oldStartLine, $newStartLine) = explode(' ', $startLines);
                        list($diff->oldStartLine) = explode(',', $oldStartLine);
                        list($diff->newStartLine) = explode(',', $newStartLine);
                        $oldCurrentLine = $diff->oldStartLine;
                        $newCurrentLine = $diff->newStartLine;
                        if($newFile)
                        {
                            $oldCurrentLine = $diff->newStartLine;
                            $newCurrentLine = $diff->oldStartLine;
                        }
                        $newLines = array();
                        for($i++; $i < $num; $i ++)
                        {
                            if(preg_match('/^@@ -(\\d+)(,(\\d+))?\\s+\\+(\\d+)(,(\\d+))?\\s+@@/A', $lines[$i]))
                            {
                                $i --;
                                break;
                            }
                            if(strpos($lines[$i], "diff --git ") === 0) break;

                            $line = $lines[$i];
                            if(strpos($line, '\ No newline at end of file') === 0)continue;
                            $sign = empty($line) ? '' : $line[0];
                            if($sign == '-' and $newFile) $sign = '+';
                            $type = $sign != '-' ? $sign == '+' ? 'new' : 'all' : 'old';
                            if($sign == '-' || $sign == '+')
                            {
                                $line = substr_replace($line, ' ', 1, 0);
                                if($newFile) $line = preg_replace('/^\-/', '+', $line);
                            }

                            $newLine = new stdclass();
                            $newLine->type  = $type;
                            $newLine->oldlc = $type != 'new' ? $oldCurrentLine : '';
                            $newLine->newlc = $type != 'old' ? $newCurrentLine : '';
                            $newLine->line  = htmlspecialchars($line);

                            if($type != 'new') $oldCurrentLine++;
                            if($type != 'old') $newCurrentLine++;

                            $newLines[] = $newLine;
                        }

                        $diff->lines = $newLines;
                        $diffFile->contents[] = $diff;
                    }

                    if(isset($lines[$i]) and strpos($lines[$i], "diff --git ") === 0)
                    {
                        $i --;
                        $newFile = false;
                        break;
                    }
                }
                $diffs[] = $diffFile;
            }
        }
        return $diffs;
    }

    /**
     * Get commit count.
     * 
     * @param  int    $commits 
     * @param  string $lastVersion 
     * @access public
     * @return int
     */
    public function getCommitCount($commits = 0, $lastVersion = '')
    {
        chdir($this->root);
        $revision = $this->branch ? $this->branch : 'HEAD';
        return execCmd(escapeCmd("$this->client rev-list --count $revision -- ./"), 'string');
    }

    /**
     * Get first revision.
     * 
     * @access public
     * @return string
     */
    public function getFirstRevision()
    {
        chdir($this->root);
        $list = execCmd(escapeCmd("$this->client rev-list --reverse HEAD -- ./"), 'array');
        return $list[0];
    }

    /**
     * Get latest revision 
     * 
     * @access public
     * @return string
     */
    public function getLatestRevision()
    {
        chdir($this->root);
        $revision = $this->branch ? $this->branch : 'HEAD';
        $list     = execCmd(escapeCmd("$this->client rev-list -1 $revision -- ./"), 'array');
        return $list[0];
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
        if($version == 'HEAD' and $branch) $version = $branch;
        $revision = empty($version) ? $revision : $version;
        $revision = is_numeric($revision) ? "--skip=$revision $branch" : $revision;
        $count    = $count == 0 ? '' : "-n $count";

        chdir($this->root);
        $list    = execCmd(escapeCmd("$this->client log $count $revision -- ./"), 'array');
        $commits = $this->parseLog($list);

        $logs = array();
        foreach($commits as $commit)
        {
            $hash = $commit->revision;
            $log  = new stdClass();
            $log->committer = $commit->committer;
            $log->revision  = $commit->revision;
            $log->comment   = $commit->comment;
            $log->time      = $commit->time;
            $logs['commits'][$hash] = $log;
            $logs['files'][$hash]   = array();
        }
        if(empty($logs)) return $logs;

        $hash  = '';
        $files = execCmd(escapeCmd("$this->client whatchanged $count $revision --pretty=format:%an@_@%cd@_@%H@_@%s -- ./"), 'array');
        foreach($files as $commit)
        {
            $commit = trim($commit);
            if(empty($commit)) continue;
            $parsedCommit = explode('@_@', $commit);
            if(count($parsedCommit) == 4)
            {
                list($account, $date, $hash, $comment) = $parsedCommit;
            }
            else 
            {
                $file = explode(' ', $commit);
                $file = end($file);
                list($action, $path) = explode("\t", $file);
                $parsedFile = new stdclass();
                $parsedFile->revision = $hash;
                $parsedFile->path     = '/' . trim($path);
                $parsedFile->type     = 'file';
                $parsedFile->action   = $action;
                $logs['files'][$hash][]  = $parsedFile;
            }
        }
        return $logs;
    }

    /**
     * Parse log.
     * 
     * @param  array  $logs 
     * @access public
     * @return array
     */
    public function parseLog($logs)
    {
        $parsedLogs = array();
        $i          = 0;
        foreach($logs as $line)
        {
            if(strpos($line, 'commit ') === 0)
            {
                if(isset($log))
                {
                    $log->comment = trim($comment);
                    $log->change  = $changes;
                    $parsedLogs[$i] = $log;
                    $i++;
                }

                $log     = new stdclass();
                $comment = '';
                $changes = array();

                $log->revision = trim(preg_replace('/^commit/', '', $line));
            }
            elseif(strpos($line, 'Author:') === 0)
            {
                $account        = preg_replace('/^Author:/', '', $line);
                $log->committer = trim(preg_replace('/<[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+>/', '', $account));
            }
            elseif(strpos($line, 'Date:') === 0)
            {
                $date      = trim(preg_replace('/^Date:/', '', $line));
                $log->time = date('Y-m-d H:i:s', strtotime($date)); 
            }
            elseif(preg_match('/^\s{2,}/', $line))
            {
                $comment .= $line;
            }
            elseif(strpos($line, "\t") !== false)
            {
                list($action, $entry) = explode("\t", $line);
                $entry = '/' . trim($entry);
                $pathInfo = array();
                $pathInfo['action'] = $action;
                $pathInfo['kind']   = 'file';
                $changes[$entry]    = $pathInfo;
            }
        }

        if(isset($log))
        {
            $log->comment   = trim($comment);
            $log->change    = $changes;
            $parsedLogs[$i] = $log;
        }

        return $parsedLogs;
    }
}
