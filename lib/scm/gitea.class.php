<?php
class gitea
{
    public  $client;
    public  $projectID;
    private $pageLimit = 50;

    /**
     * Construct
     *
     * @param  string    $client    gitea api url.
     * @param  string    $root      id of gitea project.
     * @param  string    $username  null
     * @param  string    $password  token of gitea api.
     * @param  string    $encoding
     * @access public
     * @return void
     */
    public function __construct($client, $root, $username, $password, $encoding = 'UTF-8')
    {
        $this->client = $client;
        $this->root   = rtrim($root, '/') . '/';
        $this->token  = $password;
        $this->branch = isset($_COOKIE['repoBranch']) ? $_COOKIE['repoBranch'] : 'HEAD';
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
        $api  = "contents";
        $path = ltrim($path, '/');
        if($path) $api .= "/$path";

        $param = new stdclass();
        $param->ref       = $revision;
        $param->recursive = 0;
        if(!empty($this->branch)) $param->ref = $this->branch;

        $list = $this->fetch($api, $param, true);
        if(empty($list)) return array();

        $infos = array();
        foreach($list as $file)
        {
            if(!isset($file->type)) continue;

            $info = new stdClass();
            $info->name = $file->name;
            $info->kind = $file->type;

            if($file->type == 'file')
            {
                $file = $this->files($file->path, $this->branch);

                $info->revision = zget($file, 'revision', '');
                $info->comment  = zget($file, 'comment', '');
                $info->account  = zget($file, 'committer', '');
                $info->date     = zget($file, 'date', '');
                $info->size     = zget($file, 'size', '');
            }
            else
            {
                $commits = $this->getCommitsByPath($file->path, '', '', 1, 1);
                if(empty($commits) or !is_array($commits)) continue;
                $commit = $commits[0];

                $info->revision = $commit->sha;
                $info->comment  = $commit->commit->message;
                $info->account  = $commit->commit->author->name;
                $info->date     = date('Y-m-d H:i:s', strtotime($commit->commit->author->date));
                $info->size     = 0;
            }

            $infos[] = $info;
            unset($info);
        }

        /* Sort by kind */
        foreach($infos as $key => $info) $kinds[$key] = $info->kind;
        if($infos) array_multisort($kinds, SORT_ASC, $infos);
        return $infos;
    }

    /**
     * Get files info.
     *
     * The API path requested is: "GET /projects/:id/repository/files/:file_path".
     * Known issue of GitLab API: if a '%' in 'file_path', GitLab API will show a error 'file_path should be a valid file path'.
     *
     * @param  string    $path
     * @param  string    $ref
     * @access public
     * @return object
     * @doc    https://docs.gitea.com/ee/api/repository_files.html
     */
    public function files($path, $ref = 'master')
    {
        $path = urlencode($path);
        $api  = "contents/$path";
        $file = $this->fetch($api, array('ref' => $ref));
        if(!isset($file->name)) return false;

        $commits = $this->getCommitsByPath($path, '', '', 1, 1);
        $file->revision = $file->sha;
        $file->size     = $this->formatBytes($file->size);

        if(!empty($commits))
        {
            $commit = $commits[0];

            $file->revision  = $commit->sha;
            $file->committer = $commit->commit->author->name;
            $file->comment   = $commit->commit->message;
            $file->date      = date('Y-m-d H:i:s', strtotime($commit->commit->author->date));
        }

        return $file;
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
        $api  = "tags";
        $tags = array();

        $params = array();
        $params['limit'] = $this->pageLimit;
        for($page = 1; true; $page ++)
        {
            $params['page'] = $page;
            $list = $this->fetch($api, $params);
            if(empty($list) or $list == '[]') break;

            foreach($list as $tag) $tags[] = $tag->name;
            if(count($list) < $params['limit']) break;
        }

        return $tags;
    }

    /**
     * Get branches.
     *
     * @access public
     * @return array
     */
    public function branch()
    {
        /* Max size of limit in gitea API is 50. */
        $params = array();
        $params['limit'] = $this->pageLimit;

        /* Get default branch. */
        $project       = $this->fetch('');
        $defaultBranch = $project->default_branch;

        $branches = array();
        $default  = array();
        for($page = 1; true; $page ++)
        {
            $params['page'] = $page;
            $branchList = $this->fetch("branches", $params);
            if(empty($branchList)) break;

            foreach($branchList as $branch)
            {
                if(!isset($branch->name)) continue;
                if($branch->name == $defaultBranch)
                {
                    $default[$branch->name] = $branch->name;
                }
                else
                {
                    $branches[$branch->name] = $branch->name;
                }
            }

            /* Last page. */
            if(count($branchList) < $params['limit']) break;
        }

        if(empty($branches) and empty($default)) $branches['master'] = 'master';
        asort($branches);

        $branches = $default + $branches;
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
        return $this->log($path);
    }

