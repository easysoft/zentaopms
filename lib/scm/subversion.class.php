<?php
require_once dirname(__FILE__) . '/gitfox.class.php';

class subversionRepo
{
    public $client;
    public $account;
    public $encoding;
    public $root;
    public $password;
    public $repo;
    public $apiRoot;
    public $token;

    /**
     * Construct
     *
     * @param  string $client   兼容旧签名,不再使用
     * @param  string $root     gitfox apiPath,如 http://gitfox:3000/api/v2/repos/<id>/
     * @param  string $account  兼容旧签名,凭证由 gitfox 从 repo.Connector 取
     * @param  string $password gitfox token
     * @param  string $encoding 兼容旧签名,gitfox 统一 utf-8
     * @param  object $repo
     * @access public
     * @return void
     */
    public function __construct($client, $root, $account, $password, $encoding = 'UTF-8', $repo = null)
    {
        $this->client   = $client;
        $this->root     = rtrim($root, '/');
        $this->account  = $account;
        $this->password = $password;
        $this->encoding = $encoding;
        $this->repo     = $repo;

        $this->apiRoot = rtrim($root, '/');
        $this->token   = $password;
    }


    /**
     * 调 gitfox 统一 SVN 执行接口,返回 svn 原生 stdout 字符串。
     * Call gitfox /svn/exec, return raw svn stdout content.
     *
     * @param  string $func ls|log|blame|cat|info|diff
     * @param  array  $args 对应 *XxxParams 字段(Revision/XML/Verbose/...)
     * @access public
     * @return string|false 失败返 false
     */
    public function fetchContent($func, $args = array())
    {
        /* SVN /svn/exec 发 JSON body,svn stdout 走原文回,故 Accept 用通配。 */
        $header = gitfoxRepo::buildAuthHeader($this->token, '', 'application/json', '*/*');

        $url     = $this->apiRoot . '/svn/exec';
        $payload = json_encode(array(
            'func' => $func,
            'args' => empty($args) ? new stdclass() : $args
        ));

        $response = commonModel::http($url, $payload, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header, 'data');
        if(gitfoxRepo::clearHttpErrors()) return false;

        /* gitfox 错误时回 JSON {code:fail,message:"..."}; 成功时回原 svn stdout */
        if(!empty($response) && $response[0] === '{')
        {
            $maybe = json_decode($response);
            if(is_object($maybe) && isset($maybe->code) && $maybe->code !== 'success') return false;
        }
        return $response;
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
        $args = array('Revision' => (string)$revision, 'XML' => true);
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $list = $this->fetchContent('ls', $args);
        if($list === false) $list = '';

        $listObject = simplexml_load_string($list);
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
            /* revision 强转字符串,zen 层会做 substr,SVN int 与 git hash 共用 */
            $info->revision = (string)$list->commit['revision'];
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
     * 签名兼容 scm::tags 透传的 6 参数; svn 只用 path/revision/onlyDir。
     *
     * @param  string $path
     * @param  string $revision
     * @param  bool   $onlyDir
     * @access public
     * @return array
     */
    public function tags($path = '', $revision = 'HEAD', $onlyDir = true, string $orderBy = '', int $limit = 0, int $pageID = 1)
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
     * 签名兼容 scm::branch 透传的 6 参数; SVN 无原生分支语义,直返空数组。
     *
     * @access public
     * @return array
     */
    public function branch(string $showDetail = '', string $orderBy = '', int $limit = 0, int $pageID = 1, string $label = '', string $showArchived = 'active')
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
        $args = array('Verbose' => true, 'XML' => true, 'Limit' => (int)$count);
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $comments = $this->fetchContent('log', $args);
        if($comments === false) $comments = '';

        $parsedComments = simplexml_load_string($comments);
        $logs = array();
        if(empty($parsedComments->logentry)) return $logs;

        foreach($parsedComments->logentry as $entry)
        {
            $log = new stdclass();
            $log->committer = (string)$entry->author;
            $log->revision  = (string)$entry['revision'];
            $log->comment   = trim((string)$entry->msg);
            $log->time      = date('Y-m-d H:i:s', strtotime($entry->date));
            $log->change    = array();
            $logs[]         = $log;
            unset($log);
        }

        /* Sort by kind */
        foreach($logs as $key => $log) $revision[$key] = (int)$log->revision;
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
        $args = array(
            'Revision' => "{$fromRevision}:{$toRevision}",
            'XML'      => true,
            'Verbose'  => $quiet ? false : true
        );
        if($quiet)            $args['Quiet'] = true;
        if(!empty($count))    $args['Limit'] = (int)$count;
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $comments = $this->fetchContent('log', $args);
        if($comments === false) $comments = '';

        $parsedComments = simplexml_load_string($comments);
        $logs     = array();
        $revision = array();
        if(empty($parsedComments->logentry)) return $logs;

        foreach($parsedComments->logentry as $entry)
        {
            $log = new stdclass();
            $log->committer = (string)$entry->author;
            $log->revision  = (string)$entry['revision'];
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
        $blameArgs = array('Revision' => (string)$revision, 'XML' => true);
        $catArgs   = array('Revision' => (string)$revision);
        if($resourcePath !== '' && $resourcePath !== '/')
        {
            $sub = ltrim($resourcePath, '/');
            $blameArgs['Subpath'] = $sub;
            $catArgs['Subpath']   = $sub;
        }

        $output = $this->fetchContent('blame', $blameArgs);
        if($output === false) return array();

        $fileContent = $this->fetchContent('cat', $catArgs);
        if($fileContent === false) $fileContent = '';
        $content = explode("\n", $fileContent);

        $parsedResult = simplexml_load_string($output);

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
                    $blame['revision']  = (string)$line->commit['revision'];
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
        if(empty($toRevision)) $fromRevision = '';
        if($fromRevision == '^') $fromRevision = $toRevision - 1;

        $args = array('Revision' => "{$fromRevision}:{$toRevision}");
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $diffs = $this->fetchContent('diff', $args);
        if($diffs === false) return array();

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

        $resourcePath = $entry;
        $args = array('Revision' => (string)$revision);
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $content = $this->fetchContent('cat', $args);
        if($content === false) return '';
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
        $args = array('Revision' => (string)$revision, 'XML' => true);
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $svninfo = $this->fetchContent('info', $args);
        if($svninfo === false) $svninfo = '';

        $parsedSvnInfo = simplexml_load_string($svninfo);
        $info = new stdclass();
        $info->kind      = empty($parsedSvnInfo->entry['kind'])             ? '' : (string)$parsedSvnInfo->entry['kind'];
        $info->path      = empty($parsedSvnInfo->entry['path'])             ? '' : (string)$parsedSvnInfo->entry['path'];
        $info->revision  = empty($parsedSvnInfo->entry['revision'])         ? '' : (int)$parsedSvnInfo->entry['revision'];
        $info->cRevision = empty($parsedSvnInfo->entry->commit['revision']) ? '' : (int)$parsedSvnInfo->entry->commit['revision'];
        $info->root      = empty($parsedSvnInfo->entry->repository->root)   ? '' : (string)$parsedSvnInfo->entry->repository->root;
        return $info;
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
                /* gitfox 后端跑 svn diff 时"Index:"是 wc 绝对路径,形如
                 * /.../svn/repos/<uid>-wc/trunk/src/foo.py
                 * 此处剥"-wc/"前缀,只留仓库相对路径 trunk/src/foo.py。 */
                $wcPos = strpos($fileName, '-wc/');
                if($wcPos !== false) $fileName = substr($fileName, $wcPos + 4);
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

        $args = array(
            'Revision' => "{$version}:HEAD",
            'XML'      => true,
            'Verbose'  => true
        );
        if(!empty($count)) $args['Limit'] = (int)$count;

        $comments = $this->fetchContent('log', $args);
        if($comments === false) $comments = '';

        $parsedComments = simplexml_load_string($comments);
        $logs = array();
        foreach($parsedComments->logentry as $entry)
        {
            $parsedLog            = new stdClass();
            $parsedLog->committer = (string)$entry->author;
            $parsedLog->revision  = (string)$entry['revision'];
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
                    $parsedFile->action   = substr((string)$file['action'], 0, 1);
                    if(isset($file['copyfrom-path'])) $parsedFile->copyfromPath = (string)$file['copyfrom-path'];
                    if(isset($file['copyfrom-rev']))  $parsedFile->copyfromRev = (string)$file['copyfrom-rev'];

                    $logs['files'][$parsedLog->revision][]  = $parsedFile;
                }
            }
        }
        return $logs;
    }

    /**
     * Exec svn cmd. 已废弃,保留空壳兼容 scm::exec 转发,不再支持任意 svn 子命令。
     *
     * @param  string $cmd
     * @access public
     * @return array
     */
    public function exec($cmd)
    {
        return array();
    }

    /**
     * Get download url. 暂未实现,gitfox 的 svn export 接口待下一 PR 接入,保留空壳兼容 scm::getDownloadUrl 转发。
     *
     * @param  string $branch
     * @param  string $savePath
     * @param  string $ext
     * @access public
     * @return string
     */
    public function getDownloadUrl($branch = '', $savePath = '', $ext = 'zip')
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

        $resourcePath = $path;
        $args = array('Revision' => (string)$revision, 'XML' => true);
        if($resourcePath !== '' && $resourcePath !== '/') $args['Subpath'] = ltrim($resourcePath, '/');

        $list = $this->fetchContent('ls', $args);
        if($list === false) $list = '';

        $listObject = simplexml_load_string($list);
        if(!empty($listObject->list->entry)) $listObject = $listObject->list->entry;
        $infos = array();
        if(empty($listObject)) return $infos;

        foreach($listObject as $list)
        {
            $kind     = (string)$list['kind'];
            $pathName = ltrim($resourcePath . DIRECTORY_SEPARATOR . (string)$list->name, DIRECTORY_SEPARATOR);
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

    /**
     * 取 clone URL。SVN 用 repo->path / svnURL 即可,无 SSH。
     * @access public
     * @return object
     */
    public function getCloneUrl()
    {
        $url = new stdclass();
        $url->http = isset($this->repo->path) ? (string)$this->repo->path : '';
        $url->ssh  = '';
        return $url;
    }

    /**
     * 按 commit 取文件列表。SVN 用 log -v 拼回——本 PR 暂返空数组,后续接入 svnLogByRev 解析。
     *
     * @param  string $revision
     * @access public
     * @return array
     */
    public function getFilesByCommit($revision)
    {
        return array();
    }

    /**
     * 按路径取 commits。SVN 复用 log 接口转换字段形态适配 gitfox commits 数据结构。
     *
     * @param  string $path
     * @param  string $fromRevision
     * @param  string $toRevision
     * @param  int    $perPage
     * @param  int    $page
     * @param  string $beginDate
     * @param  string $endDate
     * @param  string $committer
     * @access public
     * @return array
     */
    public function getCommitsByPath($path, $fromRevision = '', $toRevision = '', $perPage = 0, $page = 1, $beginDate = '', $endDate = '', $committer = '')
    {
        /* SVN revision 必连续,故按 revision 区间真分页:
         *   总条数 = HEAD revision (即最新 revision 号本身)
         *   第 K 页 (perPage=N) = revision 区间 [HEAD-(K-1)*N : max(1, HEAD-K*N+1)] 取最近 N 条
         * 命中筛选(committer/begin/end/单 revision)时无法按 revision 区间精确分页,回退到全量拉+本地切片。
         */
        $perPage = (int)$perPage;
        $page    = max(1, (int)$page);

        $hasFilter = ($committer !== '' || $beginDate !== '' || $endDate !== '' || ($fromRevision !== '' && $fromRevision == $toRevision));
        if($hasFilter || $perPage <= 0)
        {
            return $this->getCommitsByPathFiltered($path, $fromRevision, $toRevision, $perPage, $page, $beginDate, $endDate, $committer);
        }

        /* 取 HEAD revision 作真实总数 */
        $head = (int)$this->getLatestRevision();
        if($head <= 0) return array();

        self::$lastCommitsTotal = $head;

        /* 按 page 推 revision 区间。SVN log -r FROM:TO 是闭区间,FROM>TO 时倒序输出。 */
        $top    = $head - ($page - 1) * $perPage;
        if($top <= 0) return array();
        $bottom = max(1, $top - $perPage + 1);

        $logs = $this->log($path, $top, $bottom, $perPage, false);
        if(empty($logs)) return array();

        return $this->wrapSvnLogsAsCommits($logs);
    }

    /**
     * 带筛选条件时的兜底:拉全量后本地过滤+切片。
     *
     * @access protected
     * @return array
     */
    protected function getCommitsByPathFiltered($path, $fromRevision, $toRevision, $perPage, $page, $beginDate, $endDate, $committer)
    {
        $from = $fromRevision === '' ? 1 : $fromRevision;
        $to   = $toRevision === ''   ? 'HEAD' : $toRevision;
        $logs = $this->log($path, $from, $to, 0, false);
        if(empty($logs)) return array();

        $filtered = array();
        foreach($logs as $log)
        {
            if($committer && $log->committer != $committer) continue;
            if($beginDate && strtotime($log->time) < strtotime($beginDate)) continue;
            if($endDate   && strtotime($log->time) > strtotime($endDate))   continue;
            $filtered[] = $log;
        }

        self::$lastCommitsTotal = count($filtered);

        if($perPage > 0)
        {
            $offset   = ($page - 1) * $perPage;
            $filtered = array_slice($filtered, $offset, $perPage);
        }

        return $this->wrapSvnLogsAsCommits($filtered);
    }

    /**
     * 把 SVN log 数组适配成 gitfox commits 数据结构。
     *
     * @access protected
     * @return array
     */
    protected function wrapSvnLogsAsCommits($logs)
    {
        $results = array();
        foreach($logs as $log)
        {
            $rev = (string)$log->revision;
            $item = new stdclass();
            $item->id              = $rev;
            $item->sha             = $rev;
            $item->revision        = $rev;
            $item->title           = $log->comment;
            $item->message         = $log->comment;
            $item->committed_date  = $log->time;
            $item->committer_name  = $log->committer;
            $item->author          = new stdclass();
            $item->author->when    = $log->time;
            $item->author->identity = new stdclass();
            $item->author->identity->name = $log->committer;
            $results[] = $item;
        }
        return $results;
    }

    /** 最近一次 getCommitsByPath 过滤后的总条数;供上层回写真实 recTotal。 */
    public static $lastCommitsTotal = 0;

    /**
     * 获取 MR commits。SVN 无 MR 语义,返空数组兼容 scm 转发。
     *
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function getMRCommits($sourceBranch, $targetBranch)
    {
        return array();
    }

    /**
     * 创建 MR。SVN 无 MR 语义,返 null。
     *
     * @param  object $MR
     * @param  string $openID
     * @access public
     * @return null
     */
    public function createMR($MR, $openID = '', $assignee = '')
    {
        return null;
    }
}
