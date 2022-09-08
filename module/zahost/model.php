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
     * Get host by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_ZAHOST)->where('id')->eq($id) ->fetch();
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
            ->batchCheck('cpuCores,instanceNum', 'int')
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
        if(dao::isError())
        {
            return false;
        }

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
     * Translate field name with correct language.
     *
     * @param  array  $errors
     * @access public
     * @return array
     */
    public function translateField($errors)
    {
        foreach($errors as $fieldName => $subErrors)
        {
            foreach($subErrors as $index => $errMsg) $errors[$fieldName][$index] = str_replace($fieldName, $this->lang->zahost->$fieldName, $errMsg);
        }

        return $errors;
    }
}
