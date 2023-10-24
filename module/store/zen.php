<?php
declare(strict_types=1);
/**
 * The zen file of store module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     store
 * @link        https://www.zentao.net
 */
class storeZen extends store
{
    /**
     * 获取已安装的所有应用。
     * Get all installed apps.
     *
     * @param  form      $formData
     * @param  bool      $isPipelineServer
     * @access protected
     * @return object|false
     */
    protected function getInstalledApps(): array
    {
        $installedApps = array();
        $space         = $this->loadModel('space')->defaultSpace($this->app->user->account);
        $instances     = $this->space->getSpaceInstances($space->id, 'all');
        foreach($instances as $instance) $installedApps[] = $instance->appID;

        return $installedApps;
    }
}