    /**
     * Get logs.
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

        $path  = ltrim($path, DIRECTORY_SEPARATOR);
        $count = $count == 0 ? '' : "-n $count";
        $list  = $this->getCommitsByPath($path, $fromRevision, $toRevision, 1);
        foreach($list as $commit)
        {
            if(isset($commit->sha)) $commit->diffs = $this->getFilesByCommit($commit->sha);
        }

        return $this->parseLog($list);
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
        return array();
    }

    /**
     * Diff file.
     *
     * @param  string $path
     * @param  string $fromRevision
     * @param  string $toRevision
     * @param  string $fromProject
     * @param  string $extra
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision, $fromProject = '', $extra = '')
    {
        if(!scm::checkRevision($fromRevision) and $extra != 'isBranchOrTag') return array();
        if(!scm::checkRevision($toRevision) and $extra != 'isBranchOrTag')   return array();

        $diffApi = "{$this->root}git/commits/$fromRevision.diff?token={$this->token}";
        $diffs   = commonModel::http($diffApi);
        $lines   = explode("\n", $diffs);
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
        if($revision == 'HEAD' and $this->branch) $revision = $this->branch;
        $file = $this->files($entry, $revision);
        return base64_decode($file->content);
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

        $info = new stdclass();
        $info->kind     = 'dir';
        $info->path     = $entry;
        $info->revision = $revision;
        $info->root     = '';
        if($revision == 'HEAD' and $this->branch) $info->revision = $this->branch;

        if($entry)
        {
            $parent = dirname($entry);
            if($parent == '.') $parent = '/';
            if($parent == '')  $parent = '/';
            $list = $this->tree($parent, 0);
            $file = new stdclass();

            foreach($list as $node) if($node->path == $entry) $file = $node;

            $commits = $this->getCommitsByPath($entry);

            if(!empty($commits)) $file->revision = zget($commits[0], 'id', '');
            $info->kind = (isset($file->type) and $file->type == 'tree') ? 'dir' : 'file';
        }

        return $info;
    }

    /**
     * Exec git cmd.
     *
     * @param  string $cmd
     * @access public
     * @todo Exec commands by gitea api.
     * @return array
     */
    public function exec($cmd)
    {
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
     * @param  string $version
     * @param  int    $count
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getCommits($version = '', $count = 0, $branch = '')
    {
        if(!scm::checkRevision($version)) return array();
        $api     = "commits";
        $commits = array();
        $files   = array();

        if(empty($count)) $count = $this->pageLimit;

        if(!empty($version) and $count == 1)
        {
            $api .= '/' . $version;
            $commit = $this->fetch($api, array('limit' => 1));
            if(isset($commit->sha))
            {
                $log = new stdclass;
                $log->committer = $commit->commit->author->name;
                $log->revision  = $commit->sha;
                $log->comment   = $commit->commit->message;
                $log->time      = date('Y-m-d H:i:s', strtotime($commit->commit->author->date));

                $commits[$commit->sha] = $log;
                $files[$commit->sha]   = $this->getFilesByCommit($log->revision);

                return array('commits' => $commits, 'files' => $files);
            }
        }

        $params['sha']  = $branch;
        if($version and $version != 'HEAD')
        {
            /* Get since param. */
            if(substr($version, 0, 5) == 'since')
            {
                $since   = true;
                $version = substr($version, 5);
            }

            $committedDate = $this->getCommittedDate($version);
            if(!$committedDate) return array('commits' => array(), 'files' => array());

            if(!empty($since))
            {
                $params['since'] = $committedDate;
            }
            else
            {
                $params['until'] = $committedDate;
            }
        }

        $list = $this->fetch($api, $params);

        foreach($list as $commit)
        {
            if(!is_object($commit->commit)) continue;

            $log = new stdclass;
            $log->committer = $commit->commit->author->name;
            $log->revision  = $commit->sha;
            $log->comment   = $commit->commit->message;
            $log->time      = date('Y-m-d H:i:s', strtotime($commit->commit->author->date));

            $commits[$commit->sha] = $log;
            $files[$commit->sha]   = $this->getFilesByCommit($log->revision);
        }

        return array('commits' => $commits, 'files' => $files);
    }

    /**
     * getCommit
     *
     * @param  int    $sha
     * @access public
     * @return void
     */
    public function getCommittedDate($sha)
    {
        if(!scm::checkRevision($sha)) return null;
        if(!$sha or $sha == 'HEAD') return date('c');

        global $dao;
        $time = $dao->select('time')->from(TABLE_REPOHISTORY)->where('revision')->eq($sha)->fetch('time');
        if($time) return date('c', strtotime($time));

        $params = array();
        $params['sha']   = $sha;
        $params['limit'] = 1;
        $result = $this->fetch("commits", $params);
        return (isset($resulti[0]->created)) ? date('Y-m-d H:i:s', strtotime($result->created)) : false;
    }

    /**
     * Get commits by path.
     *
     * @param  string    $path
     * @param  string    $fromRevision
     * @param  string    $toRevision
     * @param  int       $perPage
     * @access public
     * @return array
     */
    public function getCommitsByPath($path, $fromRevision = '', $toRevision = '', $perPage = 0, $limit = 0)
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $api  = "commits";

        if(!$limit) $limit = $this->pageLimit;
        $param = new stdclass();
        $param->path  = urldecode($path);
        $param->limit = $limit;
        $param->sha   = ($toRevision != 'HEAD' and $toRevision) ? $toRevision : $this->branch;

        if($perPage) $param->page = $perPage;
        return $this->fetch($api, $param);
    }

