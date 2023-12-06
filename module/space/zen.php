<?php
declare(strict_types=1);
/**
 * The zen file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     space
 * @link        https://www.zentao.net
 */
class spaceZen extends space
{
    /**
     * 获取空间下的应用实例。
     * Get space instances.
     *
     * @param  string    $browseType
     * @access protected
     * @return array
     */
    protected function getSpaceInstances(string $browseType = 'all'): array
    {
        $instances = $this->space->getSpaceInstances(0, $browseType);
        foreach($instances as $instance)
        {
            $instance->externalID = 0;
            $instance->orgID      = $instance->id;
            $instance->type       = 'store';

            if(in_array($instance->appName, $this->config->space->zentaoApps))
            {
                $externalApp = $this->space->getExternalAppByApp($instance);
                if($externalApp) $instance->externalID = $externalApp->id;
            }
        }

        $maxID     = 0;
        $pipelines = array();
        if($browseType == 'all' || $browseType == 'running') $pipelines = $this->loadModel('pipeline')->getList('', 'id_desc');
        if(!empty($instances)) $maxID = max(array_keys($instances));
        foreach($pipelines as $key => $pipeline)
        {
            $maxID ++;
            if($pipeline->createdBy == 'system') unset($pipelines[$key]);

            $pipeline->createdAt  = $pipeline->createdDate;
            $pipeline->appName    = $this->lang->space->appType[$pipeline->type];
            $pipeline->status     = 'running';
            $pipeline->type       = 'external';
            $pipeline->externalID = $pipeline->id;
            $pipeline->orgID      = $pipeline->id;
            $pipeline->id         = $maxID;
        }

        return array_merge($instances, $pipelines);
    }
}
