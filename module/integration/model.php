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
    public function getListByTriggerType($triggerType)
    {
        return $this->dao->select('*')->from(TABLE_INTEGRATION)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->eq($triggerType)
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
}
