<?php
class gitlab
{
    public $client;
    public $projectID;

    /**
     * Construct
     *
     * @param  string    $client    gitlab api url.
     * @param  string    $root      id of gitlab project.
     * @param  string    $username  null
     * @param  string    $password  token of gitlab api.
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
        $api  = "tree";

        $param = new stdclass();
        $param->path      = ltrim($path, '/');
        $param->ref       = $revision;
        $param->recursive = 0;
        if(!empty($this->branch)) $param->ref = $this->branch;

        $list = $this->fetch($api, $param);
        if(empty($list)) return array();

        $infos = array();
        foreach($list as $file)
        {
            if(!isset($file->type)) continue;

            $info = new stdClass();
            if($file->type == 'blob')
            {
                $path = $file->path;
                $file = $this->files($file->path);

                $info->name     = $file->file_name;
                $info->kind     = 'file';
                $info->account  = $file->committer;
                $info->date     = $file->date;
                $info->size     = $file->size;
                $info->comment  = $file->comment;
                $info->revision = $file->revision;
            }
            else
            {
                $commits = $this->getCommitsByPath($file->path);
                if(empty($commits)) continue;
                $commit = $commits[0];

                $info->name     = $file->name;
                $info->kind     = 'dir';
                $info->revision = $commit->id;
                $info->account  = $commit->committer_name;
                $info->date     = date('Y-m-d H:i:s', strtotime($commit->committed_date));
                $info->size     = 0;
                $info->comment  = $commit->message;
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
     * @param  string    $path
     * @param  string    $ref
     * @access public
     * @return array
     */
    public function files($path, $ref = 'master')
    {
        $path = urlencode($path);
        $api  = "files/$path";
        $param = new stdclass();
        $param->ref = $ref;
        $file = $this->fetch($api, $param);

        $commits = $this->getCommitsByPath($path);
        $file->revision = $file->commit_id;
        $file->size     = $this->formatBytes($file->size);

        if(!empty($commits))
        {
            $commit = $commits[0];
            $file->committer = $commit->committer_name;
            $file->comment   = $commit->message;
            $file->date      = date('Y-m-d H:i:s', strtotime($commit->committed_date));
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
        $api = "tags";
        $list = $this->fetch($api);

        $tags = array();
        foreach($list as $tag) $tags[] = $tag->name;

        return $tags;
    }

    /**
     * Get branch
     *
     * @access public
     * @return array
     */
    public function branch()
    {
        $api  = "branches";
        $list = $this->fetch($api);

        $branches = array();
        foreach($list as $branch) $branches[$branch->name] = $branch->name;
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
        foreach($list as $commit) $commit->diffs = $this->getFilesByCommit($commit->id);

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

        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $path = urlencode($path);
        $api  = "files/$path/blame";
        $param = new stdclass;
        $param->ref = $this->branch;
        $results = $this->fetch($api, $param);

        $blames   = array();
        $revLine  = 0;
        $revision = '';

        $lineNumber = 1;
        foreach($results as $blame)
        {
            $line = array();
            $line['revision']  = $blame->commit->id;
            $line['committer'] = $blame->commit->committer_name;
            $line['time']      = $blame->commit->committer_name;
            $line['line']      = $lineNumber;
            $line['lines']     = count($blame->lines);
            $line['content']   = array_shift($blame->lines);

            $blames[] = $line;

            $lineNumber ++;

            foreach($blame->lines as $line)
            {
                $blames[] = array('line' => $lineNumber, 'content' => $line);
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
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision)
    {
        if(!scm::checkRevision($fromRevision)) return array();
        if(!scm::checkRevision($toRevision))   return array();

        $api     = "compare";
        $params  = array('from' => $fromRevision, 'to' => $toRevision, 'straight' => 1);
        if($toRevision == 'HEAD' and $this->branch) $params['to'] = $this->branch;
        $results = $this->fetch($api, $params);
        foreach($results->diffs as $key => $diff)
        {
            if($path != '' and strpos($diff->new_path, $path) === false) unset($results->diffs[$key]);
        }
        $diffs = $results->diffs;
        $lines = array();
        foreach($diffs as $diff)
        {
            $lines[] = sprintf("diff --git a/%s b/%s", $diff->old_path, $diff->new_path);
            $lines[] = sprintf("index %s ... %s %s ", $fromRevision, $toRevision, $diff->b_mode);
            $lines[] = sprintf("--a/%s", $diff->old_path);
            $lines[] = sprintf("--b/%s", $diff->new_path);
            $diffLines = explode("\n", $diff->diff);
            foreach($diffLines as $diffLine) $lines[] = $diffLine;
        }
        return $lines;
        //return $results->diffs;
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

            foreach($list as $node) if($node->path == $entry) $file = $node;

            $commits = $this->getCommitsByPath($entry);

            if(!empty($commits)) $file->revision = zget($commits[0], 'id', '');
            $info->kind = $file->type == 'tree' ? 'dir' : 'file';
        }

        return $info;
    }

    /**
     * Exec git cmd.
     *
     * @param  string $cmd
     * @access public
     * @todo Exec commads by gitlab api.
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
        $api = "commits";

        if(empty($count)) $count = 100;
        $params = array();
        $params['ref_name'] = $branch;
        $params['per_page'] = $count;
        $params['all']      = 1;

        if($version and $version != 'HEAD')
        {
            $lastCommit = $this->getSingleCommit($version);
            if(!isset($lastCommit->committed_date)) return array('commits' => array(), 'files' => array());

            $params['until'] = $lastCommit->committed_date;
        }

        $list = $this->fetch($api, $params);

        $commits = array();
        foreach($list as $commit)
        {
            $log = new stdclass;
            $log->committer = $commit->committer_name;
            $log->revision  = $commit->id;
            $log->comment   = $commit->message;
            $log->time      = date('Y-m-d H:i:s', strtotime($commit->created_at));

            $commits[$commit->id] = $log;
            $files[$commit->id]   = $this->getFilesByCommit($log->revision);
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
    public function getSingleCommit($sha)
    {
        if(!scm::checkRevision($sha)) return null;
        $api = "commits/$sha";
        return $this->fetch($api);
    }

    /**
     * Get commits by path.
     *
     * @param  string    $path
     * @access public
     * @return array
     */
    public function getCommitsByPath($path, $fromRevision = '', $toRevision = '')
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $api = "commits";

        $param = new stdclass();
        $param->path     = urldecode($path);
        $param->ref_name = $this->branch;

        $fromRevision = ($fromRevision and $fromRevision != 'HEAD') ? $this->getSingleCommit($fromRevision) : '';
        $toRevision   = ($toRevision and $toRevision != 'HEAD') ? $this->getSingleCommit($toRevision) : '';

        $since = '';
        $until = '';
        if($fromRevision and $toRevision)
        {
            $since = min($fromRevision->committed_date, $toRevision->committed_date);
            $until = max($fromRevision->committed_date, $toRevision->committed_date);
        }
        elseif($fromRevision)
        {
            $since = $fromRevision->committed_date;
        }

        if($since) $param->since = $since;
        if($until) $param->until = $until;

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
        $api  = "commits/{$revision}/diff";
        $params = new stdclass;
        $params->page     = 1;
        $params->per_page = 200;

        $allResults = array();
        while($results = $this->fetch($api, $params))
        {
            $params->page ++;
            $allResults = $allResults + $results;
        }

        $files = array();
        foreach($allResults as $row)
        {
            $file = new stdclass();
            $file->revision = $revision;
            $file->path     = '/' . $row->new_path;
            $file->type     = 'file';

            $file->action   = 'M';
            if($row->new_file) $file->action = 'A';
            if($row->renamed_file) $file->action = 'R';
            if($row->deleted_file) $file->action = 'D';
            $files[] = $file;
        }

        return $files;
    }

    /**
     * Repository/tree api.
     *
     * @param  string    $path
     * @param  bool      $recursive
     * @access public
     * @return void
     */
    public function tree($path, $recursive = 1)
    {
        $api = "tree";

        $params = array();
        $params['path']      = ltrim($path, '/');
        $params['ref']       = $this->branch;
        $params['recursive'] = (int) $recursive;
        return $this->fetch($api, $params);
    }

    /**
     * Fetch data from gitlab api.
     *
     * @param  string    $api
     * @access public
     * @return void
     */
    public function fetch($api, $params = array())
    {
        $params = (array) $params;
        $params['private_token'] = $this->token;

        $api = ltrim($api, '/');
        $api = $this->root . $api . '?' . http_build_query($params);

        $response = commonModel::http($api);
        if(!empty(commonModel::$requestErrors))
        {
            commonModel::$requestErrors = array();
            return array();
        }

        return json_decode($response);
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
            $parsedLog = new stdclass();
            $parsedLog->revision  = $commit->id;
            $parsedLog->committer = $commit->committer_name;
            $parsedLog->time      = date('Y-m-d H:i:s', strtotime($commit->committed_date));
            $parsedLog->comment   = $commit->message;
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
}
