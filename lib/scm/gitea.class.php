<?php
class giteaRepo
{
    public $client;
    public $root;
    public $repo;

    /**
     * Construct
     *
     * @param  string $client
     * @param  string $root
     * @param  string $username
     * @param  string $password
     * @param  string $encoding
     * @param  object $repo
     * @access public
     * @return void
     */
    public function __construct($client, $root, $username, $password, $encoding = 'UTF-8', $repo = null)
    {
        putenv('LC_CTYPE=en_US.UTF-8');

        $this->repo   = $repo;
        $this->client = $client;
        $this->root   = rtrim($root, DIRECTORY_SEPARATOR);
        if(!realpath($this->root) and !empty($repo))
        {
            global $app;
            $project = $app->control->loadModel('gitea')->apiGetSingleProject($repo->serviceHost, $repo->serviceProject);
            if(isset($project->tokenCloneUrl))
            {
                $cmd = 'git clone --progress -v "' . $project->tokenCloneUrl . '" "' . $this->root . '"  > "' . $app->getTmpRoot() . "log/clone.progress." . strtolower($repo->SCM) . ".{$repo->name}.log\" 2>&1 &";
                if(PHP_OS == 'WINNT') $cmd = "start /b $cmd";
                exec($cmd);

                return $app->control->locate($app->control->createLink('repo', 'showSyncCommit', "repoID={$repo->id}"));
            }
        }

        $branch = isset($_COOKIE['repoBranch']) ? $_COOKIE['repoBranch'] : '';
        if($branch)
        {
            $branches = $this->branch();
            if(isset($branches[$branch])) $branch = "origin/$branch";
        }
        $this->branch = $branch;

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
        if(!scm::checkRevision($revision)) return array();

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $sub  = '';
        chdir($this->root);
        if(!empty($path)) $sub = ":$path";
        if(!empty($this->branch))$revision = $this->branch;
        execCmd(escapeCmd("$this->client pull"));
        $cmd  = escapeCmd("$this->client ls-tree -l $revision$sub");
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
        if(!scm::checkRevision($revision)) return array();

        chdir($this->root);
        $cmd  = escapeCmd("$this->client tag --sort=taggerdate");
        $list = execCmd($cmd . ' 2>&1', 'array', $result);
        if($result) return array();

        foreach($list as $key => $tag)
        {
            if(!$tag) unset($list[$key]);
        }

        return $list;
    }

    /**
     * Get branch.
     *
     * @access public
     * @return array
     */
    public function branch()
    {
        global $app;
        $apiRoot  = $app->control->loadModel('gitea')->getApiRoot($this->repo->serviceHost);
        $url      = sprintf($apiRoot, "/repos/{$this->repo->serviceProject}/branches");
        $branches = json_decode(commonModel::http($url));
        if(empty($branches)) return array();

        return array_column($branches, 'name', 'name');
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
        global $app;
        $apiRoot = $app->control->loadModel('gitea')->getApiRoot($this->repo->serviceHost);

        $url   = sprintf($apiRoot, "/repos/{$this->repo->serviceProject}/branches");
        $param = new stdclass();
        $param->old_branch_name = $ref;
        $param->new_branch_name = $branchName;
        $result = json_decode(commonModel::http($url, $param, array(), array(), 'json'));

        return array('result' => empty($result->name) ? 'fail' : 'success', 'message' => zget($result, 'message', 'Error'));
    }

