<?php
/**
 * The model file of svn module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        if(empty($this->repos)) return false;

        $this->loadModel('compile');
        /* Get commit triggerType integrations by repoIdList */
        $commitPlans = $this->loadModel('integration')->getListByTriggerType('commit', array_keys($this->repos));
        $commitGroup = array();
        foreach($commitPlans as $integration) $commitGroup[$integration->repo][$integration->id] = $integration;

        /* Get tag triggerType integrations by repoIdList */
        $tagPlans = $this->integration->getListByTriggerType('tag', array_keys($this->repos));
        $tagGroup = array();
        foreach($tagPlans as $integration) $tagGroup[$integration->repo][$integration->id] = $integration;

        $_COOKIE['repoBranch'] = '';
        foreach($this->repos as $repoID => $repo)
        {
            $this->printLog("begin repo {$repo->name}");
            if(!$this->setRepo($repo)) return false;

            $this->printLog("get this repo logs.");
            $lastInDB = $this->repo->getLatestComment($repoID);
            /* Ignore unsynced repo. */
            if(empty($lastInDB)) continue;

            $version = $lastInDB->commit;
            $logs    = $this->repo->getUnsyncLogs($repo);
            $objects = array();
            if(!empty($logs))
            {
                $this->printLog("get " . count($logs) . " logs");
                $this->printLog('begin parsing logs');

                foreach($logs as $log)
                {
                    $this->printLog("parsing log {$log->revision}");

                    $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");
                    $objects = $this->repo->parseComment($log->msg);
                    if($objects)
                    {
                        $this->printLog('extract' .
                            'task:' . join(' ', $objects['tasks']) .
                            ' bug:'  . join(',', $objects['bugs']));
                        $this->repo->saveAction2PMS($objects, $log, $repo->encoding, 'svn');
                    }
                    else
                    {
                        $this->printLog('no objects found' . "\n");
                    }

                    /* Create compile by comment. */
                    $integrations = zget($commitGroup, $repoID, array());
                    foreach($integrations as $integration)
                    {
                        foreach(explode(',', $integration->comment) as $comment)
                        {
                            if(strpos($log->msg, $comment) !== false) $this->compile->createByIntegration($integration->id);
                        }
                    }

                    $version = $this->repo->saveOneCommit($repoID, $log, $version);
                }
                $this->repo->updateCommitCount($repoID, $lastInDB->commit + count($logs));
                $this->dao->update(TABLE_REPO)->set('lastSync')->eq(helper::now())->where('id')->eq($repoID)->exec();

                $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");
            }

            /* Create compile by tag. */
            $integrations = zget($tagGroup, $repoID, array());
            foreach($integrations as $integration)
            {
                $dirs = $this->getRepoTags($repo, $integration->svnDir);
                end($dirs);
                $lastTag = current($dirs);
                if($lastTag != $integration->lastTag)
                {
                    $tag = rtrim($repo->path , '/') . '/' . trim($integration->svnDir, '/') . '/' . $lastTag;
                    $this->compile->createByIntegration($integration->id, $tag, 'tag');
                    $this->dao->update(TABLE_INTEGRATION)->set('lastTag')->eq($lastTag)->where('id')->eq($integration->id)->exec();
                }
            }
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
        $parent = '/';
        if($repo->prefix) $parent = rtrim($repo->prefix, '/');
        if(trim($path, '/')) $parent = rtrim($repo->prefix, '/') . '/' . trim($path, '/');
        $stmt = $this->dao->select('t1.*,t2.revision as svnRevision,t2.time')->from(TABLE_REPOFILES)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.revision = t2.id')
            ->where('t1.repo')->eq($repo->id)
            ->andWhere('t1.type')->eq('dir')
            ->andWhere('t1.parent')->eq($parent)
            ->orderBy('path,svnRevision,time')
            ->query();

        $dirs = array();
        while($row = $stmt->fetch())
        {
            $path = $row->path;
            if($repo->prefix) $path = str_replace($repo->prefix, '', $path);
            if(empty($path)) $path = '/';

            $dirs[$path] = $row;
            if($row->action == 'D') unset($dirs[$path]);
        }

        $dirTime = array();
        foreach($dirs as $dirPath => $dir) $dirTime[$dir->time][$dirPath] = $dirPath;

        ksort($dirTime);
        $dirPairs = array();
        foreach($dirTime as $time => $dirPaths)
        {
            ksort($dirPaths);
            foreach($dirPaths as $dirPath) $dirPairs[$dirPath] = basename($dirPath);
        }

        return $dirPairs;
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
        foreach($this->config->svn->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            if(strpos(strtolower($url), strtolower($repo['path'])) !== false) return (object)$repo;
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
