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

    /**
     * 构造待创建的发布数据。
     * Build the release data to be create.
     *
     * @param  int          $productID
     * @param  int          $branch
     * @param  int          $projectID
     * @access protected
     * @return object|false
     */
    protected function buildReleaseForCreate(int $productID, int $branch, int $projectID = 0): object|false
    {
        $productID = $this->post->product ? $this->post->product : $productID;
        $branch    = $this->post->branch ? $this->post->branch : $branch;

        $release = form::data()
            ->add('product', (int)$productID)
            ->add('branch',  (int)$branch)
            ->setIF($projectID, 'project', $projectID)
            ->setIF($this->post->build === false, 'build', 0)
            ->get();

        /* Check build if build is required. */
        if(strpos($this->config->release->create->requiredFields, 'build') !== false && empty($release->build))
        {
            dao::$errors['build'] = sprintf($this->lang->error->notempty, $this->lang->release->build);
            return false;
        }

        return $release;
    }
}