    /**
     * 创建标签。
     * Create a tag in the repository.
     *
     * @param string $tagName The name of the tag to be created.
     * @param string $ref     The revision of the tag, a commit SHA, another tag name, or branch name..
     * @param string $comment An optional comment for the tag.
     * @return array Returns an array with the result of the operation and a message. If the tag already exists, the result will be 'fail' and the message will be 'Tag is exists'. If the operation fails, the result will be 'fail' and the message will be 'Created fail.'. Otherwise, the result will be 'success' and the message will be an empty string.
     */
    public function createTag($tagName, $ref, $comment = '')
    {
        $tags = $this->tags('', 'HEAD');
        if(isset($tags[$tagName])) return array('result' => 'fail', 'message' => 'Tag is exists');

        chdir($this->root);
        execCmd(escapeCmd("{$this->client} stash"));
        $res = execCmd(escapeCmd("{$this->client} tag {$tagName} {$ref} -m '{$comment}'"), 'array');
        if(empty($res[0])) return array('result' => 'fail', 'message' => 'Created fail.');

        execCmd(escapeCmd("{$this->client} push origin {$tagName}"), 'array');
        return array('result' => 'success', 'message' => '');
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
        if(!scm::checkRevision($fromRevision)) return array();
        if(!scm::checkRevision($toRevision))   return array();

        execCmd(escapeCmd("$this->client pull"));

        $path  = ltrim($path, DIRECTORY_SEPARATOR);
        $count = $count == 0 ? '' : "-n $count";
        /* compatible with svn. */
        if($fromRevision === 'HEAD' and $this->branch) $fromRevision = $this->branch;
        if($toRevision   === 'HEAD' and $this->branch) $toRevision   = $this->branch;
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
     * @param  bool   $showComment
     * @access public
     * @return array
     */
    public function blame($path, $revision, $showComment = true)
    {
        if(!scm::checkRevision($revision)) return array();

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        chdir($this->root);
        $list = execCmd(escapeCmd("$this->client blame -c -l $revision -- $path"), 'array');

        $blames   = array();
        $revLine  = 0;
        $revision = '';
        foreach($list as $line)
        {
            if(empty($line)) continue;
            if($line[0] == '^') $line = substr($line, 1);
            preg_match('/^([0-9a-f]{39,40})\s.*\(\s*(\S+)\s+([\d-]+)\s(.*)\s(\d+)\)(.*)$/U', $line, $matches);

            if(isset($matches[1]) and $matches[1] != $revision)
            {
                $blame = array();
                $blame['revision']  = $matches[1];
                $blame['committer'] = $matches[2];
                $blame['time']      = $matches[3];
                $blame['line']      = $matches[5];
                $blame['lines']     = 1;
                $blame['content']   = strpos($matches[6], ' ') === false ? $matches[6] : substr($matches[6], 1);

                $log = $this->log('', '', '', 1);
                $blame['message'] = $log[0]->comment;

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
     * @param  string $extra
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision, $extra = '')
    {
        if(!scm::checkRevision($fromRevision) and $extra != 'isBranchOrTag') return array();
        if(!scm::checkRevision($toRevision) and $extra != 'isBranchOrTag')   return array();

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        chdir($this->root);
        if($toRevision == 'HEAD' and $this->branch) $toRevision = $this->branch;
        if($fromRevision == '^') $fromRevision = $toRevision . '^';
        if(strpos($fromRevision, '^') !== false)
        {
            $list = execCmd(escapeCmd("$this->client log -2 $toRevision --pretty=format:%H -- $path"), 'array');
            if(!isset($list[1])) return execCmd(escapeCmd("$this->client show HEAD"), 'array');

            $fromRevision = $list[1];
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
        if(!scm::checkRevision($revision)) return false;

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
        if(!scm::checkRevision($revision)) return false;

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

        $newFile  = false;
        $allFiles = array();
        for($i = 0; $i < $num; $i ++)
        {
            $diffFile = new stdclass();
            if(strpos($lines[$i], "diff --git ") === 0)
            {
                $fileInfo = explode(' ',$lines[$i]);
                $fileName = substr($fileInfo[2], strpos($fileInfo[2], '/') + 1);

                /* Prevent duplicate display of files. */
                if(in_array($fileName, $allFiles)) continue;
                $allFiles[] = $fileName;

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
                            $newLine->line  = htmlSpecialString($line);

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
        if(!scm::checkRevision($lastVersion)) return false;

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
     * @param  string $rversion
     * @param  int    $count
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getCommits($revision = '', $count = 0, $branch = '')
    {
        if(!scm::checkRevision($revision)) return array();

        if($revision == 'HEAD' and $branch)
        {
            $revision = $branch;
        }
        elseif(is_numeric($revision))
        {
            $revision = "--skip=$revision $branch";
        }
        $count = $count == 0 ? '' : "-n $count";

        chdir($this->root);
        if($branch)
        {
            execCmd(escapeCmd("$this->client checkout -b $branch"));
            execCmd(escapeCmd("$this->client checkout $branch"));
            execCmd(escapeCmd("$this->client pull"));
        }

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
                $file = explode("\t", end($file));
                if(!isset($file[1])) $file[1] = '';
                list($action, $path) = $file;

                $parsedFile = new stdclass();
                $parsedFile->revision   = $hash;
                $parsedFile->path       = '/' . trim($path);
                $parsedFile->oldPath    = isset($file[2]) ? '/' . trim($file[2]) : '';
                $parsedFile->type       = 'file';
                $parsedFile->action     = substr($action, 0, 1);
                $logs['files'][$hash][] = $parsedFile;
            }
        }
        return $logs;
    }

    /**
     * Get clone url.
     *
     * @access public
     * @return string
     */
    public function getCloneUrl()
    {
        $url      = new stdclass();
        $remote   = execCmd(escapeCmd("$this->client remote -v"), 'array');
        $pregHttp = '/http(s)?:\/\/(www\.)?[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+)*(\/\w+)*\.git/';
        $pregSSH  = '/ssh:\/\/git@[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+)*(\/\w+)*\.git/';

        if(preg_match($pregHttp, $remote[0], $matches)) $url->http = $matches[0];
        if(preg_match($pregSSH,  $remote[0], $matches)) $url->ssh  = $matches[0];

        return $url;
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
                $lineList = explode("\t", $line);
                list($action, $entry) = $lineList;
                $entry = '/' . trim($entry);
                $pathInfo = array();
                $pathInfo['action']  = substr($action, 0, 1);
                $pathInfo['kind']    = 'file';
                $pathInfo['oldPath'] = isset($lineList[2]) ? '/' . trim($lineList[2]) : '';
                $changes[$entry]     = $pathInfo;
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

    /**
     * Get download url.
     *
     * @param  string $branch
     * @param  string $savePath
     * @param  string $ext
     * @access public
     * @return string
     */
    public function getDownloadUrl($branch = 'master', $savePath = '', $ext = 'zip')
    {
        global $app;
        $api = $app->control->loadModel('gitea')->getApiRoot($this->repo->serviceHost);
        return sprintf($api, "/repos/{$this->repo->serviceProject}/archive/{$branch}.zip");
    }

    /**
     * List all files.
     *
     * @param  string $path
     * @param  string $revision
     * @param  array  $lists
     * @access public
     * @return array
     */
    public function getAllFiles($path, $revision = 'HEAD', &$lists = array())
    {
        if(!scm::checkRevision($revision)) return array();

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $sub  = '';
        chdir($this->root);
        if(!empty($path)) $sub = ":$path";
        if(!empty($this->branch))$revision = $this->branch;
        $cmd = escapeCmd("$this->client ls-tree -l $revision$sub");
        $list = execCmd($cmd . ' 2>&1', 'array', $result);
        if($result) return array();

        foreach($list as $entry)
        {
            list($mod, $kind, $revision, $size, $name) = preg_split('/[\t ]+/', $entry);

            /* Get commit info. */
            $pathName = ltrim($path . DIRECTORY_SEPARATOR . $name, DIRECTORY_SEPARATOR);
            if($kind == 'tree')
            {
                $this->getAllFiles($pathName, $revision, $lists);
            }
            else
            {
                $lists[] = rtrim($pathName, DIRECTORY_SEPARATOR);
            }
        }

        return $lists;
    }

    /**
     * 通过API创建合并请求。
     * Create mr by api.
     *
     *  @param object $MR
     *  @param string $openID
     *  @param string $assignee
     *  @access public
     *  @return object|null
     */
    public function createMR(object $MR, string $openID, string $assignee): object|null
    {
        $MRObject = new stdclass();
        $MRObject->title = $MR->title;
        $MRObject->head  = $MR->sourceBranch;
        $MRObject->base  = $MR->targetBranch;
        $MRObject->body  = $MR->description;
        if(!empty($assignee)) $MRObject->assignee = $assignee;

        global $app;
        $url = sprintf($app->control->loadModel('gitea')->getApiRoot($MR->hostID), "/repos/{$MR->sourceProject}/pulls");
        $MR  = json_decode(commonModel::http($url, $MRObject));
        if(isset($MR->number)) $MR->iid = $MR->number;
        if(isset($MR->mergeable))
        {
            if($MR->mergeable)  $MR->merge_status = 'can_be_merged';
            if(!$MR->mergeable) $MR->merge_status = 'cannot_be_merged';
        }
        if(isset($MR->state)  && $MR->state == 'open') $MR->state = 'opened';
        if(isset($MR->merged) && $MR->merged)          $MR->state = 'merged';
        return $MR;
    }

    /**
     * Get a mr by api.
     *
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function getSingleMR(int $MRID): null|object
    {
        $hostID    = $this->repo->serviceHost;
        $projectID = $this->repo->serviceProject;

        global $app;
        $apiRoot = $app->control->loadModel('gitea')->getApiRoot($hostID);
        $url     = sprintf($apiRoot, "/repos/{$projectID}/pulls/$MRID");
        $MR      = json_decode(commonModel::http($url));
        if(!$MR || isset($MR->message) || isset($MR->errors)) return null;

        if(isset($MR->url) || isset($MR->html_url))
        {
            $diff = common::http(sprintf($apiRoot, "/repos/$projectID/pulls/$MRID.diff"));

            $MR->web_url = $MR->url;
            $MR->iid     = $MR->number;
            $MR->state   = $MR->state == 'open' ? 'opened' : $MR->state;
            if($MR->merged) $MR->state = 'merged';

            $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
            $MR->changes_count     = empty($diff) ? 0 : 1;
            $MR->description       = $MR->body;
            $MR->target_branch     = $MR->base->ref;
            $MR->source_branch     = $MR->head->ref;
            $MR->source_project_id = $projectID;
            $MR->target_project_id = $projectID;
            $MR->has_conflicts     = empty($diff) ? true : false;
            $MR->is_draft          = strpos($MR->title, 'Draft:') === 0;
        }
        return $MR;
    }
}
