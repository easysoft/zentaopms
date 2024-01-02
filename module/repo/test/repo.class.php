<?php
class repoTest
{
    public function __construct()
    {
         global $tester, $config;
         $config->requestType = 'PATH_INFO';
         $this->objectModel = $tester->loadModel('repo');
    }

    /**
     * Check priv test.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function checkPrivTest($repo)
    {
        $objects = $this->objectModel->checkPriv($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setMenuTest(int $repoID = 0)
    {
        $repos  = $this->objectModel->dao->select('id')->from(TABLE_REPO)->fetchPairs('id');
        ob_start();
        $this->objectModel->setMenu($repos, $repoID);
        $result = ob_get_clean();

        if($result) return $result;
        return $this->objectModel->session->repoID;
    }

    /**
     * Get switcher test.
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getSwitcherTest($repoID)
    {
        $objects = $this->objectModel->getSwitcher($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListTest($projectID = 0, $SCM = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($projectID , $SCM, $orderBy , $pager );

        if(dao::isError()) return dao::getError();

        if(!empty($objects))
        {
           return $objects;
        }
        else
        {
           return 'empty';
        }
    }

    public function linkTest(int $repoID, string $revision, string $type, string $from, array $links)
    {
        if($type == 'story') $_POST['stories'] = $links;
        if($type == 'bug')   $_POST['bugs'] = $links;
        if($type == 'task')  $_POST['tasks'] = $links;

        $this->objectModel->link($repoID, $revision, $type, $from);
        if(dao::isError()) return dao::getError();

        $revisionInfo = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch();
        $relations    = array();
        foreach($links as $linkID)
        {
            $relations[] = $this->objectModel->dao->select('*')->from(TABLE_RELATION)
                ->where('AType')->eq('revision')
                ->andWhere('AID')->eq($revisionInfo->id)
                ->andWhere('BID')->eq($linkID)
                ->andWhere('relation')->eq('commit')
                ->andWhere('BType')->eq($type)
                ->fetch();
        }
        return $relations;
    }

    public function unlinkTest(int $repoID, string $revision, string $objectType, int $objectID)
    {
        $this->objectModel->unlink($repoID, $revision, $objectType, $objectID);
        if(dao::isError()) return dao::getError();

        return 'success';
    }

    public function getListBySCMTest($scm, $type = 'all')
    {
        $objects = $this->objectModel->getListBySCM($scm, $type = 'all');

        if(dao::isError()) return dao::getError();

        if(!empty($objects))
        {
            return $objects;
        }
        else
        {
            return 'empty';
        }
    }

    public function createTest($list, $isPipelineServer = true)
    {
        $init = array('SCM' => '', 'serviceHost' => '', 'serviceProject' => '', 'name' => '', 'path' => '', 'encoding' => '', 'client' => '', 'account' => '', 'password' => '', 'encrypt' => '', 'desc' => '', 'uid' => '');

        $repo = new stdclass();
        foreach($init as $filed => $defaultvalue) $repo->$filed = $defaultvalue;
        foreach($list as $key => $value) $repo->$key = $value;

        $repoID = $this->objectModel->create($repo, $isPipelineServer);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($repoID);
    }

    public function batchCreateTest($repos, $serviceHost)
    {
        $repoID = $this->objectModel->batchCreate($repos, $serviceHost);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getList();
    }

    public function updateTest($repoID, $data, $isPipelineServer)
    {
        $repo   = $this->objectModel->getByID($repoID);
        $result = $this->objectModel->update($data, $repo, $isPipelineServer);

        if(dao::isError()) return dao::getError();
        if($result === false) return 'changeServerProject';

        $newRepo = $this->objectModel->getByID($repoID);
        $changes = common::createChanges($repo, $newRepo);
        return $changes;
    }

    public function saveStateTest($repoID = 0, $objectID = 0)
    {
        $objects = $this->objectModel->saveState($repoID, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoPairsTest($type, $projectID = 0)
    {
        $objects = $this->objectModel->getRepoPairs($type, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoGroupTest($type, $projectID = 0)
    {
        $objects = $this->objectModel->getRepoGroup($type, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoByIDTest($repoID)
    {
        $objects = $this->objectModel->getRepoByID($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoByUrlTest($url)
    {
        $objects = $this->objectModel->getRepoByUrl($url);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoListByUrlTest($url = '')
    {
        $objects = $this->objectModel->getRepoListByUrl($url = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByIdListTest($idList)
    {
        $objects = $this->objectModel->getByIdList($idList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBranchesTest(int $repoID, bool $printLabel = false)
    {
        $repo = $this->objectModel->getByID($repoID);
        $objects = $this->objectModel->getBranches($repo, $printLabel);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCommitsTest($repo, $entry, $revision = 'HEAD', $type = 'dir', $pager = null, $begin = 0, $end = 0)
    {
        $objects = $this->objectModel->getCommits($repo, $entry, $revision, $type, $pager, $begin, $end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLatestCommitTest(int $repoID)
    {
        $objects = $this->objectModel->getLatestCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRevisionsFromDBTest(int $repoID, int $limit = 0, string $maxRevision = '', string $minRevision = '')
    {
        $objects = $this->objectModel->getRevisionsFromDB($repoID, $limit, $maxRevision, $minRevision);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getHistoryTest($repoID, $revisions)
    {
        $objects = $this->objectModel->getHistory($repoID, $revisions);

        if(dao::isError()) return dao::getError();

        return !empty($objects) ? array_keys($objects) : 'empty';
    }

    public function getGitRevisionNameTest($revision, $commit)
    {
        $objects = $this->objectModel->getGitRevisionName($revision, $commit);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductsByRepoTest($repoID)
    {
        $objects = $this->objectModel->getProductsByRepo($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveCommitTest(int $repoID, int $version, string $branch = '')
    {
        global $dao;
        $dao->exec('truncate table zt_repohistory');
        $dao->exec('truncate table zt_repofiles');

        $repo = $this->objectModel->getByID($repoID);

        $scm = $this->objectModel->app->loadClass('scm');
        $scm->setEngine($repo);
        $logs = $scm->getCommits('HEAD', 0);

        $objects = $this->objectModel->saveCommit($repoID, $logs, $version, $branch = '');

        if(dao::isError()) return dao::getError();

        if($version > 1) return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetchAll('id');
        if($repo->SCM == 'Subversion')
        {
            $result = array();
            $result['count'] = $objects;
            $result['files'] = $this->objectModel->dao->select('*')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->fetchAll('id');
            return $result;
        }
        return $objects;
    }

    public function saveOneCommitTest(int $repoID, int $version, string $branch = '')
    {
        global $dao;
        $dao->exec('truncate table zt_repohistory');

        $repo = $this->objectModel->getByID($repoID);
        $logs = $this->objectModel->getUnsyncedCommits($repo);

        foreach($logs as $log)
        {
            $result = $this->objectModel->saveOneCommit($repoID, $log, $version, $branch);
            break;
        }

        if(dao::isError()) return dao::getError();

        if($branch) return $this->objectModel->dao->select('*')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->fetch();
        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetch();
    }

    public function saveExistCommits4BranchTest($repoID, $branch)
    {
        $objects = $this->objectModel->saveExistCommits4Branch($repoID, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateCommitCountTest(int $repoID, int $count)
    {
        $objects = $this->objectModel->updateCommitCount($repoID, $count);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($repoID);
    }

    public function getUnsyncedCommitsTest(int $repoID)
    {
        global $dao;
        $dao->exec('truncate table zt_repohistory');

        $repo = $this->objectModel->getByID($repoID);

        $objects = $this->objectModel->getUnsyncedCommits($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createLinkTest($method, $params = '', $viewType = '', $onlybody = false)
    {
        $this->objectModel->config->webRoot = '';
        $objects = $this->objectModel->createLink($method, $params, $viewType, $onlybody);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function markSyncedTest($repoID)
    {
        $objects = $this->objectModel->markSynced($repoID);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($repoID);
    }

    public function fixCommitTest($repoID)
    {
        $objects = $this->objectModel->fixCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetchAll('id');
    }

    public function encodePathTest($path = '')
    {
        $objects = $this->objectModel->encodePath($path);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function decodePathTest($path = '')
    {
        $objects = $this->objectModel->decodePath($path);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkClientTest()
    {
        $objects = $this->objectModel->checkClient();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function rmClientVersionFileTest()
    {
        $objects = $this->objectModel->rmClientVersionFile();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkConnectionTest()
    {
        $objects = $this->objectModel->checkConnection();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function replaceCommentLinkTest($comment)
    {
        $this->objectModel->config->webRoot     = '';
        $this->objectModel->config->requestType = 'PATH_INFO';

        $objects = $this->objectModel->replaceCommentLink($comment);

        if(dao::isError()) return dao::getError();

        return str_replace(PHP_EOL, '', $objects);
    }

    public function addLinkTest($comment, $type)
    {
        $this->objectModel->config->webRoot     = '';
        $this->objectModel->config->requestType = 'PATH_INFO';

        $rules     = $this->objectModel->processRules();
        $objectReg = '/' . $rules[$type . 'Reg'] . '/i';
        if(preg_match_all($objectReg, $comment, $results))
        {
            $links = $this->objectModel->addLink($results, $type);
            foreach($links as $link) return rtrim($link);
        }

        return 'empty';
    }

    public function parseCommentTest($comment)
    {
        $objects = $this->objectModel->parseComment($comment);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function iconvCommentTest($comment, $encodings)
    {
        $objects = $this->objectModel->iconvComment($comment, $encodings);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processRulesTest()
    {
        $objects = $this->objectModel->processRules();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveAction2PMSTest($objects, $log, $repoRoot = '', $encodings = 'utf-8', $scm = 'svn', $gitlabAccountPairs = array())
    {
        $objects = $this->objectModel->saveAction2PMS($objects, $log, $repoRoot, $encodings, $scm, $gitlabAccountPairs);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveRecordTest($action, $changes)
    {
        $objects = $this->objectModel->saveRecord($action, $changes);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createActionChangesTest($log, $repoRoot, $scm = 'svn')
    {
        $objects = $this->objectModel->createActionChanges($log, $repoRoot, $scm);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskProductsAndExecutionsTest($tasks)
    {
        $objects = $this->objectModel->getTaskProductsAndExecutions($tasks);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildURLTest($methodName, $url, $revision, $scm = 'svn')
    {
        $objects = $this->objectModel->buildURL($methodName, $url, $revision, $scm);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processGitServiceTest(int $repoID)
    {
        $repo = $this->objectModel->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        $repo->codePath = $repo->path;

        $objects = $this->objectModel->processGitService($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function handleWebhookTest($event, $token, $data, $repo)
    {
        $objects = $this->objectModel->handleWebhook($event, $token, $data, $repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function syncCommitTest($repoID, $branchID)
    {
        $objects = $this->objectModel->syncCommit($repoID, $branchID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCloneUrlTest(int $repoID)
    {
        $repo = $this->objectModel->getByID($repoID);

        $objects = $this->objectModel->getCloneUrl($repo);

        if(dao::isError()) return dao::getError();

        if(empty((array)$objects)) return 'empty';
        return $objects;
    }

    public function getCacheFileTest(int $repoID, string $path, string $revision)
    {
        $result = $this->objectModel->getCacheFile($repoID, $path, $revision);

        if(strPos($result, 'repo/' . $repoID . '/' ) !== false) return true;
        return $result;
    }

    public function filterProjectTest(int $repoID)
    {
        $repo     = $this->objectModel->getByID($repoID);
        $products = !empty($repo->product) ? explode(',', $repo->product) : array();
        $projects = !empty($repo->projects) ? explode(',', $repo->projects) : array();

        $result = $this->objectModel->filterProject($products, $projects);
        return $result;
    }
}
