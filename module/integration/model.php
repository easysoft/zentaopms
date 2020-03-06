<?php
/**
 * The model file of integration module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     integration
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class integrationModel extends model
{
    /**
     * Get by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_INTEGRATION)->where('id')->eq($id)->fetch();
    }

    /**
     * Get integration list.
     * 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as repoName, t3.name as jenkinsName')->from(TABLE_INTEGRATION)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_JENKINS)->alias('t3')->on('t1.jkHost=t3.id')
            ->where('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get list by triggerType field
     * 
     * @param  string    $triggerType 
     * @access public
     * @return array
     */
    public function getListByTriggerType($triggerType, $repoIdList = array())
    {
        return $this->dao->select('*')->from(TABLE_INTEGRATION)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->eq($triggerType)
            ->beginIF($repoIdList)->andWhere('repo')->in($repoIdList)->fi()
            ->fetchAll('id');
    }

    /**
     * Create integration
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $integration = fixer::input('post')
            ->setDefault('atDay', '')
            ->setIF($this->post->repoType != 'Subversion', 'svnDir', '')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('repoType')
            ->get();
        if($integration->triggerType == 'schedule') $integration->atDay = empty($_POST['atDay']) ? '' : join(',', $this->post->atDay);
        if($integration->triggerType == 'tag' and $this->post->repoType == 'Subversion')
        {
            $integration->svnDir = array_pop($_POST['svnDir']);
            if($integration->svnDir == '/' and $_POST['svnDir']) $integration->svnDir = array_pop($_POST['svnDir']);
        }

        $this->dao->insert(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->create->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule', "atDay,atTime", 'notempty')
            ->batchCheckIF($integration->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnDir", 'notempty')

            ->autoCheck()
            ->exec();

        $id = $this->dao->lastInsertId();
        if($integration->triggerType == 'schedule' and strpos($integration->atDay, date('w')) !== false) $this->loadModel('compile')->createByIntegration($id);
        if($integration->triggerType == 'tag')
        {
            $repo    = $this->loadModel('repo')->getRepoByID($integration->repo);
            $lastTag = '';
            if($this->post->repoType != 'Subversion')
            {
                $dirs = $this->loadModel('svn')->getRepoTags($repo, $integration->svnDir);
                end($dirs);
                $lastTag = current($dirs);
            }
            else
            {
                $tags = $this->loadModel('git')->getRepoTags($repo);
                end($tags);
                $lastTag = current($tags);
            }
            $this->dao->update(TABLE_INTEGRATION)->set('lastTag')->eq($lastTag)->where('id')->eq($id)->exec();
        }
        return true;
    }

    /**
     * Update integration
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function update($id)
    {
        $oldIntegration = $this->getById($id);
        $integration    = fixer::input('post')
            ->setDefault('atDay', '')
            ->setIF($this->post->repoType != 'Subversion', 'svnDir', '')
            ->setIF($this->post->triggerType != 'commit', 'comment', '')
            ->setIF($this->post->triggerType != 'schedule', 'atDay', '')
            ->setIF($this->post->triggerType != 'schedule', 'atTime', '')
            ->setIF($this->post->triggerType != 'tag', 'lastTag', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('repoType')
            ->get();
        if($integration->triggerType == 'schedule') $integration->atDay = empty($_POST['atDay']) ? '' : join(',', $this->post->atDay);
        if($integration->triggerType == 'tag' and $this->post->repoType == 'Subversion')
        {
            $integration->svnDir = array_pop($_POST['svnDir']);
            if($integration->svnDir == '/' and $_POST['svnDir']) $integration->svnDir = array_pop($_POST['svnDir']);
        }

        $this->dao->update(TABLE_INTEGRATION)->data($integration)
            ->batchCheck($this->config->integration->edit->requiredFields, 'notempty')

            ->batchCheckIF($integration->triggerType === 'schedule', "atDay,atTime", 'notempty')
            ->batchCheckIF($integration->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF($this->post->repoType == 'Subversion', "svnDir", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        if($integration->triggerType == 'schedule')
        {
            $week = date('w');
            if($integration->triggerType != $oldIntegration->triggerType or strpos($oldIntegration->atDay, $week) === false)
            {
                if(strpos($integration->atDay, $week) !== false) $this->loadModel('compile')->createByIntegration($integration->id);
            }
        }
        elseif($integration->triggerType == 'tag')
        {
            if($integration->triggerType != $oldIntegration->triggerType or $integration->repo != $oldIntegration->repo)
            {
                $repo    = $this->loadModel('repo')->getRepoByID($integration->repo);
                $lastTag = '';
                if($this->post->repoType == 'Subversion')
                {
                    $dirs = $this->loadModel('svn')->getRepoTags($repo, $integration->svnDir);
                    end($dirs);
                    $lastTag = current($dirs);
                }
                else
                {
                    $tags = $this->loadModel('git')->getRepoTags($repo);
                    end($tags);
                    $lastTag = current($tags);
                }
                $this->dao->update(TABLE_INTEGRATION)->set('lastTag')->eq($lastTag)->where('id')->eq($id)->exec();
            }
        }
        return true;
    }

    /**
     * Exec integration.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function exec($id)
    {
        $integration = $this->dao->select('t1.id,t1.name,t1.repo,t1.jkJob,t1.triggerType,t1.atTime,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password')
            ->from(TABLE_INTEGRATION)->alias('t1')
            ->leftJoin(TABLE_JENKINS)->alias('t2')->on('t1.jkHost=t2.id')
            ->where('t1.id')->eq($id)
            ->fetch();
        if(!$integration) return false;

		$buildUrl = $this->loadModel('compile')->getBuildUrl($integration);
        $build    = new stdclass();
        $build->integration = $integration->id;
        $build->name        = $integration->name;

        $now  = helper::now();
        $data = '';
        if($integration->triggerType == 'tag')
        {
            $repo    = $this->loadModel('repo')->getRepoById($integration->repo);
            $lastTag = '';
            if($repo->SCM == 'Subversion')
            {
                $dirs = $this->loadModel('svn')->getRepoTags($repo, $integration->svnDir);
                if($dirs)
                {
                    end($dirs);
                    $lastTag = current($dirs);
                    $lastTag = rtrim($repo->path , '/') . '/' . trim($integration->svnDir, '/') . '/' . $lastTag;
                }
            }
            else
            {
                $tags = $this->loadModel('git')->getRepoTags($repo);
                if($tags)
                {
                    end($tags);
                    $lastTag = current($tags);
                }
            }

            if($lastTag)
            {
                $build->tag = $lastTag;
                $this->dao->update(TABLE_INTEGRATION)->set('lastTag')->eq($lastTag)->where('id')->eq($integration->id)->exec();

                $data = new stdClass();
                $data->PARAM_TAG = $lastTag;
            }
        }
        elseif($integration->triggerType == 'schedule')
        {
            $build->atTime = $integration->atTime;
        }

        $build->queue       = $this->loadModel('ci')->sendRequest($buildUrl, $data);
        $build->status      = $build->queue ? 'created' : 'create_fail';
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = $now;
        $build->updateDate  = $now;
        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
        $this->dao->update(TABLE_INTEGRATION)->set('lastExec')->eq($now)->set('lastStatus')->eq($build->status)->where('id')->eq($integration->id)->exec();

        return !dao::isError();
    }
}
