<?php
declare(strict_types=1);
/**
 * The model file of svn module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     svn
 * @link        https://www.zentao.net
 */
class svnModel extends model
{
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        putenv('LC_ALL=C');
    }
    /**
     * The svn binary client.
     *
     * @var int
     * @access public
     */
    public $client;

    /**
     * Repos.
     *
     * @var array
     * @access public
     */
    public $repos = array();

    /**
     * The root path of a repo
     *
     * @var string
     * @access public
     */
    public $repoRoot = '';

    /**
     * Users
     *
     * @var array
     * @access public
     */
    public $users = array();

    /**
     * 执行定时任务同步提交信息。
     * Sync commit info by cron.
     *
     * @access public
     * @return bool
     */
    public function run(): bool
    {
        $this->setRepos();
        if(empty($this->repos)) return false;

        /* Get commit triggerType jobs by repoIdList. */
        $commentGroup = $this->loadModel('job')->getTriggerGroup('commit', array_keys($this->repos));

        /* Get tag triggerType jobs by repoIdList. */
        $tagGroup = $this->job->getTriggerGroup('tag', array_keys($this->repos));

        $_COOKIE['repoBranch'] = '';
        $this->loadModel('compile');
        foreach($this->repos as $repoID => $repo)
        {
            $this->updateCommit($repo, $commentGroup, true);

            /* Create compile by tag. */
            $jobs = zget($tagGroup, $repoID, array());
            foreach($jobs as $job)
            {
                $dirs    = $this->getRepoTags($repo, $job->svnDir);
                $isNew   = empty($job->lastTag) ? true : false;
                $lastTag = '';
                foreach($dirs as $dir)
                {
                    if(!$isNew && $dir == $job->lastTag)
                    {
                        $isNew = true;
                        continue;
                    }
                    if(!$isNew) continue;

                    $lastTag = $dir;
                    $tag     = rtrim($repo->path , '/') . '/' . trim($job->svnDir, '/') . '/' . $lastTag;
                    $this->compile->createByJob($job->id, $tag, 'tag');
                }
                if($lastTag) $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 保存提交信息。
     * Save commits.
     *
     * @param  object  $repo
     * @param  array   $logs
     * @param  object  $lastInDB
     * @param  array   $commentGroup
     * @param  bool    $printLog
     * @access public
     * @return bool
     */
    public function saveCommits(object $repo, array $logs, object $lastInDB, array $commentGroup, bool $printLog): bool
    {
        $this->loadModel('repo');
        $version = (int)$lastInDB->commit + 1;
        foreach($logs as $log)
        {
            if($printLog) $this->printLog("parsing log {$log->revision}");
            if($printLog) $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");

            $objects = $this->repo->parseComment($log->msg);
            if($objects)
            {
                if($printLog) $this->printLog('extract' .
                    ' story:' . join(' ', $objects['stories']) .
                    ' task:' . join(' ', $objects['tasks']) .
                    ' bug:'  . join(',', $objects['bugs']));
                $this->repo->saveAction2PMS($objects, $log, $this->repoRoot, $repo->encoding, 'svn');
            }
            else
            {
                if($printLog) $this->printLog('no objects found' . "\n");
            }

            /* Create compile by comment. */
            $jobs = zget($commentGroup, $repo->id, array());
            foreach($jobs as $job)
            {
                foreach(explode(',', $job->comment) as $comment)
                {
                    if(strpos($log->msg, $comment) !== false)
                    {
                        $this->loadModel('compile')->createByJob($job->id);
                        continue 2;
                    }
                }
            }

            $version = $this->repo->saveOneCommit($repo->id, $log, $version);
        }

        $this->repo->updateCommitCount($repo->id, $lastInDB->commit + count($logs));
        $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repo->id)->exec();

        return !dao::isError();
    }

    /**
     * 更新提交信息。
     * Update commit.
     *
     * @param  object  $repo
     * @param  array   $commentGroup
     * @param  bool    $printLog
     * @access public
     * @return void
     */
    public function updateCommit(object $repo, array $commentGroup, bool $printLog = true)
    {
        /* Load mudule and print log. */
        if($printLog) $this->printLog("begin repo {$repo->name}");
        $this->setRepo($repo);

        /* Print log and get lastInDB. */
        if($printLog) $this->printLog("get this repo logs.");
        $lastInDB = $this->loadModel('repo')->getLatestCommit($repo->id);

        /* Ignore unsynced repo. */
        if(empty($lastInDB))
        {
            if($printLog) $this->printLog("Please init repo {$repo->name}");
            return false;
        }

        $logs = $this->repo->getUnsyncedCommits($repo);
        if(empty($logs)) return true;

        /* Update code commit history. */
        if($printLog) $this->printLog("get " . count($logs) . " logs");
        if($printLog) $this->printLog('begin parsing logs');

        $this->saveCommits($repo, $logs, $lastInDB, $commentGroup, $printLog);

        if($printLog) $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");
    }

    /**
     * 设置代码库列表。
     * Set the repos.
     *
     * @access public
     * @return void
     */
    public function setRepos()
    {
        $repos = $this->loadModel('repo')->getListBySCM('Subversion');

        $svnRepos = array();
        $paths    = array();

        foreach($repos as $repo)
        {
            if(isset($paths[$repo->path])) continue;

            unset($repo->acl);
            unset($repo->desc);
            $svnRepos[$repo->id] = $repo;
            $paths[$repo->path]  = $repo->path;
        }

        if(empty($svnRepos)) echo "You must set one svn repo.\n";

        $this->repos = $svnRepos;
    }

    /**
     * 获取代码库列表。
     * Get repos.
     *
     * @access public
     * @return array
     */
    public function getRepos(): array
    {
        $this->setRepos();
        return helper::arrayColumn($this->repos, 'path');
    }

    /**
     * 设置仓库属性。
     * Set repo.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function setRepo(object $repo): bool
    {
        $this->setClient($repo);
        $this->setRepoRoot($repo);
        return true;
    }

    /**
     * 设置svn客户端。
     * Set the svn binary client of a repo.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function setClient(object $repo): bool
    {
        $this->client = $repo->client . " --non-interactive";
        if(stripos($repo->path, 'https') === 0 || stripos($repo->path, 'svn') === 0)
        {
            $cmd     = $repo->client . ' --version --quiet';
            $version = `$cmd`;
            if(!$version) return false;

            if(version_compare($version, '1.6.0', '>'))
            {
                $this->client .= ' --trust-server-cert';
            }
        }
        if(isset($repo->account)) $this->client .= " --username $repo->account --password $repo->password --no-auth-cache";
        return true;
    }

    /**
     * 设置仓库根目录。
     * set the root path of a repo.
     *
     * @param  object $repo
     * @access public
     * @return void
     */
    public function setRepoRoot(object $repo)
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $info = $scm->info('');

        $this->repoRoot = $info->root;
    }

    /**
     * 获取仓库目录信息。
     * Get tags histories for repo.
     *
     * @param  object $repo
     * @param  string $path
     * @access public
     * @return array
     */
    public function getRepoTags(object $repo, string $path): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        return $scm->tags($path);
    }

    /**
     * 获取代码提交记录。
     * Get repo logs.
     *
     * @param  object  $repo
     * @param  int     $fromRevision
     * @access public
     * @return array
     */
    public function getRepoLogs(object $repo, int $fromRevision): array
    {
        /* The svn log command. */
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $logs = $scm->log('', $fromRevision);
        if(empty($logs)) return array();

        /* Process logs. */
        foreach($logs as $log)
        {
            $log->author = $log->committer;
            $log->msg    = $log->comment;
            $log->date   = $log->time;

            /* Process files. */
            $log->files = array();
            foreach($log->change as $file => $info) $log->files[$info['action']][] = $file;
        }
        return $logs;
    }

    /**
     * 根据URL获取对比信息。
     * Get diff by url.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string|false
     */
    public function diff(string $url, int $revision): string|false
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);
        putenv('LC_CTYPE=en_US.UTF-8');

        $oldRevision = $revision - 1;

        $url = str_replace('%2F', '/', urlencode($url));
        $url = str_replace('%3A', ':', $url);

        $cmd = $this->client . " diff -r $oldRevision:$revision $url 2>&1";
        $diff = `$cmd`;

        $encoding = isset($repo->encoding) ? $repo->encoding : 'utf-8';
        if($encoding and $encoding != 'utf-8') $diff = helper::convertEncoding($diff, $encoding);

        return $diff;
    }

    /**
     * 根据URL获取文件内容。
     * Cat a url.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string|false
     */
    public function cat(string $url, int $revision): string|false
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);

        putenv('LC_CTYPE=en_US.UTF-8');

        $url = str_replace('%2F', '/', urlencode($url));
        $url = str_replace('%3A', ':', $url);

        $cmd  = $this->client . " cat $url@$revision 2>&1";
        $code = `$cmd`;

        $encoding = isset($repo->encoding) ? $repo->encoding : 'utf-8';
        if($encoding and $encoding != 'utf-8') $code = helper::convertEncoding($code, $encoding);

        return $code;
    }

    /**
     * 根据URL获取代码库信息。
     * Get repo by url.
     *
     * @param  string $url
     * @access public
     * @return object|false
     */
    public function getRepoByURL(string $url): object|false
    {
        if(empty($this->repos)) $this->setRepos();
        foreach($this->repos as $repo)
        {
            if(strpos(strtolower($url), strtolower($repo->path)) !== false) return $repo;
        }
        return false;
    }

    /**
     * 输出日志。
     * Print log.
     *
     * @param  string $log
     * @access public
     * @return void
     */
    public function printLog(string $log)
    {
        echo helper::now() . " $log\n";
    }

    /**
     * 将日志从xml格式转换为对象。
     * Convert log from xml format to object.
     *
     * @param  array  $log
     * @access public
     * @return object|null
     */
    public function convertLog(array $log): object|null
    {
        if(empty($log)) return null;

        list($hash, $account, $date) = $log;

        $account = preg_replace('/^Author:/', '', $account);
        $account = trim(preg_replace('/<[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+>/', '', $account));
        $date    = trim(preg_replace('/^Date:/', '', $date));

        $count   = count($log);
        $comment = '';
        $files   = array();
        for($i = 3; $i < $count; $i++)
        {
            $line = $log[$i];
            if(preg_match('/^\s{2,}/', $line))
            {
                $comment .= $line;
            }
            elseif(strpos($line, "\t") !== false)
            {
                list($action, $entry) = explode("\t", $line);
                $entry = '/' . trim($entry);
                $files[$action][] = $entry;
            }
        }
        $parsedLog = new stdClass();
        $parsedLog->author    = $account;
        $parsedLog->revision  = trim(preg_replace('/^commit/', '', $hash));
        $parsedLog->msg       = trim($comment);
        $parsedLog->date      = date('Y-m-d H:i:s', strtotime($date));
        $parsedLog->files     = $files;

        return $parsedLog;
    }
}
