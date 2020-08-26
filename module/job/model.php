<?php
/**
 * The model file of job module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class jobModel extends model
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
        return $this->dao->select('*')->from(TABLE_JOB)->where('id')->eq($id)->fetch();
    }

    /**
     * Get job list.
     * 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as repoName, t3.name as jenkinsName')->from(TABLE_JOB)->alias('t1')
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
        return $this->dao->select('*')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->eq($triggerType)
            ->beginIF($repoIdList)->andWhere('repo')->in($repoIdList)->fi()
            ->fetchAll('id');
    }

    public function getTriggerConfig($job)
    {
          $triggerType = zget($this->lang->job->triggerTypeList, $job->triggerType);
          if($job->triggerType == 'tag')
          {
              if(empty($job->svnDir)) return $triggerType;

              $triggerType = $this->lang->job->dirChange;
              return "{$triggerType}({$job->svnDir})";
          }

          if($job->triggerType == 'commit') return "{$triggerType}({$job->comment})";

          if($job->triggerType == 'schedule')
          {
              $atDay = '';
              foreach(explode(',', $job->atDay) as $day) $atDay .= zget($this->lang->datepicker->dayNames, trim($day), '') . ',';
              $atDay = trim($atDay, ',');
              return "{$triggerType}({$atDay}, {$job->atTime})";
          }
    }

    /**
     * Create job
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $job = fixer::input('post')
            ->setDefault('atDay', '')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('repoType')
            ->get();
        if($job->triggerType == 'schedule') $job->atDay = empty($_POST['atDay']) ? '' : join(',', $this->post->atDay);

        $job->svnDir = '';
        if($job->triggerType == 'tag' and $this->post->repoType == 'Subversion')
        {
            $job->svnDir = array_pop($_POST['svnDir']);
            if($job->svnDir == '/' and $_POST['svnDir']) $job->svnDir = array_pop($_POST['svnDir']);
        }

        $this->dao->insert(TABLE_JOB)->data($job)
            ->batchCheck($this->config->job->create->requiredFields, 'notempty')

            ->batchCheckIF($job->triggerType === 'schedule', "atDay,atTime", 'notempty')
            ->batchCheckIF($job->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF(($this->post->repoType == 'Subversion' and $job->triggerType == 'tag'), "svnDir", 'notempty')

            ->autoCheck()
            ->exec();

        $id = $this->dao->lastInsertId();
        $this->initJob($id, $job, $this->post->repoType);
        return true;
    }

    /**
     * Update job
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function update($id)
    {
        $job = fixer::input('post')
            ->setDefault('atDay', '')
            ->setIF($this->post->triggerType != 'commit', 'comment', '')
            ->setIF($this->post->triggerType != 'schedule', 'atDay', '')
            ->setIF($this->post->triggerType != 'schedule', 'atTime', '')
            ->setIF($this->post->triggerType != 'tag', 'lastTag', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('repoType')
            ->get();
        if($job->triggerType == 'schedule') $job->atDay = empty($_POST['atDay']) ? '' : join(',', $this->post->atDay);

        $job->svnDir = '';
        if($job->triggerType == 'tag' and $this->post->repoType == 'Subversion')
        {
            $job->svnDir = array_pop($_POST['svnDir']);
            if($job->svnDir == '/' and $_POST['svnDir']) $job->svnDir = array_pop($_POST['svnDir']);
        }

        $this->dao->update(TABLE_JOB)->data($job)
            ->batchCheck($this->config->job->edit->requiredFields, 'notempty')

            ->batchCheckIF($job->triggerType === 'schedule', "atDay,atTime", 'notempty')
            ->batchCheckIF($job->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF(($this->post->repoType == 'Subversion' and $job->triggerType == 'tag'), "svnDir", 'notempty')

            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        $this->initJob($id, $job, $this->post->repoType);
        return true;
    }

    /**
     * Init when create or update job.
     * 
     * @param  int    $id 
     * @param  object $job 
     * @param  string $repoType 
     * @access public
     * @return bool
     */
    public function initJob($id, $job, $repoType)
    {
        if(empty($id)) return false;
        if($job->triggerType == 'schedule' and strpos($job->atDay, date('w')) !== false)
        {
            $compiles = $this->dao->select('*')->from(TABLE_COMPILE)->where('job')->eq($id)->andWhere('LEFT(createdDate, 10)')->eq(date('Y-m-d'))->fetchAll();
            foreach($compiles as $compile)
            {
                if(!empty($compile->status)) continue;
                $this->dao->delete()->from(TABLE_COMPILE)->where('id')->eq($compile->id)->exec();
            }
            $this->loadModel('compile')->createByJob($id, $job->atTime, 'atTime');
        }

        if($job->triggerType == 'tag')
        {
            $repo    = $this->loadModel('repo')->getRepoByID($job->repo);
            $lastTag = '';
            if($repoType == 'Subversion')
            {
                $dirs = $this->loadModel('svn')->getRepoTags($repo, $job->svnDir);
                end($dirs);
                $lastTag = current($dirs);
            }
            else
            {
                $tags = $this->loadModel('git')->getRepoTags($repo);
                end($tags);
                $lastTag = current($tags);
            }
            $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($id)->exec();
        }

        return true;
    }

    /**
     * Exec job.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function exec($id)
    {
        $job = $this->dao->select('t1.id,t1.name,t1.repo,t1.jkJob,t1.triggerType,t1.atTime,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_JENKINS)->alias('t2')->on('t1.jkHost=t2.id')
            ->where('t1.id')->eq($id)
            ->fetch();
        if(!$job) return false;

        $build = new stdclass();
        $build->job  = $job->id;
        $build->name = $job->name;

        $url  = $this->loadModel('compile')->getBuildUrl($job);
        $now  = helper::now();
        $data = new stdclass();
        $data->PARAM_TAG = '';
        if($job->triggerType == 'tag')
        {
            $repo    = $this->loadModel('repo')->getRepoById($job->repo);
            $lastTag = '';
            if($repo->SCM == 'Subversion')
            {
                $dirs = $this->loadModel('svn')->getRepoTags($repo, $job->svnDir);
                if($dirs)
                {
                    end($dirs);
                    $lastTag = current($dirs);
                    $lastTag = rtrim($repo->path , '/') . '/' . trim($job->svnDir, '/') . '/' . $lastTag;
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
                $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($job->id)->exec();
                $data->PARAM_TAG = $lastTag;
            }
        }
        elseif($job->triggerType == 'schedule')
        {
            $build->atTime = $job->atTime;
        }

        $build->createdBy   = $this->app->user->account;
        $build->createdDate = $now;
        $build->updateDate  = $now;
        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
        $compileID = $this->dao->lastInsertId();

        $data->ZENTAO_DATA = "compile={$compileID}";
        $compile = new stdclass();
        $compile->queue  = $this->loadModel('ci')->sendRequest($url->url, $data, $url->userPWD);
        $compile->status = $compile->queue ? 'created' : 'create_fail';
        $this->dao->update(TABLE_COMPILE)->data($compile)->where('id')->eq($compileID)->exec();

        $this->dao->update(TABLE_JOB)->set('lastExec')->eq($now)->set('lastStatus')->eq($compile->status)->where('id')->eq($job->id)->exec();

        return $compile->status;
    }
}
