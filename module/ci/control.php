<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->ci->setMenu();
    }

    /**
     * Build today job.
     * 
     * @access public
     * @return void
     */
    public function buildTodayJob()
    {
        $scheduleJobs = $this->loadModel('integration')->getListByTriggerType('schedule');

        $week = date('w');
        $this->loadModel('compile');
        foreach($scheduleJobs as $job)
        {
            if(strpos($job->atDay, $week) !== false) $this->compile->createByIntegration($job->id);
        }
        echo 'success';
    }

    /**
     * Exec compile.
     * 
     * @access public
     * @return void
     */
    public function exec()
    {
        $compiles = $this->loadModel('compile')->getUnexecutedList();
        foreach($compiles as $compile) $this->compile->execByCompile($compile);

        $integrations = $this->loadModel('integration')->getListByTriggerType('tag');

        $repoIdList = array();
        $repos      = array();
        foreach($integrations as $integration) $repoIdList[$integration->id] = $integration->id;
        if($repoIdList) $repos = $this->loadModel('repo')->getByIdList($repoIdList);

        foreach($integrations as $integration)
        {
            $repo = zget($repos, $integration->repo, null);
            if(empty($repo)) continue;

            $scm      = $repo->SCM == 'Git' ? 'git' : 'svn';
            $savedTag = $this->loadModel($scm)->getSavedTag($repo->id);

            $tags = $this->$scm->getRepoTags($repo, $scm == 'svn' ? $integration->svnDir : '');
            if(!empty($tags))
            {
                $arriveLastTag = false;
                foreach($tags as $tag)
                {
                    /* Get the last build tag position */
                    if($scm == 'git')
                    {
                        if(!empty($savedTag) && !$arriveLastTag) continue;
                        if(!empty($savedTag) && $tag == $savedTag)
                        {
                            $arriveLastTag = true;
                            continue;
                        }
                    }
                    elseif($scm == 'svn')
                    {
                        if(isset($savedTag[$tag])) continue;
                        $tag = rtrim($repo->path , '/') . '/' . trim($integration->svnDir, '/') . '/' . $tag;
                    }

                    $tagData = new stdclass();
                    $tagData->PARAM_TAG = $tag;
                    $this->compile->execByIntegration($integration->id, $tagData);
                }

                if($scm == 'svn') $tag = json_encode($tags);
                $this->$scm->saveLastTag($tag, $repo->id);
            }
        }
        echo 'success';
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return void
     */
    public function checkBuildStatus()
    {
        $this->ci->checkBuildStatus();
        if(dao::isError())
        {
            echo json_encode(dao::getError());
        }
        else
        {
            echo 'success';
        }
    }
}
