<?php
/**
 * The model file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Jianhua <wangjiahua@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zahostModel extends model
{
    /**
     * Set lang;
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->app->lang->host       = $this->lang->zahost;
        $this->app->lang->vmtemplate = $this->lang->zahost;
    }

    /**
     * Create a host.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $hostInfo = fixer::input('post')
            ->setDefault('cpuNumber,cpuCores,diskSize,memory', 0)
            ->get();

        $this->dao->table = 'zahost';
        $this->dao->update(TABLE_ZAHOST)->data($hostInfo)
            ->batchCheck($this->config->zahost->create->requiredFields, 'notempty')
            ->batchCheck('cpuCores,diskSize,instanceNum', 'gt', 0)
            ->batchCheck('diskSize,memory', 'float')
            ->autoCheck();
        if(dao::isError()) return false;

        if(!preg_match('/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/', $hostInfo->publicIP))
        {
            dao::$errors['publicIP'][] = sprintf($this->lang->zahost->notice->ip, $this->lang->zahost->publicIP);
            return false;
        }

        $this->dao->update(TABLE_ASSET)->data($hostInfo)->check('name', 'unique');
        if(dao::isError()) return false;

        $assetInfo['name']        = $hostInfo->name;
        $assetInfo['type']        = 'zahost';
        $assetInfo['status']      = 'normal';
        $assetInfo['createdBy']   = $this->app->user->account;
        $assetInfo['createdDate'] = helper::now();

        $this->dao->insert(TABLE_ASSET)->data($assetInfo)->autoCheck()->exec();
        if(dao::isError()) return false;

        $hostInfo->assetID = $this->dao->lastInsertID();

        $this->dao->insert(TABLE_ZAHOST)->data($hostInfo, $skipFields='name')->autoCheck()->exec();
        $hostID = $this->dao->lastInsertID();
        if(!dao::isError())
        {
            $this->loadModel('action')->create('zahost', $hostID, 'created');
            return true;
        }

        return false;
    }

    /**
     * Update a host.
     *
     * @param  int    $hostID
     * @access public
     * @return array|bool
     */
    public function update($hostID)
    {
        $oldHost  = $this->getById($hostID);
        $hostInfo = fixer::input('post')->get();

        $this->dao->update(TABLE_ZAHOST)->data($hostInfo)
            ->batchCheck($this->config->zahost->create->requiredFields, 'notempty')
            ->batchCheck('diskSize,memory', 'float');
        if(dao::isError()) return false;

        $assetInfo['name']       = $hostInfo->name;
        $assetInfo['editedBy']   = $this->app->user->account;
        $assetInfo['editedDate'] = helper::now();

        $this->dao->update(TABLE_ASSET)->data($assetInfo)->autoCheck()
            ->batchCheck($this->config->zahost->edit->requiredFields, 'notempty')
            ->where('id')->eq($oldHost->id)
            ->exec();
        if(dao::isError()) return false;

        $this->dao->update(TABLE_ZAHOST)->data($hostInfo, 'name')->autoCheck()
            ->batchCheck('cpuCores,diskSize,instanceNum', 'gt', 0)
            ->batchCheck('diskSize,memory', 'float')
            ->where('id')->eq($hostID)->exec();
        return common::createChanges($oldHost, $hostInfo);
    }

    /**
     * Create vm template.
     *
     * @param  object $host
     * @access public
     * @return void
     */
    public function createTemplate($host)
    {
        $template = fixer::input('post')
            ->setIF($this->post->diskSize > 0, 'diskSize', $this->post->diskSize * 1024)
            ->setDefault('imageName', '')
            ->get();

        $this->dao->insert(TABLE_VMTEMPLATE)->data($template)
            ->batchCheck($this->config->zahost->createtemplate->requiredFields, 'notempty')
            ->batchCheck('cpuCoreNum,diskSize,memorySize', 'gt', 0)
            ->autoCheck();
        if(dao::isError()) return false;

        $template->hostID      = $host->hostID;
        $template->createdBy   = $this->app->user->account;
        $template->createdDate = helper::now();

        $this->dao->insert(TABLE_VMTEMPLATE)->data($template) ->autoCheck()->exec();
        if(dao::isError()) return false;

        $templateID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('vmtemplate', $templateID, 'Created');
    }

    /**
     * Edit template
     *
     * @param  int    $templateID
     * @access public
     * @return bool|object
     */
    public function updateTemplate($templateID)
    {
        $oldHost      = $this->getTemplateById($templateID);
        $templateInfo = fixer::input('post')
            ->setIF($this->post->diskSize > 0, 'diskSize', $this->post->diskSize * 1024)
            ->get();

        $this->dao->update(TABLE_VMTEMPLATE)->data($templateInfo)
            ->batchCheck($this->config->zahost->edittemplate->requiredFields, 'notempty')
            ->batchCheck('cpuCoreNum,diskSize,memorySize', 'gt', 0)
            ->autoCheck();
        if(dao::isError()) return false;

        $templateInfo->editedBy   = $this->app->user->account;
        $templateInfo->editedDate = helper::now();

        $this->dao->update(TABLE_VMTEMPLATE)->data($templateInfo)->autoCheck()
            ->where('id')->eq($oldHost->id)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldHost, $templateInfo);
    }

    /**
     * Get image by ID.
     *
     * @param  int    $imageID
     * @access public
     * @return object
     */
    public function getImageByID($imageID)
    {
        return $this->dao->select('*')->from(TABLE_IMAGE)->where('deleted')->eq(0)->andWhere('id')->eq($imageID)->fetch();
    }

    /**
     * Get image files from ZAgent server.
     *
     * @param  object $hostID
     * @access public
     * @return array
     */
    public function getImageList($hostID, $browseType = 'all', $param = 0, $orderBy = 'id', $pager = null)
    {
        $imageList = $this->dao->select('*')->from(TABLE_IMAGE)
            ->where('hostID')->eq($hostID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $imageList;
    }

    /**
     * create image.
     *
     * @access public
     * @return void
     */
    public function createImage()
    {
        $vmImage = fixer::input('post')
            ->get();

        $this->dao->insert(TABLE_IMAGE)->data($vmImage)
            ->batchCheck($this->config->zahost->createimage->requiredFields, 'notempty')
            ->autoCheck()->exec();
        if(dao::isError()) return false;

        $imageID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('image', $imageID, 'Created');
    }

    /**
     * Send download image command to HOST.
     *
     * @param  object    $image
     * @access public
     * @return bool
     */
    public function downloadImage($image)
    {
        $host = $this->getById($image->hostID);
        $downloadApi = 'http://' . $host->address . '/api/v1/download/add';

        $apiParams['md5']  = $image->md5;
        $apiParams['task'] = 1;
        $apiParams['url']  = $image->address;

        $response = json_decode(commonModel::http($downloadApi, $apiParams));

        if($response->code == 200) return true;

        dao::$errors[] = $response->msg;
        return false;
    }

    /**
     * Get host by id.
     *
     * @param  int    $hostID
     * @access public
     * @return object
     */
    public function getById($hostID)
    {
        return $this->dao->select('*,t1.id as hostID,t2.id as id')->from(TABLE_ZAHOST)->alias('t1')
            ->leftJoin(TABLE_ASSET)->alias('t2')->on('t1.assetID = t2.id')
            ->where('t1.id')->eq($hostID)
            ->fetch();
    }

    /**
     * Get pairs.
     *
     * @param  string  $idFrom
     * @access public
     * @return array
     */
    public function getPairs($idForm = 'asset')
    {
        $field = $idForm == 'asset' ? 't1.id' : 't2.id';
        return $this->dao->select("$field,t1.name")->from(TABLE_ASSET)->alias('t1')
            ->leftJoin(TABLE_ZAHOST)->alias('t2')->on('t1.id = t2.assetID')
            ->where('t1.deleted')->eq('0')
            ->orderBy('`group`')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get host list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 't1.id_desc', $pager = null)
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            /* Concatenate the conditions for the query. */
            if($param)
            {
                $query = $this->loadModel('search')->getQuery($param);
                if($query)
                {
                    $this->session->set('zahostQuery', $query->sql);
                    $this->session->set('zahostForm', $query->form);
                }
                else
                {
                    $this->session->set('zahostQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->zahostQuery == false) $this->session->set('zahostQuery', ' 1 = 1');
            }
            $query = $this->session->zahostQuery;
            $query = str_replace('`id`', 't1.`id`', $query);
            $query = str_replace('`status`', 't2.`status`', $query);
            $query = str_replace('`type`', 't2.`type`', $query);
        }

        return $this->dao->select('*,t2.id as hostID,t1.id as id')->from(TABLE_ASSET)->alias('t1')
            ->leftJoin(TABLE_ZAHOST)->alias('t2')->on('t1.id = t2.assetID')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('zahost')
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get VM template list.
     *
     * @param  int    $hostID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getVmTemplateList($hostID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null)
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            /* Concatenate the conditions for the query. */
            if($param)
            {
                $query = $this->loadModel('search')->getQuery($param);
                if($query)
                {
                    $this->session->set('vmTemplateQuery', $query->sql);
                    $this->session->set('vmTemplateForm', $query->form);
                }
                else
                {
                    $this->session->set('vmTemplateQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->vmTemplateQuery == false) $this->session->set('vmTemplateQuery', ' 1 = 1');
            }
            $query = $this->session->vmTemplateQuery;
        }

        $templateList = $this->dao->select('*')->from(TABLE_VMTEMPLATE)
            ->where('hostID')->eq($hostID)
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        foreach($templateList as $template)
        {
            $template->unit = zget($this->lang->zahost->unitList, 'MB');
            if($template->diskSize > 1024)
            {
                $template->diskSize = round($template->diskSize / 1024);
                $template->unit     = zget($this->lang->zahost->unitList, 'GB');
            }
        }
        return $templateList;
    }

    /**
     * Get template pairs by host.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getTemplatePairs($hostID)
    {
        return $this->dao->select('id,name')->from(TABLE_VMTEMPLATE)->where('hostID')->eq($hostID)->fetchPairs();
    }

    /**
     * Get template by id.
     *
     * @param  int    $templateID
     * @access public
     * @return object
     */
    public function getTemplateByID($templateID)
    {
        return $this->dao->select('*')->from(TABLE_VMTEMPLATE)->where('id')->eq($templateID)->fetch();
    }
}
