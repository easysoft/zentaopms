<?php
/**
 * The model file of svn module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     svn
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class svnModel extends model
{
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
        $this->setRepos();
        $this->loadModel('job');
        if(empty($this->repos)) return false;

        /* Get commit triggerType jobs by repoIdList. */
        $commentGroup = $this->job->getTriggerGroup('commit', array_keys($this->repos));

        /* Get tag triggerType jobs by repoIdList. */
        $tagGroup = $this->job->getTriggerGroup('tag', array_keys($this->repos));

        $_COOKIE['repoBranch'] = '';
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
                    if(!$isNew and $dir == $job->lastTag)
                    {
                        $isNew = true;
                        continue;
                    }
                    if(!$isNew) continue;

                    $lastTag = $dir;
                    $tag     = rtrim($repo->path , '/') . '/' . trim($job->svnDir, '/') . '/' . $lastTag;
                    $this->loadModel('compile')->createByJob($job->id, $tag, 'tag');
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
        /* Load mudule and print log. */
        $this->loadModel('repo');
        if($printLog) $this->printLog("begin repo {$repo->name}");

        if(!$this->setRepo($repo)) return false;

        /* Print log and get lastInDB. */
        if($printLog) $this->printLog("get this repo logs.");
        $lastInDB = $this->repo->getLatestCommit($repo->id);

        /* Ignore unsynced repo. */
        if(empty($lastInDB))
        {
            if($printLog) $this->printLog("Please init repo {$repo->name}");
            return false;
        }

        $version = (int)$lastInDB->commit + 1;
        $logs    = $this->repo->getUnsyncedCommits($repo);

        /* Update code commit history. */
        $objects = array();
        if(!empty($logs))
        {
            if($printLog) $this->printLog("get " . count($logs) . " logs");
            if($printLog) $this->printLog('begin parsing logs');

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

            if($printLog) $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");
        }
    }

    /**
     * Set the repos.
     *
     * @access public
     * @return bool
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
        if($this->repos) $this->setRepos();

        $repos = array();
        foreach($this->repos as $repo) $repos[] = $repo->path;
        return $repos;
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
     * Set the svn binary client of a repo.
     *
     * @param  object    $repo
     * @access public
     * @return bool
     */
    public function setClient($repo)
    {
        $this->client = $repo->client . " --non-interactive";
        if(stripos($repo->path, 'https') === 0 or stripos($repo->path, 'svn') === 0)
        {
            $cmd = $repo->client . ' --version --quiet';
            $version = `$cmd`;
            if(version_compare($version, '1.6.0', '>'))
            {
                $this->client .= ' --trust-server-cert';
            }
        }
        if(isset($repo->account)) $this->client .= " --username $repo->account --password $repo->password --no-auth-cache";
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
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $info = $scm->info('');
        $this->repoRoot = $info->root;
    }

    /**
     * get tags histories for repo.
     *
     * @param  object    $repo
     * @access public
     * @return void
     */
    public function getRepoTags($repo, $path)
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        return $scm->tags($path);
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
        /* The svn log command. */
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $logs = $scm->log('', $fromRevision);
        if(empty($logs)) return false;

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
     * Diff a url.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string|bool
     */
    public function diff($url, $revision)
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;
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
     * Cat a url.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string|bool
     */
    public function cat($url, $revision)
    {
        $repo = $this->getRepoByURL($url);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;

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
     * Get repo by url.
     *
     * @param  string    $url
     * @access public
     * @return object|bool
     */
    public function getRepoByURL($url)
    {
        if(empty($this->repos)) $this->setRepos();
        foreach($this->repos as $repo)
        {
            if(strpos(strtolower($url), strtolower($repo->path)) !== false) return $repo;
        }
        return false;
    }

    /**
     * Pring log.
     *
     * @param  sting    $log
     * @access public
     * @return void
     */
    public function printLog($log)
    {
        echo helper::now() . " $log\n";
    }
}
