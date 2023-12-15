<?php
declare(strict_types=1);
/**
 * The zen file of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     index
 * @link        https://www.zentao.net
 */
class indexZen extends index
{
    /**
     * 检查是否显示新功能。
     * Check fhow new features or not.
     *
     * @access protected
     * @return bool
     */
    protected function checkShowFeatures(): bool
    {
        if($this->config->edition == 'ipd') return false;

        foreach($this->config->newFeatures as $feature)
        {
            $accounts = zget($this->config->global, 'skip' . ucfirst($feature), '');
            if(strpos(",$accounts,", ",{$this->app->user->account},") === false) return true;
        }
        return false;
    }

    /**
     * 根据对象类型，获取资产库中的该对象的详情页面方法。
     * Get view method for asset lib by object type.
     *
     * @param  int       $objectID
     * @param  string    $objectType
     * @access protected
     * @return string
     */
    protected function getViewMethodForAssetLib(int $objectID, string $objectType): string
    {
        if(!isset($this->config->maxVersion)) return '';

        $table = zget($this->config->objectTables, $objectType, '');
        if(empty($table)) return '';

        $field     = $objectType == 'doc' ? 'assetLibType' : 'lib';
        $objectLib = $this->dao->select($field)->from($table)->where('id')->eq($objectID)->fetch($field);
        if(empty($objectLib)) return '';

        if($objectType == 'doc') return $objectLib == 'practice' ? 'practiceView' : 'componentView';

        $this->app->loadConfig('action');
        return zget($this->config->action->assetViewMethod, $objectType, '');
    }
}

