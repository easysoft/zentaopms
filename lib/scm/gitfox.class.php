<?php
class gitfoxRepo
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
        $param->includeCommit = true;
        $param->gitRef        = $revision;
        if(!empty($this->branch)) $param->gitRef = $this->branch;

        $result = $this->fetch($api, $param);
        $list   = !empty($result->data) ? $result->data : array();
        if(empty($list) || !isset($list->content->entries)) return array();

        $list = $list->content->entries;
        if(empty($list)) return array();

        $files   = $this->fetch('paths/details', array(), false, array('gitRef' => $param->gitRef, 'paths' => array_column($list, 'path')));
        $commits = array();
        foreach($files->data->details as $file) $commits[$file->path] = $file->last_commit;

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
        $param->gitRef        = $ref;
        $param->includeCommit = true;
        $result = $this->fetch($api, $param);
        if(!isset($result->data)) return false;

        $file = $result->data;
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
     * @param  string $showDetail
     * @param  string $revision
     * @param  bool   $onlyDir
     * @param  string $orderBy
     * @param  int    $limit
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function tags($showDetail = '', $revision = 'HEAD', $onlyDir = true, string $orderBy = '', int $limit = 0, int $pageID = 0)
    {
        $api  = 'tags/list';
        $tags = array();

        $params = array();
        $params['pageSize'] = $limit ? $limit : 100;

        if(empty($orderBy)) $orderBy = 'date_desc';
        $sort = explode('_', $orderBy);
        $params['sort']  = $sort[0] == 'date' ? 'date' : $sort[0];
        $params['order'] = isset($sort[1]) ? $sort[1] : 'asc';
        if($showDetail) $params['includeCommit'] = true;
        if($showDetail && $showDetail != 'all') $params['query'] = $showDetail;
        for($page = $pageID; true; $page ++)
        {
            $params['page'] = $page;
            $result  = $this->fetch($api, array(), false, $params);
            $tagList = isset($result->data) ? $result->data : array();
            $isLast  = isset($result->listArgs) ? $result->listArgs->isLast : true;

            if(empty($tagList) || !is_array($tagList)) break;
            foreach($tagList as $tag) $tags[] = $showDetail ? $tag : $tag->name;
            if(!empty($showDetail) || $isLast) break;
        }

        return $tags;
    }

    /**
     * Get branches.
     *
     * @access public
     * @param  string $showDetail
     * @param  string $orderBy
     * @param  int    $limit
     * @param  int    $pageID
     * @param  string $label
     * @param  string $showArchived
     * @return array
     */
    public function branch(string $showDetail = '', string $orderBy = '', int $limit = 0, int $pageID = 1, string $label = '', string $showArchived = 'active')
    {
        $params = array();
        /* Max size of per_page in gitfox API is 100. */
        if(in_array($showArchived, array('active', 'all'))) $params['includeArchived'] = $showArchived == 'all';
        if($showArchived == 'archive')
        {
            unset($params['includeArchived']);
            $params['archivedOnly'] = true;
        }
        if($showDetail) $params['includeCommit'] = true;
        $params['pageSize'] = $limit ? $limit : 100;

        if(empty($orderBy)) $orderBy = 'date_desc';
        $sort = explode('_', $orderBy);
        $params['sort']  = $sort[0] == 'commitDate' ? 'date' : $sort[0];
        $params['order'] = isset($sort[1]) ? $sort[1] : 'asc';
        if($showDetail && $showDetail != 'all') $params['query']         = (string)$showDetail;
        if($label && $label != 'all')           $params['branchTypeIDs'] = array((int)$label);

        $branches = array();
        $default  = array();
        for($page = $pageID; true; $page ++)
        {
            $params['page'] = $page;
            $branchList = $this->fetch("branches/list", array(), false, $params);
            if(empty($branchList) || empty($branchList->data) || !is_array($branchList->data)) break;
            $branchList = $branchList->data;

            $i = 1;
            foreach($branchList as $branch)
            {
                $branch->id = $i++;
                if(!isset($branch->name)) continue;
                if($branch->isDefault)
                {
                    $default[$branch->name] = $showDetail ? $branch : $branch->name;
                }
                else
                {
                    $branches[$branch->name] = $showDetail ? $branch : $branch->name;
                }
            }

            /* Last page. */
            if($limit || count($branchList) < $params['pageSize']) break;
        }

        $branches = $default + $branches;

        if($showDetail)
        {
            if(empty($branches)) return array();

            if(empty($default))
            {
                $project = $this->fetch();
                $default = array($project->data->defaultBranch => $project->data->defaultBranch);
            }

            $branches = $this->getBranchesCommitsDivergence($branches, key($default));
        }
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
        $param->source       = $ref;
        $param->name         = $branchName;
        $server = $app->control->loadModel('gitfox');
        $result = $server->apiCreateBranch($this->repo->id, $param);

        return $server->getResponse($result);
    }

    /**
     * Create a new Git tag with the given tag name, reference, and optional comment.
     *
     * @param string $tagName The name of the tag to be created.
     * @param string $ref     The reference to which the tag should be created, It can be a commit SHA, another tag name, or branch name..
     * @param string $comment Optional comment message for the tag.
     * @return array An array with the result of the tag creation and an optional message.
     */
    public function createTag($repoID, $tagName, $ref, $comment = '')
    {
        global $app;

        $gitfox = $app->control->loadModel('gitfox');
        $param  = new stdclass();
        $param->name         = $tagName;
        $param->source       = $ref;
        $param->message      = $comment;

        return $gitfox->getResponse($gitfox->apiCreateTag($repoID, $param));
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
    public function blame($path, $revision = 'HEAD')
    {
        if(!scm::checkRevision($revision)) return array();

        $path   = ltrim($path, DIRECTORY_SEPARATOR);
        $path   = urldecode($path);
        $api    = "blame/$path";
        $gitRef = ($revision and $revision != 'HEAD') ? $revision : $this->branch;

        $results = $this->fetch($api, array('gitRef' => $gitRef));
        if(empty($results) || !empty($results->message)) return array();

        $results    = is_object($results) ? array($results) : explode("\n", $results);
        $blames     = array();
        $revision   = '';
        $lineNumber = 1;
        foreach($results as $blame)
        {
            if(empty($blame)) continue;

            $blame = is_object($blame) ? $blame : json_decode($blame);
            $line  = array();
            $line['revision']  = $blame->commit->sha;
            $line['committer'] = $blame->commit->author->Identity->Name;
            $line['message']   = $blame->commit->title;
            $line['time']      = date('Y-m-d H:i:s', strtotime($blame->commit->author->When));
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
     * @param  string $extra
     * @param  bool   $isMr
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision, $extra = '', $isMr = false)
    {
        if(!scm::checkRevision($fromRevision) and $extra != 'isBranchOrTag') return array();
        if(!scm::checkRevision($toRevision) and $extra != 'isBranchOrTag')   return array();

        $sameVersion = $fromRevision == '^' || strpos($fromRevision, $toRevision) === 0;
        if($toRevision == 'HEAD' and $this->branch) $toRevision = $this->branch;

        $api        = $sameVersion ? "commits/diff" : 'diff';
        $comparator = $isMr ? '...' : '..';
        $params     = $sameVersion ? array('sha' => $toRevision) : array('range' => "{$fromRevision}{$comparator}{$toRevision}", 'files' => array());
        $diffs      = $this->fetch($api, array(), false, $params);
        if(!$diffs || isset($diffs->message)) return array();

        return explode("\n", trim($diffs));
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
            $result = $this->tree($parent, 0);
            $data   = !empty($result->data) ? $result->data : array();
            $list   = !empty($data->content->entries) ? $data->content->entries : array();
            $file = new stdclass();

            if(!empty($list)) foreach($list as $node) if($node->path == $entry) $file = $node;

            $commits = $this->getCommitsByPath($entry);

            if(!empty($commits)) $file->revision = zget($commits[0], 'id', '');
            $info->kind = (!empty($file->type)) ? $file->type : 'file';
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
            if(strpos($lines[$i], "diff --git ") !== 0) continue;

            $fileInfo = explode(' ',$lines[$i]);
            $fileName = substr($fileInfo[2], strpos($fileInfo[2], '/') + 1);

            /* Prevent duplicate display of files. */
            if(in_array($fileName, $allFiles)) continue;
            $allFiles[] = $fileName;

            $diffFile->fileName = $fileName;
            for($i ++; $i < $num; $i ++)
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
        $api     = "commits/list";
        $commits = array();
        $files   = array();

        if(empty($count)) $count = 10;
        if(!empty($version) and $count == 1)
        {
            $api    = 'commits';
            $commit = $this->fetch($api, array('sha' => $version));
            if($commit && !empty($commit->data) && !empty($commit->data->sha))
            {
                $commit = $commit->data;

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
        $params['gitRef']   = $branch;
        $params['pageSize'] = $count;

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

        $list = $this->fetch($api, array(), true, $params);
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

        $result = $this->fetch('commits', array('sha' => $sha));
        if(!isset($result->data)) return false;

        $data = $result->data;
        return isset($data->committer->when) ? $data->committer->when : false;
    }

    /**
     * Get commits by path.
     *
     * @param  string    $path
     * @param  string    $fromRevision
     * @param  string    $toRevision
     * @param  int       $perPage
     * @param  int       $page
     * @param  string    $beginDate
     * @param  string    $endDate
     * @param  string    $committer
     * @access public
     * @return array
     */
    public function getCommitsByPath($path, $fromRevision = '', $toRevision = '', $perPage = 0, $page = 1, $beginDate = '', $endDate = '', $committer = '')
    {
        if(!scm::checkRevision($toRevision)) $toRevision = '';

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $api  = "commits/list";

        $param = new stdclass();
        $param->path   = urldecode($path);
        $param->gitRef = $toRevision ? $toRevision : $this->branch;

        $fromDate = $fromRevision ? $this->getCommittedDate($fromRevision) : $beginDate;
        $toDate   = $toRevision ? $this->getCommittedDate($toRevision) : $endDate;

        $since = '';
        $until = '';
        if(($fromRevision && $toRevision && $fromRevision != $toRevision) || ($beginDate && $endDate))
        {
            $since = min($fromDate, $toDate);
            $until = max($fromDate, $toDate);
        }
        else
        {
            if($fromRevision || $beginDate) $since = $fromDate;
            if($toRevision   || $endDate)   $until = $toDate;
        }

        if($since) $param->since = strtotime($since);
        if($until) $param->until = strtotime($until);

        if($perPage) $param->pageSize = $perPage;
        if($page)    $param->page     = $page;
        if($committer) $param->author = $committer;

        $result = $this->fetch($api, array(), false, $param);
        return empty($result->data) || empty($result->data->commits) ? array() : $result->data->commits;
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
        $api = 'content';

        $params = array();
        $params['filepath'] = ltrim($path, '/');
        $params['gitRef']   = $this->branch;
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
        ini_set('memory_limit', '-1');

        $params = (array) $params;
        if(empty($data)) $params['limit'] = isset($params['limit']) ? $params['limit'] : 100;

        $accept = empty($data) ? 'text/plain' : '*/*';
        $header = self::buildAuthHeader($this->token, '', '', $accept);
        if(!empty($data))
        {
            if(is_array($data) && isset($data['pageSize']) && isset($data['page']))
            {
                $data['pageSize'] = (int)$data['pageSize'];
                $data['page']     = (int)$data['page'];
            }
            if(is_object($data) && isset($data->pageSize) && isset($data->page))
            {
                $data->pageSize = (int)$data->pageSize;
                $data->page     = (int)$data->page;
            }
        }

        $api = ltrim($api, '/');
        $api = "{$this->root}{$api}?" . http_build_query($params);

        if($needToLoop)
        {
            $allResults = array();
            if(!empty($params))
            {
                for($page = 1; true; $page++)
                {
                    $results = json_decode(commonModel::http($api . "&page={$page}", $data, array(), $header));
                    if(is_array($results))
                    {
                        $allResults = array_merge($allResults, $results);
                        if(count($results) < $params['limit']) break;
                        continue;
                    }

                    if(isset($results->content)) $results = $results->content;
                    if(empty($results->$field) || !is_array($results->$field)) break;
                    if(!empty($results->$field)) $allResults = array_merge($allResults, $results->$field);
                    if(count($results->$field) < $params['limit']) break;
                }
            }
            else
            {
                for($i = 0; true; $i++)
                {
                    $data['page'] = $i + 1;
                    $results = json_decode(commonModel::http($api, $data, array(), $header, 'json'));
                    if(empty($results) || empty($results->data)) break;
                    if(isset($results->data->commits) && empty($results->data->commits)) break;
                    $allResults = array_merge($allResults, strpos($api, 'commits/list') != false ? $results->data->commits : $results->data);
                    if(!empty($results->pager) && $results->pager->pageSize < 100) break;
                }
            }

            return $allResults;
        }
        else
        {
            $response = commonModel::http($api, $data, array(), $header, 'json');
            if(self::clearHttpErrors()) return array();

            $result = json_decode($response);
            return $result ? $result : $response;
        }
    }

    /**
     * 构造 gitfox 接口鉴权请求头。
     * Build common auth headers for gitfox HTTP requests, reused by gitfoxRepo and subversionRepo.
     *
     * @param  string $token       gitfox token,会原样放进 Authorization
     * @param  string $operator    操作人账号,缺省时取 $app->user->account
     * @param  string $contentType 请求体 Content-Type,默认 application/json；传空串则不带此头
     * @param  string $accept      Accept 头值,默认 text/plain
     * @static
     * @access public
     * @return array
     */
    public static function buildAuthHeader($token, $operator = '', $contentType = 'application/json', $accept = 'text/plain')
    {
        global $app;

        if($operator === '') $operator = isset($app->user->account) ? $app->user->account : '';
        $apiLanguage = common::checkNotCN() ? 'en-US' : 'zh-CN';

        $headers = array(
            "Authorization: {$token}",
            "Accept: {$accept}",
            "APP: zentao",
            "Operator: {$operator}",
            "Accept-Language: {$apiLanguage}"
        );
        if($contentType !== '') $headers[] = "Content-Type: {$contentType}";

        return $headers;
    }

    /**
     * 检查并清空 commonModel 残留请求错误。有错时返 true,无错返 false。
     * Drain commonModel::$requestErrors, return true if there were errors.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function clearHttpErrors()
    {
        if(empty(commonModel::$requestErrors)) return false;
        commonModel::$requestErrors = array();
        return true;
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
    public function getDownloadUrl($branch = 'main', $savePath = '', $ext = 'zip')
    {
        $url  = "{$this->repo->client}/api/v2/na/repos/{$this->repo->id}/archive";
        return "{$url}/{$branch}.{$ext}";
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
        $data    = isset($project->data) ? $project->data : '';

        if(!$data) return $url;
        if($data->gitURL)    $url->http = $data->gitURL;
        if($data->gitSSHURL) $url->ssh  = $data->gitSSHURL;
        return $url;
    }

    /**
     * 通过API创建合并请求。
     * Create mr by api.
     *
     *  @param  object $MR
     *  @param  string $openID
     *  @access public
     *  @return object|null
     */
    public function createMR(object $MR, string $openID): object|null
    {
        $MRObject = new stdclass();
        $MRObject->title = $MR->title;
        $MRObject->source_branch  = $MR->sourceBranch;
        $MRObject->target_branch  = $MR->targetBranch;
        $MRObject->description  = $MR->description;

        global $app;
        $url = "{$this->root}pullreq";
        if(!$app->user->admin) $url .= "?sudo={$openID}";

        $MR = json_decode(commonModel::http($url, $MRObject, array(), array("Authorization: Bearer {$this->token}"), 'json'));
        if(isset($MR->number)) $MR->iid = $MR->number;
        if(isset($MR->merge_check_status))
        {
            $MR->merge_status = $MR->merge_check_status == 'mergeable' ? 'can_be_merged' : 'cannot_be_merged';
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
        $MR = $this->fetch("pullreq/{$MRID}");
        $MR->id                = $MR->number;
        $MR->iid               = $MR->number;
        $MR->state             = $MR->state == 'open' ? 'opened' : $MR->state;
        $MR->merge_status      = $MR->merge_check_status != 'mergeable' ? 'cannot_be_merged' : 'can_be_merged';
        $MR->changes_count     = $MR->stats->commits;
        $MR->source_project_id = $MR->source_repo_id;
        $MR->target_project_id = $MR->target_repo_id;
        $MR->has_conflicts     = $MR->merge_check_status == 'conflict';
        $MR->changes_count     = 1;
        if($MR->merged) $MR->state = 'merged';
        return $MR;
    }

    /**
     * Get pipeline list by api.
     *
     * @access public
     * @return array
     */
    public function pipelines(): array
    {
        return $this->fetch('pipelines', array(), true);
    }

    /**
     * 获取代码库的提交次数。
     * Get commit count by repo.
     *
     * @param  string $repoID
     * @param  string $period
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array|object
     */
    public function getCodeFrequencyByRepo(string $repoID, string $period, string $begin = '', string $end = ''): array|object
    {
        if(!$begin) $begin = date('Y-m-d', strtotime('-1 year'));
        if(!$end)   $end   = date('Y-m-d');

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->period     = $period;
        $param->repo       = $repoID;
        $result = $this->fetch('statistics/repo/code-frequency', array(), false, $param);
        if(empty($result) || isset($result->message) || empty($result->stats)) return array();
        return $result;
    }

    /**
     * 获取代码库的提交次数。
     * Get commit count by repo.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array|object
     */
    public function getCodeFrequencyByUser(string $account, string $period, string $begin = '', string $end = ''): array|object
    {
        if(!$begin) $begin = date('Y-m-d', strtotime('-1 year'));
        if(!$end)   $end   = date('Y-m-d');

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->period     = $period;
        $param->user       = $account;
        $result = $this->fetch('statistics/user/code-frequency', array(), false, $param);
        if(empty($result) || isset($result->message) || empty($result->stats)) return array();
        return $result;
    }

    /**
     * 获取分支与默认分支的差异。
     * Get branches and default branch divergence.
     *
     * @param  array  $branches
     * @param  string $defaultBranch
     * @access public
     * @return array
     */
    public function getBranchesCommitsDivergence(array $branches, string $defaultBranch = ''): array
    {
        $params = array();
        $params['maxDivergence'] = 0;

        $diffBranchList = array();
        foreach(array_keys($branches) as $index => $branch)
        {
            $diffBranchList[$index]['from'] = (string)$branch;
            $diffBranchList[$index]['to']   = (string)$defaultBranch;
        }

        $params['requests'] = $diffBranchList;

        $commitsDivergenceList = $this->fetch("commits/divergence", array(), false, $params);
        if(empty($commitsDivergenceList) || !is_array($commitsDivergenceList->data)) return $branches;

        foreach(array_keys($branches) as $index => $branch)
        {
            $branches[$branch]->divergence    = $commitsDivergenceList->data[$index];
            $branches[$branch]->defaultBranch = $defaultBranch;
        }

        return $branches;
    }

    /**
     * 统计活跃仓库。
     * Count active repos.
     *
     * @param  array  $repos
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array|object
     */
    public function countActiveRepos(array $repos, string $begin = '', string $end = ''): array|object
    {
        if(!$begin) $begin = date('Y-m-d', strtotime('-1 year'));
        if(!$end)   $end   = date('Y-m-d');

        $repoIdMap = array();
        foreach($repos as $repoId) $repoIdMap[] = (string)$repoId;

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->repos      = $repoIdMap;
        $result = $this->fetch('statistics/repos/active', array(), false, $param);
        if(empty($result) || isset($result->message) || empty($result->commit_count)) return array();

        return $result;
    }

    /**
     * 统计制品数。
     * Count artifacts.
     *
     * @access public
     * @return array|object
     */
    public function countArtifacts(): array|object
    {
        $result = $this->fetch('artifacts/statistic', array(), false);
        if(empty($result) || isset($result->message) || empty($result->all)) return array();

        return $result;
    }
}
