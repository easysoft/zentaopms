<?php
class repoTest
{
    public function __construct()
    {
         global $tester;
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

    public function setMenuTest($repos, $repoID = '', $showSeleter = true)
    {
        $objects = $this->objectModel->setMenu($repos, $repoID, $showSeleter);

        if(dao::isError()) return dao::getError();

        return $objects;
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
           return $objects[1]->name;
        }
        else
        {
           return 'empty';
        }
    }

    public function getListBySCMTest($scm, $type = 'all')
    {
        $objects = $this->objectModel->getListBySCM($scm, $type = 'all');

        if(dao::isError()) return dao::getError();

        if(!empty($objects))
        {
            return $objects[0]->name;
        }
        else
        {
            return 'empty';
        }
    }

    public function createTest($list)
    {
        $init = array('SCM' => '', 'serviceHost' => '', 'serviceProject' => '', 'name' => '', 'path' => '', 'encoding' => '', 'client' => '', 'account' => '', 'password' => '', 'encrypt' => '', 'desc' => '', 'uid' => '');

        foreach($init as $filed => $defaultvalue) $_POST[$filed] = $defaultvalue;
        foreach($list as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->create();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($id)
    {
        $objects = $this->objectModel->update($id);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function getBranchesTest($repo, $printLabel = false)
    {
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

    public function getLatestCommitTest($repoID)
    {
        $objects = $this->objectModel->getLatestCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRevisionsFromDBTest($repoID, $limit = '', $maxRevision = '', $minRevision = '')
    {
        $objects = $this->objectModel->getRevisionsFromDB($repoID, $limit = '', $maxRevision = '', $minRevision = '');

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

    public function getCacheFileTest($repoID, $path, $revision)
    {
        $objects = $this->objectModel->getCacheFile($repoID, $path, $revision);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductsByRepoTest($repoID)
    {
        $objects = $this->objectModel->getProductsByRepo($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveCommitTest($repoID, $logs, $version, $branch = '')
    {
        $objects = $this->objectModel->saveCommit($repoID, $logs, $version, $branch = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveOneCommitTest($repoID, $commit, $version, $branch = '')
    {
        $objects = $this->objectModel->saveOneCommit($repoID, $commit, $version, $branch = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveExistCommits4BranchTest($repoID, $branch)
    {
        $objects = $this->objectModel->saveExistCommits4Branch($repoID, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateCommitCountTest($repoID, $count)
    {
        $objects = $this->objectModel->updateCommitCount($repoID, $count);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUnsyncedCommitsTest($repo)
    {
        $objects = $this->objectModel->getUnsyncedCommits($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPreAndNextTest($repo, $entry, $revision = 'HEAD', $fileType = 'dir', $method = 'view')
    {
        $objects = $this->objectModel->getPreAndNext($repo, $entry, $revision, $fileType, $method);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createLinkTest($method, $params = '', $viewType = '', $onlybody = false)
    {
        $objects = $this->objectModel->createLink($method, $params, $viewType, $onlybody);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setBackSessionTest($type = 'list', $withOtherModule = false)
    {
        $objects = $this->objectModel->setBackSession($type, $withOtherModule);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setRepoBranchTest($branch)
    {
        $objects = $this->objectModel->setRepoBranch($branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function markSyncedTest($repoID)
    {
        $objects = $this->objectModel->markSynced($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function fixCommitTest($repoID)
    {
        $objects = $this->objectModel->fixCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
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

    public function isBinaryTest($content, $suffix = '')
    {
        $objects = $this->objectModel->isBinary($content, $suffix);

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
        $objects = $this->objectModel->replaceCommentLink($comment);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function addLinkTest($comment, $type)
    {
        $rules     = $this->objectModel->processRules();
        $objectReg = '/' . $rules[$type . 'Reg'] . '/i';
        if(preg_match_all($objectReg, $comment, $result))
        {
            $links = $this->objectModel->addLink($result, $type);
            foreach($links as $link) return $link;
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

    public function getBugProductsAndExecutionsTest($bugs)
    {
        $objects = $this->objectModel->getBugProductsAndExecutions($bugs);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildURLTest($methodName, $url, $revision, $scm = 'svn')
    {
        $objects = $this->objectModel->buildURL($methodName, $url, $revision, $scm);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processGitServiceTest($repo)
    {
        $objects = $this->objectModel->processGitService($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoListByClientTest($gitlabID, $projectID = 0)
    {
        $objects = $this->objectModel->getRepoListByClient($gitlabID, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function handleWebhookTest($event, $token, $data, $repo)
    {
        $objects = $this->objectModel->handleWebhook($event, $token, $data, $repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getGitlabProductsByProjectsTest($projectIDs)
    {
        $objects = $this->objectModel->getGitlabProductsByProjects($projectIDs);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function syncCommitTest($repoID, $branchID)
    {
        $objects = $this->objectModel->syncCommit($repoID, $branchID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionPairsTest($product, $branch = 0)
    {
        $objects = $this->objectModel->getExecutionPairs($product, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCloneUrlTest($repo)
    {
        $objects = $this->objectModel->getCloneUrl($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
