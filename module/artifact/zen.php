<?php
declare(strict_types=1);
/**
 * The zen file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
class artifactZen extends artifact
{
    /**
     * 获取节点的面包屑。
     * Get the breadcrumb of the node.
     *
     * @param  object $artifact
     * @param  array $selectPathList
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getBreadCrumbs(object $artifact, array $selectPathList, int $spaceID = 0, int $repoID = 0, string $type = 'space'): array
    {
        $breadCrumbs = array();
        if(empty($selectPathList)) return $breadCrumbs;

        $parentPath = '';
        foreach($selectPathList as $key => $path)
        {
            $parentPath .= $key == 0 ? '/' : '/' . $selectPathList[$key - 1];
            $nodes      = $this->artifact->getArtifactNodes($artifact, '/'. ltrim($parentPath, '/'));
            if(empty($nodes)) continue;
            foreach($nodes as $node)
            {
                $nodePath = helper::safe64Encode($node->path);
                $breadCrumbs[$path][] = array('text' => $node->name, 'value' => $nodePath, 'keys' => $node->name, 'url' => $this->createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath=$nodePath"));
            }
        }

        return $breadCrumbs;
    }
}