    /**
     * Get files by commit.
     *
     * @param  string    $commit
     * @access public
     * @return void
     */
    public function getFilesByCommit($revision)
    {
        if(!scm::checkRevision($revision)) return array();
        $api     = "contents";
        $results = $this->fetch($api, array('ref' => $revision));
        if(empty($results)) return array();

        $diffApi = "{$this->root}git/commits/$revision.patch?token={$this->token}";
        $diffs = commonModel::http($diffApi);
        if(empty($diffs)) return array();

        $diffs    = explode("\n", $diffs);
        $newFiles = array();
        $delFiles = array();
        foreach($diffs as $row)
        {
            preg_match('/^(\s)(create|delete)\smode\s\d+\s(.+)$/', $row, $matches);
            if(count($matches) == 4 and !in_array($matches[3], $newFiles) and !in_array($matches[3], $delFiles))
            {
                if($matches[2] == 'create')
                {
                    $newFiles[] = $matches[3];
                }
                elseif($matches[2] == 'delete')
                {
                    $delFiles[] = $matches[3];
                }
            }
        }

        $files = array();
        foreach($results as $row)
        {
            $file  = new stdclass();
            $file->revision = $revision;
            $file->type     = $row->type;
            $file->path     = '/' . $row->path;
            $file->action   = 'M';
            if(in_array($row->path, $newFiles))
            {
                $file->action = 'A';
            }
            elseif(in_array($row->path, $delFiles))
            {
                $file->action = 'D';
            }

            $files[$file->path] = $file;
        }

        return array_values($files);
    }

    /**
     * Repository/tree api.
     *
     * @param  string    $path
     * @param  bool      $recursive
     * @access public
     * @return mixed
     */
    public function tree($path, $recursive = 1)
    {
        $api = "contents";

        $params = array();
        $params['path']      = ltrim($path, '/');
        $params['ref']       = $this->branch;
        $params['recursive'] = (int) $recursive;
        return $this->fetch($api, $params);
    }

    /**
     * Fetch data from gitea api.
     *
     * @param  string    $api
     * @access public
     * @return mixed
     */
    public function fetch($api, $params = array(), $needToLoop = false)
    {
        $params = (array) $params;
        $params['token'] = $this->token;
        $params['limit'] = isset($params['limit']) ? $params['limit'] : $this->pageLimit;

        $api = ltrim($api, '/');
        $api = $this->root . $api . '?' . http_build_query($params);
        if($needToLoop)
        {
            $allResults = array();
            for($page = 1; true; $page++)
            {
                $results = json_decode(commonModel::http($api . "&page={$page}"));
                if(!is_array($results)) break;
                if(!empty($results)) $allResults = array_merge($allResults, $results);
                if(count($results) < $this->pageLimit) break;
            }

            return $allResults;
        }
        else
        {
            $response = commonModel::http($api);
            if(!empty(commonModel::$requestErrors))
            {
                commonModel::$requestErrors = array();
                return array();
            }

            return json_decode($response);
        }
    }

    /**
     * Format bytes shown.
     *
     * @param  int    $size
     * @static
     * @access public
     * @return string
     */
    public static function formatBytes($size)
    {
        if($size < 1024) return $size . 'Bytes';
        if(round($size / (1024 * 1024), 2) > 1) return round($size / (1024 * 1024), 2) . 'G';
        if(round($size / 1024, 2) > 1) return round($size / 1024, 2) . 'M';
        return round($size, 2) . 'KB';
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
        foreach($logs as $commit)
        {
            if(!isset($commit->sha)) continue;
            $parsedLog = new stdclass();
            $parsedLog->revision  = $commit->sha;
            $parsedLog->committer = $commit->commit->author->name;
            $parsedLog->time      = date('Y-m-d H:i:s', strtotime($commit->commit->author->date));
            $parsedLog->comment   = $commit->commit->message;
            $parsedLog->change    = array();
            foreach($commit->diffs as $diff)
            {
                $parsedLog->change[$diff->path] = array();
                $parsedLog->change[$diff->path]['action'] = $diff->action;
                $parsedLog->change[$diff->path]['kind']   = $diff->type;
            }
            $parsedLogs[] = $parsedLog;
        }

        return $parsedLogs;
    }

    /**
     * Get download url.
     *
     * @param  string $branch
     * @param  string $ext
     * @access public
     * @return string
     */
    public function getDownloadUrl($branch = 'master', $ext = 'zip')
    {
        $params['token'] = $this->token;

        return "{$this->root}archive/{$branch}.{$ext}" . '?' . http_build_query($params);
    }
}
