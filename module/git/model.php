<?php
declare(strict_types=1);
/**
 * The model file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     git
 * @link        http://www.zentao.net
 */
class gitModel extends model
{
    /**
     * The git binary client.
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
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('action');
        $this->loadModel('repo');
    }

    /**
     * 执行定时任务同步提交信息。
     * Sync commit info by cron.
     *
     * @access public
     * @return bool
     */
    public function run(): bool
    {
        /* Get repos and load module. */
        $this->setRepos();
        if(empty($this->repos)) return false;

        /* Get commit triggerType jobs by repoIdList. */
        $commentGroup = $this->loadModel('job')->getTriggerGroup('commit', array_keys($this->repos));

        /* Get tag triggerType jobs by repoIdList. */
        $tagGroup = $this->job->getTriggerGroup('tag', array_keys($this->repos));

        $this->loadModel('compile');
        $this->loadModel('gitlab');
        $this->loadModel('repo');
        foreach($this->repos as $repoID => $repo)
        {
            $this->updateCommit($repo, $commentGroup, true);

            if($repo->SCM == 'Gitlab')
            {
                $this->gitlab->updateCodePath((int)$repo->serviceHost, (int)$repo->serviceProject, (int)$repo->id);
                $this->repo->updateCommitDate((int)$repo->id);
            }

            /* Create compile by tag. */
            $jobs = zget($tagGroup, $repoID, array());
            foreach($jobs as $job)
            {
                $tags    = $this->getRepoTags($repo);
                $isNew   = empty($job->lastTag) ? true : false;
                $lastTag = '';
                foreach($tags as $tag)
                {
                    if(empty($tag)) continue;
                    if(!$isNew && $tag == $job->lastTag)
                    {
                        $isNew = true;
                        continue;
                    }
                    if(!$isNew) continue;

                    $lastTag = $tag;
                    if($lastTag) $this->compile->createByJob($job->id, $lastTag, 'tag');
                }
                if($lastTag) $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 保存提交信息及更新关联对象。
     * Save commit info and update related objects.
     *
     * @param  object $repo
     * @param  string $branch
     * @param  array  $logs
     * @param  int    $version
     * @param  array  $commentGroup
     * @param  array  $accountPairs
     * @param  bool   $printLog
     * @access public
     * @return int
     */
    public function saveCommits(object $repo, string $branch, array $logs, int $version, array $commentGroup, array $accountPairs, bool $printLog): int
    {
        if($printLog) $this->printLog("get " . count($logs) . " logs\n" . 'begin parsing logs');

        $this->loadModel('repo');
        foreach($logs as $log)
        {
            if($printLog) $this->printLog("parsing log {$log->revision}");
            if($printLog) $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");

            $objects     = $this->repo->parseComment($log->msg);
            $lastVersion = $version;
            $version     = $this->repo->saveOneCommit($repo->id, $log, $version, $branch);

            if($objects)
            {
                if($printLog) $this->printLog('extract' . ' story:' . join(' ', $objects['stories']) . ' task:' . join(' ', $objects['tasks']) . ' bug:' . join(',', $objects['bugs']));
                if($lastVersion != $version)
                {
                    $this->repo->saveAction2PMS($objects, $log, $this->repoRoot, $repo->encoding, 'git', $accountPairs);

                    /* Objects link commit. */
                    foreach($objects as $objectType => $objectIDs)
                    {
                        $objectTypeMap = array('stories' => 'story', 'bugs' => 'bug', 'tasks' => 'task');
                        if(empty($objectIDs) || !isset($objectTypeMap[$objectType])) continue;

                        $this->post->$objectType = $objectIDs;
                        $this->repo->link($repo->id, $log->revision, $objectTypeMap[$objectType], 'commit');
                    }
                }
            }
            elseif($printLog)
            {
                $this->printLog('no objects found' . "\n");
            }

            /* Create compile by comment. */
            $jobs = zget($commentGroup, $repo->id, array());
            foreach($jobs as $job)
            {
                foreach(explode(',', $job->comment) as $comment)
                {
                    if(strpos($log->msg, $comment) !== false) $this->loadModel('job')->exec($job->id);
                }
            }
        }

        return count($logs);
    }

    /**
     * 更新提交信息。
     * Update commit.
     *
     * @param  object $repo
     * @param  array  $commentGroup
     * @param  bool   $printLog
     * @access public
     * @return bool
     */
    public function updateCommit(object $repo, array $commentGroup, bool $printLog = true): bool
    {
        if($repo->SCM == 'Gitlab') return false;

        /* Load module and print log. */
        if($printLog) $this->printLog("begin repo $repo->id");
        if(!$this->setRepo($repo)) return false;

        /* Get branches and commits. */
        $branches = $this->loadModel('repo')->getBranches($repo);
        $commits  = $repo->commits;

        $scm           = strtolower($repo->SCM);
        $userList      = $this->loadModel($scm)->apiGetUsers($repo->gitService);
        $acountIDPairs = $this->{$scm}->getUserAccountIdPairs($repo->gitService, 'openID,account');

        $accountPairs = array();
        foreach($userList as $user) $accountPairs[$user->realname] = zget($acountIDPairs, $user->id, '');

        /* Update code commit history. */
        foreach($branches as $branch)
        {
            if($printLog) $this->printLog("sync branch $branch logs.");
            $_COOKIE['repoBranch'] = $branch;

            if($printLog) $this->printLog("get this repo logs.");

            /* Ignore unsynced branch. */
            if($repo->synced != 1)
            {
                if($printLog) $this->printLog("Please init repo {$repo->name}");
                continue;
            }

            $logs = $this->repo->getUnsyncedCommits($repo);
            if(empty($logs)) continue;

            $lastInDB = $this->repo->getLatestCommit($repo->id);
            $version  = isset($lastInDB->commit) ? (int)$lastInDB->commit + 1 : 1;
            $commits += $this->saveCommits($repo, $branch, $logs, $version, $commentGroup, $accountPairs, $printLog);
        }

        $this->repo->updateCommitCount($repo->id, $commits);
        $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repo->id)->exec();
        if($printLog) $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");

        return !dao::isError();
    }

    /**
     * 设置代码库列表。
     * Set the repos.
     *
     * @access public
     * @return mixed
     */
    public function setRepos()
    {
        $repos    = $this->loadModel('repo')->getListBySCM('Git,Gitlab,Gogs,Gitea');
        $gitRepos = array();
        $paths    = array();
        foreach($repos as $repo)
        {
            if(!isset($paths[$repo->path]))
            {
                unset($repo->acl);
                unset($repo->desc);
                $gitRepos[$repo->id] = $repo;
                $paths[$repo->path]  = $repo->path;
            }
        }

        if(empty($gitRepos)) echo "You must set one git repo.\n";

        $this->repos = $gitRepos;
        return true;
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
        $repoPairs = array();
        foreach($this->repos as $repo) $repoPairs[] = $repo->path;

        return $repoPairs;
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
        $this->client = $repo->client;
        if(empty($this->client)) return false;

        $this->repoRoot = $repo->path;
        return true;
    }

    /**
     * 获取仓库的分支列表。
     * get tags histories for repo.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getRepoTags(object $repo): array
    {
        if(in_array(true, array(empty($repo->client), empty($repo->path), !isset($repo->account), !isset($repo->password), !isset($repo->encoding)))) return false;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        return $scm->tags('');
    }

    /**
     * 获取代码提交记录。
     * Get repo logs.
     *
     * @param  object $repo
     * @param  string $fromRevision
     * @access public
     * @return array
     */
    public function getRepoLogs(object $repo, string $fromRevision): array
    {
        if(in_array(true, array(empty($repo->client), empty($repo->path), !isset($repo->account), !isset($repo->password), !isset($repo->encoding)))) return false;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $logs = $scm->log('', $fromRevision);
        if(empty($logs)) return false;

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
     * 将日志从xml格式转换为对象。
     * Convert log from xml format to object.
     *
     * @param  object $log
     * @access public
     * @return object
     */
    public function convertLog(object $log): object
    {
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

    /**
     * 输出日志信息.
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
}
