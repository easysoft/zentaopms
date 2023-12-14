<?php
/**
 * The model file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     git
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
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
     * Run.
     *
     * @access public
     * @return void
     */
    public function run()
    {
        /* Get repos and load module. */
        $this->setRepos();
        $this->loadModel('job');
        $this->loadModel('gitlab');
        $this->loadModel('repo');

        if(empty($this->repos)) return false;

        /* Get commit triggerType jobs by repoIdList. */
        $commentGroup = $this->job->getTriggerGroup('commit', array_keys($this->repos));

        /* Get tag triggerType jobs by repoIdList. */
        $tagGroup = $this->job->getTriggerGroup('tag', array_keys($this->repos));

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
                    if(!$isNew and $tag == $job->lastTag)
                    {
                        $isNew = true;
                        continue;
                    }
                    if(!$isNew) continue;

                    $lastTag = $tag;
                    if($lastTag) $this->loadModel('compile')->createByJob($job->id, $lastTag, 'tag');
                }
                if($lastTag) $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
            }
        }
    }

    /**
     * Update commit.
     *
     * @param  object $repo
     * @param  array  $commentGroup
     * @param  bool   $printLog
     * @access public
     * @return void
     */
    public function updateCommit($repo, $commentGroup, $printLog = true)
    {
        if($repo->SCM == 'Gitlab') return;

        /* Load module and print log. */
        $this->loadModel('repo');
        if($printLog) $this->printLog("begin repo $repo->id");

        if(!$this->setRepo($repo)) return false;

        /* Get branches and commits. */
        $branches = $this->repo->getBranches($repo);
        $commits  = $repo->commits;

        $accountPairs = array();
        if($repo->SCM == 'Gitlab')
        {
            $userList      = $this->loadModel('gitlab')->apiGetUsers($repo->gitService);
            $acountIDPairs = $this->gitlab->getUserIdAccountPairs($repo->gitService);
            foreach($userList as $gitlabUser) $accountPairs[$gitlabUser->realname] = zget($acountIDPairs, $gitlabUser->id, '');
        }
        elseif($repo->SCM == 'Gitea')
        {
            $userList      = $this->loadModel('gitea')->apiGetUsers($repo->gitService);
            $acountIDPairs = $this->gitea->getUserAccountIdPairs($repo->gitService, 'openID,account');
            foreach($userList as $gitlabUser) $accountPairs[$gitlabUser->realname] = zget($acountIDPairs, $gitlabUser->id, '');
        }
        elseif($repo->SCM == 'Gogs')
        {
            $userList      = $this->loadModel('gogs')->apiGetUsers($repo->gitService);
            $acountIDPairs = $this->gogs->getUserAccountIdPairs($repo->gitService, 'openID,account');
            foreach($userList as $gitlabUser) $accountPairs[$gitlabUser->realname] = zget($acountIDPairs, $gitlabUser->id, '');
        }

        /* Update code commit history. */
        foreach($branches as $branch)
        {
            if($printLog) $this->printLog("sync branch $branch logs.");
            $_COOKIE['repoBranch'] = $branch;

            if($printLog) $this->printLog("get this repo logs.");

            $lastInDB = $this->repo->getLatestCommit($repo->id);

            /* Ignore unsynced branch. */
            if($repo->synced != 1)
            {
                if($printLog) $this->printLog("Please init repo {$repo->name}");
                continue;
            }

            $version = isset($lastInDB->commit) ? (int)$lastInDB->commit + 1 : 1;
            $logs    = $this->repo->getUnsyncedCommits($repo);
            $objects = array();
            if(!empty($logs))
            {
                if($printLog) $this->printLog("get " . count($logs) . " logs");
                if($printLog) $this->printLog('begin parsing logs');

                foreach($logs as $log)
                {
                    if($printLog) $this->printLog("parsing log {$log->revision}");
                    if($printLog) $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");

                    $objects     = $this->repo->parseComment($log->msg);
                    $lastVersion = $version;
                    $version     = $this->repo->saveOneCommit($repo->id, $log, $version, $branch);

                    if($objects)
                    {
                        if($printLog) $this->printLog('extract' .
                            ' story:' . join(' ', $objects['stories']) .
                            ' task:' . join(' ', $objects['tasks']) .
                            ' bug:'  . join(',', $objects['bugs']));

                        if($lastVersion != $version)
                        {
                            $this->repo->saveAction2PMS($objects, $log, $this->repoRoot, $repo->encoding, 'git', $accountPairs);

                            /* Objects link commit. */
                            foreach($objects as $objectType => $objectIDs)
                            {
                                $objectTypeMap = array('stories' => 'story', 'bugs' => 'bug', 'tasks' => 'task');
                                if(empty($objectIDs) or !isset($objectTypeMap[$objectType])) continue;

                                $this->post->$objectType = $objectIDs;
                                $this->repo->link($repo->id, $log->revision, $objectTypeMap[$objectType], 'commit');
                            }
                        }
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
                                $this->loadModel('job')->exec($job->id);
                                continue 2;
                            }
                        }
                    }
                    $commits += count($logs);
                }
            }
        }

        $this->repo->updateCommitCount($repo->id, $commits);
        $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repo->id)->exec();
        if($printLog) $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");
    }

    /**
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
     * Get repos.
     *
     * @access public
     * @return array
     */
    public function getRepos()
    {
        $this->setRepos();
        $repoPairs = array();
        foreach($this->repos as $repo) $repoPairs[] = $repo->path;

        return $repoPairs;
    }

    /**
     * Set repo.
     *
     * @param  object    $repo
     * @access public
     * @return bool
     */
    public function setRepo($repo)
    {
        $this->setClient($repo);
        if(empty($this->client)) return false;

        $this->setRepoRoot($repo);
        return true;
    }

    /**
     * Set the git binary client of a repo.
     *
     * @param  object    $repo
     * @access public
     * @return bool
     */

    public function setClient($repo)
    {
        $this->client = $repo->client;
        return true;
    }

    /**
     * set the root path of a repo.
     *
     * @param  object    $repo
     * @access public
     * @return void
     */
    public function setRepoRoot($repo)
    {
        $this->repoRoot = $repo->path;
    }

    /**
     * get tags histories for repo.
     *
     * @param  object    $repo
     * @access public
     * @return mixed
     */
    public function getRepoTags($repo)
    {
        if(empty($repo->client) or empty($repo->path) or !isset($repo->account) or !isset($repo->password) or !isset($repo->encoding)) return false;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        return $scm->tags('');
    }

    /**
     * Get repo logs.
     *
     * @param  object  $repo
     * @param  int     $fromRevision
     * @access public
     * @return array
     */
    public function getRepoLogs($repo, $fromRevision)
    {
        if(empty($repo->client) or empty($repo->path) or !isset($repo->account) or !isset($repo->password) or !isset($repo->encoding)) return false;

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
     * Convert log from xml format to object.
     *
     * @param  object    $log
     * @access public
     * @return object
     */
    public function convertLog($log)
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
     * Print log.
     *
     * @param  string $log
     * @access public
     * @return void
     */
    public function printLog($log)
    {
        echo helper::now() . " $log\n";
    }
}
