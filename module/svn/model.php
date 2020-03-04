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
     * The log root.
     * 
     * @var string
     * @access public
     */
    public $logRoot = '';

    /**
     * The restart file.
     * 
     * @var string
     * @access public
     */
    public $restartFile = '';

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

        $this->setLogRoot();
        $this->setRestartFile();

        foreach($this->repos as $name => $repo)
        {
            $this->printLog("begin repo $name");
            if(!$this->setRepo($repo)) return false;

            $savedRevision = $this->getSavedRevision();
            $this->printLog("start from revision $savedRevision");

            $logs    = $this->getRepoLogs($repo, $savedRevision);
            $objects = array();
            if(!empty($logs))
            {
                $this->printLog("get " . count($logs) . " logs");
                $this->printLog('begin parsing logs');

                foreach($logs as $log)
                {
                    $this->printLog("parsing log {$log->revision}");
                    if($log->revision == $savedRevision)
                    {
                        $this->printLog("{$log->revision} alread parsed, commit it");
                        continue;
                    }

                    $this->printLog("comment is\n----------\n" . trim($log->msg) . "\n----------");

                    $objects = $this->repo->parseComment($log->msg);
                    if($objects)
                    {
                        $this->printLog('extract' .
                            'story:' . join(' ', $objects['stories']) .
                            ' task:' . join(' ', $objects['tasks']) .
                            ' bug:'  . join(',', $objects['bugs']));

                        $this->saveAction2PMS($objects, $log, $repo->encoding);
                    }
                    else
                    {
                        $this->printLog('no objects found' . "\n");
                    }

                    if($log->revision > $savedRevision) $savedRevision = $log->revision;
                }

                $this->saveLastRevision($savedRevision);
                $this->printLog("save revision $savedRevision");
                $this->deleteRestartFile();
                $this->printLog("\n\nrepo #" . $repo->id . ': ' . $repo->path . " finished");
            }

            // Create compile by integration.
            $integrations = zget($objects, 'integrations', array());
            $this->loadModel('compile');
            foreach($integrations as $id) $this->compile->createByIntegration($id);

            /* Create compile by tag. */
            $integrations = $this->dao->select('*')->from(TABLE_INTEGRATION)->where('triggerType')->eq('tag')->andWhere('repo')->eq($repo->id)->fetchAll('id');
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
     * Set the log root.
     * 
     * @access public
     * @return void
     */
    public function setLogRoot()
    {
        $this->logRoot = $this->app->getTmpRoot() . 'svn/';
        if(!is_dir($this->logRoot)) mkdir($this->logRoot);
    }

    /**
     * Set the tag file of a repo.
     *
     * @param  string    $repoId
     * @access public
     * @return void
     */
    public function setTagFile($repoId)
    {
        $this->setLogRoot();
        $this->tagFile = $this->logRoot . $repoId . '.tag';
    }

    /**
     * Set the restart file.
     * 
     * @access public
     * @return void
     */
    public function setRestartFile()
    {
        $this->restartFile = dirname(__FILE__) . '/restart';
    }

    /**
     * Delete the restart file.
     * 
     * @access public
     * @return void
     */
    public function deleteRestartFile()
    {
        if(is_file($this->restartFile)) unlink($this->restartFile);
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
            $svnRepos[] = $repo;
            $paths[$repo->path] = $repo->path;
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
        $repos = array();
        if(!$this->config->svn->repos) return $repos;

        foreach($this->config->svn->repos as $repo)
        {
            if(empty($repo['path'])) continue;
            $repos[] = $repo['path'];
        }
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

        $this->setLogFile($repo->id);
        $this->setTagFile($repo->id);
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
     * Set the log file of a repo.
     * 
     * @param  string    $repoName 
     * @access public
     * @return void
     */
    public function setLogFile($repoId)
    {
        $this->logFile = $this->logRoot . $repoId . '.log';
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
     * Save action to pms.
     * 
     * @param  array    $objects 
     * @param  object   $log 
     * @param  string   $repoRoot 
     * @access public
     * @return void
     */
    public function saveAction2PMS($objects, $log, $repoRoot = '', $encodings = 'utf-8')
    {
        $action = new stdclass();
        $action->actor   = $log->author;
        $action->action  = 'svncommited';
        $action->date    = $log->date;

        $action->comment = htmlspecialchars($this->repo->iconvComment($log->msg, $encodings));
        $action->extra   = $log->revision;

        $changes = $this->createActionChanges($log, $repoRoot);

        if($objects['stories'])
        {
            $products = $this->getStoryProducts($objects['stories']);
            foreach($objects['stories'] as $storyID)
            {
                $storyID = (int)$storyID;
                if(!isset($products[$storyID])) continue;

                $action->objectType = 'story';
                $action->objectID   = $storyID;
                $action->product    = $products[$storyID];
                $action->project    = 0;

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['tasks'])
        {
            $productsAndProjects = $this->getTaskProductsAndProjects($objects['tasks']);
            foreach($objects['tasks'] as $taskID)
            {
                $taskID = (int)$taskID;
                if(!isset($productsAndProjects[$taskID])) continue;

                $action->objectType = 'task';
                $action->objectID   = $taskID;
                $action->product    = $productsAndProjects[$taskID]['product'];
                $action->project    = $productsAndProjects[$taskID]['project'];

                $this->saveRecord($action, $changes);
            }
        }

        if($objects['bugs'])
        {
            $productsAndProjects = $this->getBugProductsAndProjects($objects['bugs']);

            foreach($objects['bugs'] as $bugID)
            {
                $bugID = (int)$bugID;
                if(!isset($productsAndProjects[$bugID])) continue;

                $action->objectType = 'bug';
                $action->objectID   = $bugID;
                $action->product    = $productsAndProjects[$bugID]->product;
                $action->project    = $productsAndProjects[$bugID]->project;

                $this->saveRecord($action, $changes);
            }
        }
    }

    /**
     * Save an action to pms.
     * 
     * @param  object $action
     * @param  object $log
     * @access public
     * @return bool
     */
    public function saveRecord($action, $changes)
    {
        $record = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($action->objectType)
            ->andWhere('objectID')->eq($action->objectID)
            ->andWhere('extra')->eq($action->extra)
            ->andWhere('action')->eq('svncommited')
            ->fetch();
        if($record)
        {
            $this->dao->update(TABLE_ACTION)->data($action)->where('id')->eq($record->id)->exec();
            if($changes)
            {
                $historyID = $this->dao->findByAction($record->id)->from(TABLE_HISTORY)->fetch('id');
                if($historyID)
                {
                    $this->dao->update(TABLE_HISTORY)->data($changes)->where('id')->eq($historyID)->exec();
                }
                else
                {
                    $this->action->logHistory($record->id, array($changes));
                }
            }
        }
        else
        {
            $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
            if($changes)
            {
                $actionID = $this->dao->lastInsertID();
                $this->action->logHistory($actionID, array($changes));
            }
        }
    }

    /**
     * Create changes for action from a log.
     * 
     * @param  object    $log 
     * @param  string    $repoRoot 
     * @access public
     * @return array
     */
    public function createActionChanges($log, $repoRoot)
    {
        if(!$log->files) return array();
        $diff = '';

        $oldSelf = $this->server->PHP_SELF;
        $this->server->set('PHP_SELF', $this->config->webRoot, '', false, true);

        if(!$repoRoot) $repoRoot = $this->repoRoot;

        foreach($log->files as $action => $actionFiles)
        {
            foreach($actionFiles as $file)
            {
                $catLink  = trim(html::a($this->buildURL('cat', $repoRoot . $file, $log->revision), 'view', '', "class='iframe' data-width='960'"));
                $diffLink = trim(html::a($this->buildURL('diff', $repoRoot . $file, $log->revision), 'diff', '', "class='iframe' data-width='960'"));
                $diff .= $action . " " . $file . " $catLink ";
                $diff .= $action == 'M' ? "$diffLink\n" : "\n" ;
            }
        }
        $changes = new stdclass();
        $changes->field = 'subversion';
        $changes->old   = '';
        $changes->new   = '';
        $changes->diff  = trim($diff);

        $this->server->set('PHP_SELF', $oldSelf);
        return (array)$changes;
    }

    /**
     * Get products of stories.
     * 
     * @param  array    $stories 
     * @access public
     * @return array
     */
    public function getStoryProducts($stories)
    {
        return $this->dao->select('id, product')->from(TABLE_STORY)->where('id')->in($stories)->fetchPairs();
    }

    /**
     * Get products and projects of tasks.
     * 
     * @param  array    $tasks 
     * @access public
     * @return array
     */
    public function getTaskProductsAndProjects($tasks)
    {
        $records = array();
        $products = $this->dao->select('t1.id, t2.product')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.id')->in($tasks)->fetchPairs();

        $projects = $this->dao->select('id, project')->from(TABLE_TASK)->where('id')->in($tasks)->fetchPairs();

        foreach($projects as $taskID => $projectID)
        {
            $record = array();
            $record['project'] = $projectID;
            $record['product'] = isset($products[$taskID]) ? $products[$taskID] : 0;
            $records[$taskID] = $record;
        }
        return $records;
    }

    /**
     * Get products and projects of bugs.
     * 
     * @param  array    $bugs 
     * @access public
     * @return array
     */
    public function getBugProductsAndProjects($bugs)
    {
        return $this->dao->select('id, project, product')->from(TABLE_BUG)->where('id')->in($bugs)->fetchAll('id');
    }

    /**
     * Get the saved revision.
     * 
     * @access public
     * @return int
     */
    public function getSavedRevision()
    {
        if(!file_exists($this->logFile)) return 0;
        if(file_exists($this->restartFile)) return 0;
        return (int)trim(file_get_contents($this->logFile));
    }

    /**
     * Save the last revision.
     * 
     * @param  int    $revision 
     * @access public
     * @return void
     */
    public function saveLastRevision($revision)
    {
        file_put_contents($this->logFile, $revision);
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

    /**
     * Build URL.
     * 
     * @param  string $methodName 
     * @param  string $url 
     * @param  int    $revision 
     * @access public
     * @return string
     */
    public function buildURL($methodName, $url, $revision)
    {
        $buildedURL  = helper::createLink('svn', $methodName, "url=&revision=$revision", 'html');
        $buildedURL .= strpos($buildedURL, '?') === false ? '?' : '&';
        $buildedURL .= 'repoUrl=' . helper::safe64Encode($url);

        return $buildedURL;
    }

    /**
     * Get the saved tag.
     *
     * @access public
     * @return int
     */
    public function getSavedTag($repoID = 0)
    {
        if($repoID) $this->setTagFile($repoID);
        if(!file_exists($this->tagFile)) return array();
        if(file_exists($this->restartFile)) return array();

        $tags = array();
        foreach(json_decode(file_get_contents($this->tagFile)) as $tag) $tags[$tag] = $tag;
        return $tags;
    }

    /**
     * Save the last revision.
     *
     * @param  int    $tag
     * @access public
     * @return void
     */
    public function saveLastTag($tag, $repoId = 0)
    {
        if($repoId) $this->setTagFile($repoId);
        if(is_array($tag)) $tag = json_encode($tag);
        file_put_contents($this->tagFile, $tag);
    }
}
