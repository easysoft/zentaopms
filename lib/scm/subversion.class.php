<?php
class Subversion
{
    public $client;
    public $root;
    public $account;
    public $password;
    public $ssh;
    public $remote;
    public $encoding;
    public $svnVersion;
    public $repo;

    /**
     * Construct
     *
     * @param  string $client
     * @param  string $root
     * @param  string $account
     * @param  string $password
     * @param  string $encoding
     * @param  object $repo
     * @access public
     * @return void
     */
    public function __construct($client, $root, $account, $password, $encoding = 'UTF-8', $repo = null)
    {
        putenv('LC_CTYPE=en_US.UTF-8');
        $this->root     = str_replace(array('%3A', '%2F', '+'), array(':', '/', ' '), urlencode(rtrim($root, '/')));
        $this->account  = $account;
        $this->password = $password;
        $this->encoding = $encoding;
        $this->repo     = $repo;
        $this->ssh      = (stripos($this->root, 'svn') === 0 or stripos($this->root, 'https') === 0) ? true : false;
        $this->remote   = !(stripos($this->root, 'file') === 0);
        $this->client   = $this->remote ? $client . " --username @account@ --password @password@" : $client;
        if($this->encoding == 'utf-8') $this->encoding = 'gbk';

        $this->svnVersion = $this->getSVNVersion($client);
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

        $resourcePath = $path;
        $path = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'ls', "-r $revision --xml")));
        $list = execCmd($cmd, 'string', $result);
        if($result)
        {
            $path = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'ls', "-r $revision --xml")));
            $list = execCmd($cmd, 'string', $result);
            if($result) $list = '';
        }
        $listObject = simplexml_load_string($list);
        if(!empty($list) and empty($listObject))
        {
            $list = helper::convertEncoding($list, $this->encoding, 'utf-8');
            $listObject = simplexml_load_string($list);
        }
        if(!empty($listObject->list->entry)) $listObject = $listObject->list->entry;
        $infos = array();
        if(empty($listObject)) return $infos;

        foreach($listObject as $list)
        {
            $info = new stdclass();
            $info->size     = 0;
            $info->name     = (string)$list->name;
            $info->path     = $resourcePath . '/' . $list->name;
            $info->kind     = (string)$list['kind'];
            $info->revision = (int)$list->commit['revision'];
            $info->account  = (string)$list->commit->author;
            $info->date     = date('Y-m-d H:i:s', strtotime($list->commit->date));
            $info->comment  = '';

            if($info->kind == 'file') $info->size = (int)$list->size > 1024 ? round((int)$list->size / 1024, 2) . "KB" : (int)$list->size . 'Bytes';
            $infos[] = $info;
        }

        /* Sort by kind */
        foreach($infos as $key => $info) $kind[$key] = $info->kind;
        if($infos) array_multisort($kind, SORT_ASC, $infos);

        return $infos;
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
        if(!scm::checkRevision($revision)) return array();

        $infos = $this->ls($path, $revision);
        $dirs  = array();
        foreach($infos as $info)
        {
            if($onlyDir and $info->kind != 'dir') continue;
            $dirs[$info->date][$info->name] = $info->name;
        }

        ksort($dirs);
        $tags   = array();
        $trimed = trim($path, '/');
        $prefix = empty($trimed) ? '/' : '/' . $trimed . '/';
        foreach($dirs as $dirNames)
        {
            ksort($dirNames);
            foreach($dirNames as $dirName)
            {
                $dirPath = $prefix . $dirName;
                $tags[$dirPath] = $dirName;
            }
        }

        return $tags;
    }

    /**
     * Get branch.
     *
     * @access public
     * @return array
     */
    public function branch()
    {
        return array();
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
        $resourcePath = $path;
        $path = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'log', "--limit $count --xml")));
        $comments = execCmd($cmd, 'string', $result);
        if($result)
        {
            $path = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'log', "--limit $count --xml")));
            $comments = execCmd($cmd, 'string', $result);
            if($result) $comments = '';
        }

        $parsedComments = simplexml_load_string($comments);
        if(!empty($comments) and empty($parsedComments))
        {
            $comments = helper::convertEncoding($comments, $this->encoding, 'utf-8');
            $parsedComments = simplexml_load_string($comments);
        }
        $logs = array();
        foreach($parsedComments->logentry as $entry)
        {
            $log = new stdclass();
            $log->committer = (string)$entry->author;
            $log->revision  = (int)$entry['revision'];
            $log->comment   = trim((string)$entry->msg);
            $log->time      = date('Y-m-d H:i:s', strtotime($entry->date));
            $log->change    = array();
            $logs[]         = $log;
            unset($log);
        }

        /* Sort by kind */
        foreach($logs as $key => $log) $revision[$key] = $log->revision;
        if($logs) array_multisort($revision, SORT_DESC, $logs);

        return $logs;
    }

    /**
     * Get log.
     *
     * @param  string $path
     * @param  int    $fromRevision
     * @param  string $toRevision
     * @param  int    $count
     * @param  bool   $quiet
     * @access public
     * @return array
     */
    public function log($path, $fromRevision = 0, $toRevision = 'HEAD', $count = 0, $quiet = false)
    {
        if(!scm::checkRevision($fromRevision)) return array();
        if(!scm::checkRevision($toRevision))   return array();

        $resourcePath = $path;
        $count = $count == 0 ? '' : "--limit $count";
        $param = $quiet ? '-q' : '-v';
        $path  = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $cmd   = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'log', "$count $param -r $fromRevision:$toRevision --xml")));
        $comments = execCmd($cmd, 'string', $result);
        if($result)
        {
            $path = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'log', "$count $param -r $fromRevision:$toRevision --xml")));
            $comments = execCmd($cmd, 'string', $result);
            if($result) $comments = '';
        }

        $parsedComments = simplexml_load_string($comments);
        if(!empty($comments) and empty($parsedComments))
        {
            $comments = helper::convertEncoding($comments, $this->encoding, 'utf-8');
            $parsedComments = simplexml_load_string($comments);
        }
        $logs     = array();
        $revision = array();
        if(empty($parsedComments->logentry)) return $logs;

        foreach($parsedComments->logentry as $entry)
        {
            $log = new stdclass();
            $log->committer = (string)$entry->author;
            $log->revision  = (int)$entry['revision'];
            $log->comment   = trim((string)$entry->msg);
            $log->time      = date('Y-m-d H:i:s', strtotime($entry->date));
            $log->change    = array();
            if(!empty($entry->paths))
            {
                foreach($entry->paths->path as $path)
                {
                    $pathInfo = array();
                    foreach($path->attributes() as $attr => $value) $pathInfo[$attr] = (string)$value;
                    $log->change[(string)$path] = $pathInfo;
                }
            }
            if(in_array($log->revision, $revision)) continue;

            $logs[]     = $log;
            $revision[] = $log->revision;
            unset($log);
        }

        /* Sort by kind */
        if($logs) array_multisort($revision, SORT_DESC, $logs);
        return $logs;
    }

    /**
     * Blame file.
     *
     * @param  string $path
     * @param  int    $revision
     * @param  bool   $showComment
     * @access public
     * @return array
     */
    public function blame($path, $revision, $showComment = true)
    {
        if(!scm::checkRevision($revision)) return array();

        $resourcePath = $path;
        $path   = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $file   = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'cat', "-r $revision")));
        $blame  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'blame', "-r $revision --xml")));
        $output = execCmd($blame, 'string', $result);
        if($result)
        {
            $path   = '"' . $this->root . '/' . $resourcePath . '"';
            $file   = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'cat', "-r $revision")));
            $blame  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'blame', "-r $revision --xml")));
            $output = execCmd($blame, 'string', $result);

            if($result) return array();
        }

        $content = execCmd($file, 'array');

        $parsedResult = simplexml_load_string($output);
        if(!empty($output) and empty($parsedResult))
        {
            $output = helper::convertEncoding($output, $this->encoding, 'utf-8');
            $parsedResult = simplexml_load_string($output);
        }

        $blames   = array();
        $revLine  = 0;
        $revision = '';
        if($parsedResult->target->entry)
        {
            foreach($parsedResult->target->entry as $line)
            {
                if($line->commit['revision'] != $revision)
                {
                    $blame = array();
                    $blame['revision']  = (int)$line->commit['revision'];
                    $blame['committer'] = (string)$line->commit->author;
                    $blame['time']      = date('Y-m-d H:i:s', strtotime($line->commit->date));
                    $blame['line']      = (int)$line['line-number'];
                    $blame['lines']     = 1;
                    $blame['content']   = isset($content[$blame['line'] - 1]) ? $content[$blame['line'] - 1] : '';
                    $blame['message']   = '';

                    if($showComment)
                    {
                        $log = $this->log('', $blame['revision'], 'HEAD', 1);
                        $blame['message'] = $log[0]->comment;
                    }

                    $revision         = $blame['revision'];
                    $revLine          = $blame['line'];
                    $blames[$revLine] = $blame;
                }
                else
                {
                    $blame            = array();
                    $blame['line']    = (int)$line['line-number'];
                    $blame['content'] = zget($content, $blame['line'] - 1, '');

                    $blames[$blame['line']] = $blame;
                    $blames[$revLine]['lines'] ++;
                }
            }
        }
        return $blames;
    }

    /**
     * Diff file.
     *
     * @param  string $path
     * @param  int    $fromRevision
     * @param  int    $toRevision
     * @access public
     * @return array
     */
    public function diff($path, $fromRevision, $toRevision)
    {
        if(!scm::checkRevision($fromRevision)) return array();
        if(!scm::checkRevision($toRevision))   return array();

        $resourcePath = $path;
        if($fromRevision == '^') $fromRevision = $toRevision - 1;
        $path = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'diff', "-r $fromRevision:$toRevision")));
        $lines = execCmd($cmd, 'array', $result);
        if($result)
        {
            $path  = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd   = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'diff', "-r $fromRevision:$toRevision")));
            $lines = execCmd($cmd, 'array', $result);
        }
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

        $resourcePath = $entry;
        $entry = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($entry)) . '"';
        $cmd   = $this->replaceAuth(escapeCmd($this->buildCMD($entry, 'cat', "-r $revision")));
        $content = execCmd($cmd, 'string', $result);
        if($result)
        {
            $entry = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd   = $this->replaceAuth(escapeCmd($this->buildCMD($entry, 'cat', "-r $revision")));
            $content = execCmd($cmd, 'string', $result);
        }
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

        $resourcePath = $entry;
        $entry   = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($entry)) . '"';
        $svnInfo = $this->replaceAuth(escapeCmd($this->buildCMD($entry, 'info', "-r $revision --xml")));

        $svninfo = execCmd($svnInfo, 'string', $result);
        if($result)
        {
            $entry   = '"' . $this->root . '/' . $resourcePath . '"';
            $svnInfo = $this->replaceAuth(escapeCmd($this->buildCMD($entry, 'info', "-r $revision --xml")));

            $svninfo = execCmd($svnInfo, 'string', $result);
            if($result) $svninfo = '';
        }

        $parsedSvnInfo = simplexml_load_string($svninfo);
        if(!empty($svninfo) and empty($parsedSvnInfo))
        {
            $svninfo = helper::convertEncoding($svninfo, $this->encoding, 'utf-8');
            $parsedSvnInfo = simplexml_load_string($svninfo);
        }
        $info = new stdclass();
        $info->kind      = empty($parsedSvnInfo->entry['kind'])             ? '' : (string)$parsedSvnInfo->entry['kind'];
        $info->path      = empty($parsedSvnInfo->entry['path'])             ? '' : (string)$parsedSvnInfo->entry['path'];
        $info->revision  = empty($parsedSvnInfo->entry['revision'])         ? '' : (int)$parsedSvnInfo->entry['revision'];
        $info->cRevision = empty($parsedSvnInfo->entry->commit['revision']) ? '' : (int)$parsedSvnInfo->entry->commit['revision'];
        $info->root      = empty($parsedSvnInfo->entry->repository->root)   ? '' : (string)$parsedSvnInfo->entry->repository->root;
        return $info;
    }

    /**
     * Exec svn cmd.
     *
     * @param  string $cmd
     * @access public
     * @return array
     */
    public function exec($cmd)
    {
        $cmd = $this->replaceAuth(escapeCmd($this->buildCMD('', $cmd, '')));
        return execCmd($cmd, 'array');
    }

    /**
     * Parse diff.
     *
     * @param  array  $lines
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

        for($i = 0; $i < $num; $i ++)
        {
            $diffFile = new stdclass();
            if(strpos($lines[$i], "Index: ") === 0)
            {
                $fileName = str_replace('Index: ', '', $lines[$i]);
                $diffFile->fileName = $fileName;
                for($i++; $i < $num; $i ++)
                {
                    $diff = new stdclass();
                    if(strpos($lines[$i], '+++', 0) !== false)    continue;
                    if(strpos($lines[$i], '---', 0) !== false)    continue;
                    if(strpos($lines[$i], '======', 0) !== false) continue;
                    if(preg_match('/^@@ -(\\d+)(,(\\d+))?\\s+\\+(\\d+)(,(\\d+))?\\s+@@\\s*($)/A', $lines[$i]))
                    {
                        $startLines = trim(str_replace(array('@', '+', '-'), '', $lines[$i]));
                        list($oldStartLine, $newStartLine) = explode(' ', $startLines);
                        list($diff->oldStartLine) = explode(',', $oldStartLine);
                        list($diff->newStartLine) = explode(',', $newStartLine);
                        $oldCurrentLine = $diff->oldStartLine;
                        $newCurrentLine = $diff->newStartLine;
                        $newLines = array();
                        for($i++; $i < $num; $i ++)
                        {
                            if(preg_match('/^@@ -(\\d+)(,(\\d+))?\\s+\\+(\\d+)(,(\\d+))?\\s+@@\\s*($)/A', $lines[$i]))
                            {
                                $i --;
                                break;
                            }
                            if(strpos($lines[$i], "Index: ") === 0) break;

                            $line = $lines[$i];
                            if(strpos($line, '\ No newline at end of file') === 0)continue;
                            $sign = empty($line) ? '' : $line[0];
                            $type = $sign != '-' ? $sign == '+' ? 'new' : 'all' : 'old';
                            if($sign == '-' || $sign == '+') $line = substr_replace($line, ' ', 1, 0);

                            $newLine = new stdclass();
                            $newLine->type  = $type;
                            $newLine->oldlc = $type != 'new' ? $oldCurrentLine : '';
                            $newLine->newlc = $type != 'old' ? $newCurrentLine : '';
                            $newLine->line  = $line;

                            if($type != 'new') $oldCurrentLine++;
                            if($type != 'old') $newCurrentLine++;

                            $newLines[] = $newLine;
                        }

                        $diff->lines = $newLines;
                        $diffFile->contents[] = $diff;
                    }

                    if(isset($lines[$i]) and strpos($lines[$i], "Index: ") === 0)
                    {
                        $i --;
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
     * @param  int    $lastVersion
     * @access public
     * @return int
     */
    public function getCommitCount($commits = 0, $lastVersion = 0)
    {
        if(!scm::checkRevision($lastVersion)) return false;

        if(empty($commits))     $commits     = 0;
        if(empty($lastVersion)) $lastVersion = 0;
        $lastRevision = $this->getLatestRevision();

        $count = 10000;
        $from  = $lastVersion;
        while(true)
        {
            $logs     = $this->log('', $from, $lastRevision, empty($from) ? $count : $count + 1, $quiet = true);
            if(empty($logs)) break;

            $num      = empty($from) ? count($logs) : count($logs) - 1;
            $commits += $num;

            $from = reset($logs);
            $from = $from->revision;
            if($from == $lastRevision) break;
        }
        return $commits;
    }

    /**
     * Get first revision.
     *
     * @access public
     * @return int
     */
    public function getFirstRevision()
    {
        $logs     = $this->log('', 0, 'HEAD', 1, $quiet = true);
        if(empty($logs)) return 0;
        $firstLog = end($logs);
        return $firstLog->revision;
    }

    /**
     * Get latest revision.
     *
     * @access public
     * @return int
     */
    public function getLatestRevision()
    {
        $info = $this->info('');
        return $info->cRevision;
    }

    /**
     * Get commits.
     *
     * @param  string $version
     * @param  int    $count
     * @access public
     * @return array
     */
    public function getCommits($version = '', $count = 0)
    {
        if(!scm::checkRevision($version)) return array();

        $count = $count == 0 ? '' : "--limit $count";
        $path  = '"' . $this->root . '"';
        if(stripos($this->root, 'https') === 0 or stripos($this->root, 'svn') === 0)
        {
            $comments = str_replace("\\", "/", "$this->client log $count -v -r $version:0 --non-interactive --trust-server-cert-failures=cn-mismatch --trust-server-cert --no-auth-cache --xml $path");
            if($this->svnVersion and version_compare($this->svnVersion, '1.9', '<')) $comments = str_replace("\\", "/", "$this->client log $count -v -r $version:0 --non-interactive --trust-server-cert --no-auth-cache --xml $path");
        }
        else
        {
            $comments = str_replace("\\", "/", "$this->client log $count -v -r $version:0 --no-auth-cache --xml $path");
        }
        $comments = $this->replaceAuth(escapeCmd($comments));
        $comments = execCmd($comments, 'string', $result);
        if($result) $comments = '';

        $parsedComments = simplexml_load_string($comments);
        if(!empty($comments) and empty($parsedComments))
        {
            $comments = helper::convertEncoding($comments, $this->encoding, 'utf-8');
            $parsedComments = simplexml_load_string($comments);
        }
        $logs = array();
        foreach($parsedComments->logentry as $entry)
        {
            $parsedLog            = new stdClass();
            $parsedLog->committer = (string)$entry->author;
            $parsedLog->revision  = (int)$entry['revision'];
            $parsedLog->comment   = trim((string)$entry->msg);
            $parsedLog->time      = date('Y-m-d H:i:s', strtotime($entry->date));
            $logs['commits'][$parsedLog->revision] = $parsedLog;
            $logs['files'][$parsedLog->revision]   = array();
            if(!empty($entry->paths))
            {
                foreach($entry->paths->path as $file)
                {
                    $parsedFile = new stdclass();
                    $parsedFile->revision = $parsedLog->revision;
                    $parsedFile->path     = (string)$file;
                    $parsedFile->type     = (string)$file['kind'];
                    $parsedFile->action   = (string)$file['action'];
                    $logs['files'][$parsedLog->revision][]  = $parsedFile;
                }
            }
        }
        return $logs;
    }

    /**
     * Replace svn auth.
     *
     * @param  string $cmd
     * @access public
     * @return string
     */
    public function replaceAuth($cmd)
    {
        return str_replace(array('@account@', '@password@'), array($this->account, $this->password), $cmd);
    }

    /**
     * Build command.
     *
     * @param  string $path
     * @param  string $action
     * @param  string $param
     * @access public
     * @return string
     */
    public function buildCMD($path, $action, $param)
    {
        if($this->ssh)
        {
            $cmd = str_replace("\\", "/", "$this->client $action $param --non-interactive --trust-server-cert-failures=cn-mismatch --trust-server-cert --no-auth-cache $path");
            if($this->svnVersion and version_compare($this->svnVersion, '1.9', '<')) $cmd = str_replace("\\", "/", "$this->client $action $param --non-interactive --trust-server-cert --no-auth-cache $path");
        }
        else
        {
            $cmd = str_replace("\\", "/", "$this->client $action $param --no-auth-cache $path");
        }

        return $cmd;
    }

    /**
     * Get SVN version.
     *
     * @param  string $client
     * @access public
     * @return string
     */
    public function getSVNVersion($client)
    {
        $versionCommand = "$client --version --quiet 2>&1";
        exec($versionCommand, $versionOutput, $versionResult);
        if($versionResult) return false;

        return end($versionOutput);
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
        global $app, $config;

        /* Get repo name. */
        $pathList = explode('/', trim($this->root, '/'));
        $repoDir  = $savePath . DS . end($pathList);
        execCmd($this->replaceAuth(escapeCmd($this->buildCMD("$this->root $repoDir", 'export', ''))));

        $fileName = $savePath . DS . "{$this->repo->name}.zip";
        $app->loadClass('pclzip', true);
        $zip = new pclzip($fileName);
        $zip->create($repoDir, PCLZIP_OPT_REMOVE_PATH, $repoDir);

        $zfile = $app->loadClass('zfile');
        $zfile->removeDir($repoDir);
        return $config->webRoot . $app->getAppName() . 'data' . DS . 'repo' . DS . $this->repo->name . '.zip';
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

        $resourcePath = $path;
        $path         = '"' . $this->root . '/' . str_replace(array('%2F', '+'), array('/', ' '), urlencode($path)) . '"';
        $cmd          = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'ls', "-r $revision --xml")));
        $list         = execCmd($cmd, 'string', $result);
        if($result)
        {
            $path = '"' . $this->root . '/' . $resourcePath . '"';
            $cmd  = $this->replaceAuth(escapeCmd($this->buildCMD($path, 'ls', "-r $revision --xml")));
            $list = execCmd($cmd, 'string', $result);
            if($result) $list = '';
        }
        $listObject = simplexml_load_string($list);
        if(!empty($list) and empty($listObject))
        {
            $list = helper::convertEncoding($list, $this->encoding, 'utf-8');
            $listObject = simplexml_load_string($list);
        }
        if(!empty($listObject->list->entry)) $listObject = $listObject->list->entry;
        $infos = array();
        if(empty($listObject)) return $infos;

        foreach($listObject as $list)
        {
            $kind     = (string)$list['kind'];
            $pathName = ltrim($path . DIRECTORY_SEPARATOR . (string)$list->name, DIRECTORY_SEPARATOR);
            if($kind == 'dir')
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
}
