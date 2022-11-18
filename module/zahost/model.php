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
            ->setDefault('cpuNumber,cpu,disk,memory', 0)
            ->get();

        $this->dao->table = 'zahost';
        $this->dao->update(TABLE_ZAHOST)->data($hostInfo)
            ->batchCheck($this->config->zahost->create->requiredFields, 'notempty')
            ->batchCheck('cpu,disk', 'gt', 0)
            ->batchCheck('disk,memory', 'float')
            ->autoCheck();
        if(dao::isError()) return false;

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
            return $hostID;
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
            ->batchCheck('disk,memory', 'float');
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
            ->batchCheck('cpu,disk', 'gt', 0)
            ->batchCheck('disk,memory', 'float')
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
            ->setIF($this->post->disk > 0, 'disk', $this->post->disk * 1024)
            ->setDefault('imageName', '')
            ->get();

        $this->dao->insert(TABLE_VMTEMPLATE)->data($template)
            ->batchCheck($this->config->zahost->createtemplate->requiredFields, 'notempty')
            ->batchCheck('cpuCoreNum,disk,memorySize', 'gt', 0)
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
            ->setIF($this->post->disk > 0, 'disk', $this->post->disk * 1024)
            ->get();

        $this->dao->update(TABLE_VMTEMPLATE)->data($templateInfo)
            ->batchCheck($this->config->zahost->edittemplate->requiredFields, 'notempty')
            ->batchCheck('cpuCoreNum,disk,memorySize', 'gt', 0)
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
     * Get image by name.
     *
     * @param  string $imageName
     * @param  int    $hostID
     * @access public
     * @return object
     */
    public function getImageByNameAndHostID($imageName, $hostID)
    {
        return $this->dao->select('*')->from(TABLE_IMAGE)
            ->where('deleted')->eq(0)
            ->andWhere('hostID')->eq($hostID)
            ->andWhere('name')->eq($imageName)->fetch();
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
        $imageList = json_decode(file_get_contents($this->config->zahost->imageListUrl));

        $downloadedImageList = $this->dao->select('*')->from(TABLE_IMAGE)
            ->where('hostID')->eq($hostID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('name');

        foreach($imageList as &$image)
        {
            $downloadedImage = zget($downloadedImageList, $image->name, '');
            if(empty($downloadedImage))
            {
                $image->id     = 0;
                $image->status = '';
            }
            else
            {
                $image->id     = $downloadedImage->id;
                $image->status = $downloadedImage->status;
            }

            $image->hostID = $hostID;
        }

        return $imageList;
    }

    /**
     * create image.
     *
     * @param  int    $hostID
     * @param  string $imageName
     * @access public
     * @return object
     */
    public function createImage($hostID, $imageName)
    {
        $imageList = json_decode(file_get_contents($this->config->zahost->imageListUrl));

        $imageData = new stdclass;
        foreach($imageList  as $item) if($item->name == $imageName) $imageData = $item;

        $imageData->hostID = $hostID;
        $imageData->status = 'created';
        $imageData->osCategory = $imageData->os;
        unset($imageData->os);

        $this->dao->insert(TABLE_IMAGE)->data($imageData, 'desc')->autoCheck()->exec();
        if(dao::isError()) return false;

        $imageID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('image', $imageID, 'Created');

        return $this->getImageByID($imageID);
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
        $host   = $this->getById($image->hostID);
        $apiUrl = 'http://' . $host->address . ':' . $this->config->zahost->defaultPort . '/api/v1/download/add';

        $apiParams['md5']  = $image->md5;
        $apiParams['url']  = $image->address;
        $apiParams['task'] = intval($image->id);

        $response = json_decode(commonModel::http($apiUrl, array($apiParams), array(CURLOPT_CUSTOMREQUEST => 'POST'), array(), 'json'));

        if($response and $response->code == 'success')
        {
            $this->dao->update(TABLE_IMAGE)
                ->set('status')->eq('created')
                ->where('id')->eq($image->id)->exec();
            return true;
        }

        dao::$errors[] = $this->lang->zahost->image->downloadImageFail;
        return false;
    }

    /**
     * Query image download progress.
     *
     * @param  object $image
     * @access public
     * @return string Return Status code.
     */
    public function queryDownloadImageStatus($image)
    {
        $host   = $this->getById($image->hostID);
        $apiUrl = 'http://' . $host->address . ':' . $this->config->zahost->defaultPort . '/api/v1/task/getStatus';

        $result = json_decode(commonModel::http($apiUrl, array(), array(CURLOPT_CUSTOMREQUEST => 'POST'), array(), 'json'));
        if(!$result or $result->code != 'success') return $image->status;

        foreach($result->data as $status => $group)
        {
            $currentTask = null;
            foreach($group as $host)
            {
                if($host->task == $image->id )
                {
                    $currentTask = $host;
                    break;
                }
            }

            if($currentTask)
            {
                $image->rate   = $currentTask->rate;
                $image->status = $status;

                $this->dao->update(TABLE_IMAGE)
                    ->set('osCategory')->eq($image->os)
                    ->set('status')->eq($status)
                    ->set('path')->eq($currentTask->path)
                    ->where('id')->eq($image->id)->exec();

                break;
            }
        }

        return $image;
    }

    /**
     * Query download image status.
     *
     * @param  object $image
     * @access public
     * @return object
     */
    public function downloadImageStatus($image)
    {
        $host      = $this->getById($image->hostID);
        $statusApi = 'http://' . $host->address . '/api/v1/task/status';

        $response = json_decode(commonModel::http($statusApi, array(), array(CURLOPT_CUSTOMREQUEST => 'GET'), array(), 'json'));

        a($response);
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
     * Build test task menu.
     *
     * @param  object $host
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($host, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($host);
    }

    /**
     * Build test task view menu.
     *
     * @param  object $host
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($host)
    {
        if($host->deleted) return '';

        $menu   = '';
        $params = "hostID=$host->hostID";

        $menu .= $this->buildMenu('zahost', 'edit',   $params, $host, 'view');

        $params = "hostID=$host->assetID";
        $menu .= $this->buildMenu('zahost', 'delete', $params, $host, 'view', 'trash', 'hiddenwin');

        return $menu;
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
            if($template->disk > 1024)
            {
                $template->disk = round($template->disk / 1024);
                $template->unit     = zget($this->lang->zahost->unitList, 'GB');
            }
        }
        return $templateList;
    }

    /**
     * Get image pairs by host.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getImagePairs($hostID)
    {
        return $this->dao->select('id,name')->from(TABLE_IMAGE)->where('hostID')->eq($hostID)->andWhere('status')->eq('completed')->fetchPairs();
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

    /**
     * Get service status from ZAgent server.
     *
     * @param  object $host
     * @access public
     * @return array
     */
    public function getServiceStatus($host)
    {
        $result = json_decode(commonModel::http("http://{$host->address}:8086/api/v1/service/check", json_encode(array("services" => "all"))));
        if(empty($result) || $result->code != 'success')
        {
            $result = new stdclass;
            $result->data = $this->lang->zahost->initHost->serviceStatus;
        }

        return $result->data;
    }
}
