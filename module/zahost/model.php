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
     * Get host by id.
     *
     * @param  int    $hostID
     * @access public
     * @return object
     */
    public function getById($hostID)
    {
        return $this->dao->select('*')->from(TABLE_ZAHOST)->where('id')->eq($hostID)->fetch();
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
     * Create vm template.
     *
     * @access public
     * @return void
     */
    public function createTemplate()
    {
        $template = fixer::input('post')
            ->setIF($this->post->diskSize > 0, 'diskSize', $this->post->diskSize * 1024)
            ->get();

        $this->dao->insert(TABLE_VMTEMPLATE)->data($template)
            ->batchCheck($this->config->zahost->createTemplate->requiredFields, 'notempty')
            ->batchCheck('cpuCoreNum,diskSize,memorySize', 'gt', 0)
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $templateID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('vmtemplate', $templateID, 'Created');
    }
}
