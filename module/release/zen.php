<?php
declare(strict_types=1);
/**
 * The zen file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
class releaseZen extends release
{
    /**
     * 处理发布列表展示数据。
     * Process release list display data.
     *
     * @param  array     $releaseList
     * @access protected
     * @return array
     */
    protected function processReleaseListData(array $releaseList): array
    {
        $releases = array();
        $this->loadModel('project');
        $this->loadModel('execution');
        foreach($releaseList as $release)
        {
            $buildCount = count($release->builds);

            $release->rowspan = $buildCount;
            $release->actions = $this->release->buildActionList($release);

            if(!empty($release->builds))
            {
                foreach($release->builds as $build)
                {
                    $releaseInfo  = clone $release;
                    $moduleName   = $build->execution ? 'build' : 'projectbuild';
                    $canClickable = false;
                    if($moduleName == 'projectbuild' && $this->project->checkPriv($build->project)) $canClickable = true;
                    if($moduleName == 'build' && $this->execution->checkPriv($build->execution))    $canClickable = true;
                    $build->link = $canClickable ? $this->createLink($moduleName, 'view', "buildID={$build->id}") : '';

                    $releaseInfo->build = $build;

                    $releases[] = $releaseInfo;
                }
            }
            else
            {
                $releases[] = $release;
            }

        }

        return $releases;
    }
}

