<?php
class gitfox
{
    public $client;
    public $projectID;
    public $root;
    public $token;
    public $branch;
    public $repo;

    /**
     * Construct
     *
     * @param  string $client    gitfox api url.
     * @param  string $root      id of gitfox project.
     * @param  string $username  null
     * @param  string $password  token of gitfox api.
     * @param  string $encoding
     * @param  object $repo
     * @access public
     * @return void
     */
    public function __construct($client, $root, $username, $password, $encoding = 'UTF-8', $repo = null)
    {
        $this->client = $client;
        $this->root   = rtrim($root, '/') . '/';
        $this->token  = $password;
        $this->branch = isset($_COOKIE['repoBranch']) ? $_COOKIE['repoBranch'] : 'HEAD';
        $this->repo   = $repo;
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
        $path  = ltrim($path, '/');
        $api   = rtrim("content/{$path}", '/');
        $param = new stdclass();
        $param->include_commit = 'true';
        $param->git_ref        = $revision;
        if(!empty($this->branch)) $param->git_ref = $this->branch;

        $list = $this->fetch($api, $param, true, array(), 'entries');
        if(empty($list)) return array();

        $files   = $this->fetch('path-details', array('git_ref' => $param->git_ref), false, array('paths' => array_column($list, 'path')));
        $commits = array();
        foreach($files->details as $file) $commits[$file->path] = $file->last_commit;

        $fileList = array();
        foreach($list as $file)
        {
            if(!isset($file->type)) continue;

            $info = new stdClass();
            $info->name     = $file->name;
            $info->path     = $file->path;
            $info->kind     = $file->type;
            $info->revision = zget($commits[$file->path], 'sha', '');
            $info->comment  = zget($commits[$file->path], 'title', '');
            $info->account  = zget($commits[$file->path]->author->identity, 'name', '');
            $info->date     = date('Y-m-d H:i:s', strtotime($commits[$file->path]->author->when));
            $info->size     = 0;

            $fileList[] = $info;
            unset($info);
        }

        /* Sort by kind */
        foreach($fileList as $key => $info) $kinds[$key] = $info->kind;
        if($fileList) array_multisort($kinds, SORT_ASC, $fileList);
        return $fileList;
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
     * @doc    https://docs.gitfox.com/ee/api/repository_files.html
     */
    public function files($path, $ref = 'master')
    {
        $path = urldecode($path);
        $api  = "content/$path";
        $param = new stdclass();
        $param->git_ref        = $ref;
        $param->include_commit = 'true';
        $file = $this->fetch($api, $param);
        if(!isset($file->name)) return false;

        $file->file_name = $file->name;
        $file->revision  = $file->latest_commit->sha;
        $file->comment   = $file->latest_commit->title;
        $file->date      = date('Y-m-d H:i:s', strtotime($file->latest_commit->author->when));
        $file->committer = $file->latest_commit->author->identity->name;
        $file->size      = $this->formatBytes($file->content->size);

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
    public function tags()
    {
        $api  = 'tags';
        $tags = array();

        $params = array();
        $params['limit'] = '100';
        $params['sort']  = 'date';
        $params['order'] = 'desc';
        for($page = 1; true; $page ++)
        {
            $params['page'] = $page;
            $list = $this->fetch($api, $params);
            if(empty($list) || !is_array($list)) break;

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
        /* Max size of per_page in gitfox API is 100. */
        $params = array();
        $params['limit'] = '100';
        $params['sort']  = 'date';
        $params['order'] = 'asc';

        $branches = array();
        for($page = 1; true; $page ++)
        {
            $params['page'] = $page;
            $branchList = $this->fetch("branches", $params);
            if(empty($branchList) || !is_array($branchList)) break;

            foreach($branchList as $branch)
            {
                if(!isset($branch->name)) continue;
                $branches[$branch->name] = $branch->name;
            }

            /* Last page. */
            if(count($branchList) < $params['limit']) break;
        }

        asort($branches);
        return $branches;
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

        $param = new stdclass();
        $param->bypass_rules = false;
        $param->target       = $ref;
        $param->name         = $branchName;
        $result = $app->control->loadModel('gitfox')->apiCreateBranch($this->repo->serviceHost, $this->repo->serviceProject, $param);

        return array('result' => empty($result->name) ? 'fail' : 'success', 'message' => empty($result->name) ? $result->message : '');
    }

    /**
     * Get last log.
     *
     * @param  string $path
     * @access public
     * @return array
     */
    public function getLastLog($path)
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

        $list = $this->getCommitsByPath($path, $fromRevision, $toRevision);
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
        if(!scm::checkRevision($revision)) return array();

        $path  = ltrim($path, DIRECTORY_SEPARATOR);
        $path  = urldecode($path);
        $api   = "blame/$path";
        $param = new stdclass;
        $param->git_ref = ($revision and $revision != 'HEAD') ? $revision : $this->branch;
        $results = $this->fetch($api, $param);
        if(empty($results) or isset($results->message)) return array();

        $blames   = array();
        $revision = '';

        $lineNumber = 1;
        foreach($results as $blame)
        {
            $line = array();
            $line['revision']  = $blame->commit->sha;
            $line['committer'] = $blame->commit->author->identity->name;
            $line['message']   = $blame->commit->title;
            $line['time']      = date('Y-m-d H:i:s', strtotime($blame->commit->author->when));
            $line['line']      = $lineNumber;
            $line['lines']     = count($blame->lines);
            $line['content']   = array_shift($blame->lines);

            $blames[$lineNumber] = $line;

            $lineNumber ++;

            foreach($blame->lines as $line)
            {
                $blames[$lineNumber] = array('line' => $lineNumber, 'content' => $line);
                $lineNumber ++;
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
     * @param  string $fromProject
     * @param  string $extra
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision, $fromProject = '', $extra = '')
    {
        if(!scm::checkRevision($fromRevision) and $extra != 'isBranchOrTag') return array();
        if(!scm::checkRevision($toRevision) and $extra != 'isBranchOrTag')   return array();

        $sameVersion = $fromRevision == '^' || strpos($fromRevision, $toRevision) === 0;
        if($toRevision == 'HEAD' and $this->branch) $toRevision = $this->branch;

        $api   = $sameVersion ? "commits/$toRevision/diff" : "diff/{$fromRevision}...{$toRevision}";
        $diffs = $this->fetch($api);
        if(!$diffs || isset($diffs->message)) return array();

        return explode("\n", $diffs);
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
        return isset($file->content->data) ? base64_decode($file->content->data) : '';
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

            if(!empty($list)) foreach($list as $node) if($node->path == $entry) $file = $node;

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
     * @return array
     */
    public function exec()
    {
        return true;
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
                            $type = $sign != '-' ? ($sign == '+' ? 'new' : 'all') : 'old';
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
        return true;
    }

    /**
     * Get first revision.
     *
     * @access public
     * @return string
     */
    public function getFirstRevision()
    {
        return true;
    }

    /**
     * Get latest revision
     *
     * @access public
     * @return string
     */
    public function getLatestRevision()
    {
        return true;
    }

    /**
     * Get commits.
     *
     * @param  string $version
     * @param  int    $count
     * @param  string $branch
     * @param  bool   $getFile
     * @access public
     * @return array
     */
    public function getCommits($version = '', $count = 0, $branch = '', $getFile = false)
    {
        if(!scm::checkRevision($version)) return array();
        $api     = "commits";
        $commits = array();
        $files   = array();

        if(empty($count)) $count = 10;
        if(!empty($version) and $count == 1)
        {
            $api .= '/' . $version;
            $commit = $this->fetch($api, array('limit' => 1));
            if($commit && !empty($commit->sha))
            {
                $log = new stdclass;
                $log->committer = $commit->author->identity->name;
                $log->revision  = $commit->sha;
                $log->comment   = $commit->title;
                $log->time      = date('Y-m-d H:i:s', strtotime($commit->author->when));

                $commits[$commit->sha] = $log;
                if($getFile) $files[$commit->sha] = $this->getFilesByCommit($log->revision);

                return array('commits' => $commits, 'files' => $files);
            }
        }

        $params = array();
        $params['git_ref'] = $branch;
        $params['limit'] = $count;

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

        $list = $this->fetch($api, $params, true);
        foreach($list as $commit)
        {
            if(!is_object($commit)) continue;

            $log = new stdclass;
            $log->committer = $commit->author->identity->name;
            $log->revision  = $commit->sha;
            $log->comment   = $commit->title;
            $log->time      = date('Y-m-d H:i:s', strtotime($commit->author->when));

            $commits[$commit->sha] = $log;
            if($getFile) $files[$commit->sha] = $this->getFilesByCommit($log->revision);
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

        $result = $this->fetch("commits/$sha");
        return isset($result->author->when) ? $result->author->when : false;
    }

    /**
     * Get commits by path.
     *
     * @param  string    $path
     * @param  string    $fromRevision
     * @param  string    $toRevision
     * @param  int       $perPage
     * @param  int       $page
     * @param  bool      $getUrl
     * @access public
     * @return array
     */
    public function getCommitsByPath($path, $fromRevision = '', $toRevision = '', $perPage = 0, $page = 1, $getUrl = false, $beginDate = '', $endDate = '')
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $api = "commits";

        $param = new stdclass();
        $param->path    = urldecode($path);
        $param->git_ref = ($toRevision != 'HEAD' and $toRevision) ? $toRevision : $this->branch;

        $fromDate = $beginDate ? $beginDate : $this->getCommittedDate($fromRevision);
        $toDate   = $endDate ? $endDate : $this->getCommittedDate($toRevision);

        $since = '';
        $until = '';
        if(($fromRevision && $toRevision) || ($beginDate && $endDate))
        {
            $since = min($fromDate, $toDate);
            $until = max($fromDate, $toDate);
        }
        elseif($fromRevision || $beginDate)
        {
            $since = $fromDate;
        }
        if($since) $param->since = strtotime($since);
        if($until) $param->until = strtotime($until);

        if($perPage) $param->limit = $perPage;
        if($page)    $param->page  = $page;

        if($getUrl)
        {
            $params = (array) $param;
            $params['limit']      = isset($params['limit']) ? $params['limit'] : 100;

            $api = ltrim($api, '/');
            $api = $this->root . $api . '?' . http_build_query($params);
            return $api;
        }

        $result = $this->fetch($api, $param);
        return empty($result->commits) ? array() : $result->commits;
    }

    /**
     * Get files by commit.
     *
     * @param  string  $commit
     * @access public
     * @return void
     */
    public function getFilesByCommit($revision)
    {
        return array();
    }

    /**
     * Repository/tree api.
     *
     * @param  string    $path
     * @param  bool      $recursive
     * @param  bool      $loop
     * @access public
     * @return mixed
     */
    public function tree($path)
    {
        $api = 'path-details';

        $params = array();
        $params['repo_ref'] = ltrim($path, '/');
        $params['git_ref']  = $this->branch;
        return $this->fetch($api, $params);
    }

    /**
     * Fetch data from gitfox api.
     *
     * @param  string    $api
     * @param  array     $params
     * @param  bool      $needToLoop
     * @param  array     $data
     * @param  string    $field
     * @access public
     * @return mixed
     */
    public function fetch($api = '', $params = array(), $needToLoop = false, $data = array(), $field = 'details')
    {
        $params = (array) $params;
        if(empty($data)) $params['limit'] = isset($params['limit']) ? $params['limit'] : 100;

        $header = array(
            "Authorization: Bearer {$this->token}",
            "Accept: text/plain"
        );
        if(!empty($data)) $header[1] = 'Accept: */*';

        $api = ltrim($api, '/');
        $api = "{$this->root}{$api}?" . http_build_query($params);

        if($needToLoop)
        {
            $allResults = array();
            for($page = 1; true; $page++)
            {
                $results = json_decode(commonModel::http($api . "&page={$page}", $data, array(), $header));
                if(isset($results->content)) $results = $results->content;
                if(empty($results->$field) || !is_array($results->$field)) break;
                if(!empty($results->$field)) $allResults = array_merge($allResults, $results->$field);
                if(count($results->$field) < 100) break;
            }

            return $allResults;
        }
        else
        {
            $response = commonModel::http($api, $data, array(), $header, 'json');
            if(!empty(commonModel::$requestErrors))
            {
                commonModel::$requestErrors = array();
                return array();
            }

            $result = json_decode($response);
            return $result ? $result : $response;
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
        foreach($logs as $commit)
        {
            if(!isset($commit->sha)) continue;

            $parsedLog = new stdclass();
            $parsedLog->revision  = $commit->sha;
            $parsedLog->committer = $commit->author->identity->name;
            $parsedLog->time      = date('Y-m-d H:i:s', strtotime($commit->author->when));
            $parsedLog->comment   = $commit->message;
            $parsedLog->change    = array();
            if(!empty($commit->diffs))
            {
                foreach($commit->diffs as $diff)
                {
                    $parsedLog->change[$diff->path] = array();
                    $parsedLog->change[$diff->path]['action']  = $diff->action;
                    $parsedLog->change[$diff->path]['kind']    = $diff->type;
                    $parsedLog->change[$diff->path]['oldPath'] = $diff->oldPath;
                }
            }
            $parsedLogs[] = $parsedLog;
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
        return '';
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
    public function getAllFiles($path = '', $revision = 'HEAD', &$lists = array())
    {
        if(!scm::checkRevision($revision)) return array();
        $api = 'path-details';

        $param = new stdclass();
        $param->repo_ref = ltrim($path, '/');
        $param->git_ref  = $revision;
        if(!empty($this->branch)) $param->git_ref = $this->branch;

        $list = $this->fetch($api, $param, true);
        if(empty($list)) return array();

        foreach($list as $file)
        {
            if(!isset($file->type)) continue;

            if($file->type == 'blob')
            {
                $lists[] = $file->path;
            }
            else
            {
                $this->getAllFiles($file->path, $revision, $lists);
            }
        }
        return $lists;
    }

    /**
     * 获取特定对象的api。
     * Get api url for target.
     *
     * @param  string $target
     * @access public
     * @return string
     */
    public function getApiUrl(string $target): string
    {
        if($target == 'project')
        {
            return str_replace('repos/', '', $this->root). "?token={$this->token}";
        }
        $params = array();
        $params['token'] = $this->token;
        $params['page']  = 1;
        $params['limit'] = 100;

        $api = $this->root . $target . '?' . http_build_query($params);
        return $api;
    }

    /**
     * Get repo clone url.
     *
     * @access public
     * @return object
     */
    public function getCloneUrl()
    {
        $project = $this->fetch();
        $url     = new stdclass();
        if(isset($project->git_url)) $url->http = $project->git_url;
        return $url;
    }
}
