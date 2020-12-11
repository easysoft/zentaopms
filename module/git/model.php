<?php
/**
 * The model file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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

        if(empty($this->repos)) return false;

        /* Get commit triggerType jobs by repoIdList. */
        $commentGroup = $this->job->getTriggerGroup('commit', array_keys($this->repos));

        /* Get tag triggerType jobs by repoIdList. */
        $tagGroup = $this->job->getTriggerGroup('tag', array_keys($this->repos));

        foreach($this->repos as $repoID => $repo)
        {
            $this->updateCommit($repo, $commentGroup, true);

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
        /* Load mudule and print log. */
        $this->loadModel('repo');
        if($printLog) $this->printLog("begin repo $repo->id");

        if(!$this->setRepo($repo)) return false;

        /* Get branches and commits. */
        $branches = $this->repo->getBranches($repo);
        $commits  = $repo->commits;

        /* Update code commit history. */
        foreach($branches as $branch)
        {
            if($printLog) $this->printLog("sync branch $branch logs.");
            $_COOKIE['repoBranch'] = $branch;

            if($printLog) $this->printLog("get this repo logs.");

            $lastInDB = $this->repo->getLatestCommit($repo->id);

            /* Ignore unsynced branch. */
            if(empty($lastInDB))
            {
                if($printLog) $this->printLog("Please init repo {$repo->name}");
                continue;
            }

            $version = $lastInDB->commit;
            $logs    = $this->repo->getUnsyncCommits($repo);
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

                        $this->repo->saveAction2PMS($objects, $log, $this->repoRoot, $repo->encoding, 'git');
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
                            if(strpos($log->msg, $comment) !== false) $this->loadModel('compile')->createByJob($job->id);
                        }
                    }
                    $version  = $this->repo->saveOneCommit($repo->id, $log, $version, $branch);
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
     * @return bool
     */
    public function setRepos()
    {
        $repos    = $this->loadModel('repo')->getListBySCM('Git');
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
        $repos     = $this->setRepos();
        $repoPairs = array();
        foreach($repos as $repo) $repoPairs[] = $repo->path;

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
     * @return void
     */
    public function getRepoTags($repo)
    {
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
     * Diff a url.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function diff($path, $revision)
    {
        $repo = $this->getRepoByURL($path);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;
        putenv('LC_CTYPE=en_US.UTF-8');

        chdir($repo->path);
        exec("{$this->client} config core.quotepath false");
        $subPath = substr($path, strlen($repo->path));
        if($subPath[0] == '/' or $subPath[0] == '\\') $subPath = substr($subPath, 1);

        $encodings = explode(',', $this->config->git->encodings);
        foreach($encodings as $encoding)
        {
            $encoding = trim($encoding);
            if($encoding == 'utf-8') continue;
            $subPath = helper::convertEncoding($subPath, 'utf-8', $encoding);
            if($subPath) break;
        }

        exec("$this->client rev-list -n 2 $revision -- $subPath", $lists);
        if(count($lists) == 2) list($nowRevision, $preRevision) = $lists;
        $cmd = "$this->client diff $preRevision $nowRevision -- $subPath 2>&1";
        $diff = `$cmd`;

        $encoding = isset($repo->encoding) ? $repo->encoding : 'utf-8';
        if($encoding and $encoding != 'utf-8') $diff = helper::convertEncoding($diff, $encoding);

        return $diff;
    }

    /**
     * Cat a url.
     * 
     * @param  string $path
     * @param  int    $revision 
     * @access public
     * @return string|bool
     */
    public function cat($path, $revision)
    {
        $repo = $this->getRepoByURL($path);
        if(!$repo) return false;

        $this->setClient($repo);
        if(empty($this->client)) return false;

        putenv('LC_CTYPE=en_US.UTF-8');

        $subPath = substr($path, strlen($repo->path));
        if($subPath[0] == '/' or $subPath[0] == '\\') $subPath = substr($subPath, 1);

        $encodings = explode(',', $this->config->git->encodings);
        foreach($encodings as $encoding)
        {
            $encoding = trim($encoding);
            if($encoding == 'utf-8') continue;
            $subPath = helper::convertEncoding($subPath, 'utf-8', $encoding);
            if($subPath) break;
        }

        chdir($repo->path);
        exec("{$this->client} config core.quotepath false");
        $cmd  = "$this->client show $revision:$subPath 2>&1";
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
        foreach($this->config->git->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            if(strpos($url, $repo['path']) !== false) return (object)$repo;
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
