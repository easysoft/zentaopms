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
            $selectPath = '/' . ltrim($parentPath . '/' . $path, '/');
            foreach($nodes as $node)
            {
                $nodePath = helper::safe64Encode($node->path);
                $breadCrumbs[$selectPath][] = array('text' => $node->name, 'path'=> $node->path, 'value' => $nodePath, 'keys' => $node->name, 'url' => $this->createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath=$nodePath"));
            }
        }

        return $breadCrumbs;
    }

    /**
     * 获取制品库目录结构。
     * Get artifact folder structure.
     *
     * @param  object $artifact
     * @param  string $path
     * @param  string $selectPath
     * @access public
     * @return array
     */
    public function getArtifactTreeData(object $artifact, string $path = '/', string $selectPath = '', int $spaceID = 0, int $repoID = 0, $type = 'space'): array
    {
        $items = array();
        $nodes = $this->artifact->getArtifactNodes($artifact, $path);
        if(empty($nodes)) return $items;

        $items = array();
        if(empty($selectNode)) $selectNode = array();
        foreach($nodes as $node)
        {
            if(!isset($node->metadata)) continue;
            $path = helper::safe64Encode($node->path);

            $item = new stdclass();
            $item->id         = $path;
            $item->name       = $node->name;
            $item->text       = $node->name;
            $item->path       = $node->path;
            $item->format     = $node->format;
            $item->kind       = $node->metadata->type == 'group' ? 'dir' : 'file';
            $item->active     = $node->path == $selectPath;
            $item->artifactID = $artifact->id;
            $item->spaceID    = $spaceID;
            $item->repoID     = $repoID;
            $item->viewType   = $type;
            $item->basePath   = $path;
            $item->entityID   = $node->metadata->entityID;
            $item->className  = 'text-clip';
            $item->hint       = $node->name;
            $item->hover      = true;
            $item->url        = $this->createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$path}");

            if($item->kind == 'dir')
            {
                $baseSelectPath = helper::safe64Encode($selectPath);
                $item->items = array('url' => $this->createLink('artifact', 'ajaxGetFolders', "artifactID={$artifact->id}&path={$path}&selectPath={$baseSelectPath}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"));
            }
            $item->actions = $node->metadata->type == 'asset' ? array() : $this->buildTreeAction($item);
            $items[] = $item;
        }

        return $items;
    }

    /**
     * 获取编辑目录页所属上级选项。
     * Get parent picker items for edit dir page.
     *
     * @param  object $artifact
     * @param  string $excludePath
     * @access public
     * @return array
     */
    public function getParentPickerItems(object $artifact, string $excludePath = ''): array
    {
        $items = array(array('text' => '/', 'value' => '/'));
        if(empty($artifact->id)) return $items;

        $children = $this->buildParentPickerChildren($artifact, '/', $excludePath);
        if(!empty($children)) $items[0]['items'] = $children;

        return $items;
    }

    /**
     * 构建编辑目录页所属上级树。
     * Build parent picker tree for edit dir page.
     *
     * @param  object $artifact
     * @param  string $path
     * @param  string $excludePath
     * @access protected
     * @return array
     */
    protected function buildParentPickerChildren(object $artifact, string $path = '/', string $excludePath = ''): array
    {
        $items = array();
        $nodes = $this->artifact->getArtifactNodes($artifact, $path);
        if(empty($nodes)) return $items;

        foreach($nodes as $node)
        {
            if(empty($node->metadata) || $node->metadata->type != 'group') continue;
            if($excludePath && $node->path == $excludePath) continue;

            $item = array('text' => $node->name, 'value' => $node->metadata->entityID, 'keys' => $node->name);
            $children = $this->buildParentPickerChildren($artifact, $node->path, $excludePath);
            if(!empty($children)) $item['items'] = $children;

            $items[] = $item;
        }

        return $items;
    }

    /**
     * 构建树形菜单按钮。
     * Build tree menu button.
     *
     * @param  object $item
     * @access public
     * @return array
     */
    function buildTreeAction(object $item): array
    {
        $actions = array();
        if(empty($item->format) || empty($item->id)) return $actions;

        $dropdownItems   = array();
        $dropdownItems[] = array('key' => 'addSiblingDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->addSiblingDir, 'url' => $this->createLink('artifact', 'createDir', "artifactID={$item->artifactID}&path={$item->basePath}&isSubDir=0"));
        $dropdownItems[] = array('key' => 'addSubDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->addSubDir, 'url' => $this->createLink('artifact', 'createDir', "artifactID={$item->artifactID}&path={$item->basePath}&isSubDir=1"));
        $dropdownItems[] = array('key' => 'editDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->editDir, 'url' => $this->createLink('artifact', 'editDir', "artifactID={$item->artifactID}&path={$item->basePath}"));
        $dropdownItems[] = array('key' => 'deleteDir', 'innerClass' => 'ajax-submit', 'text' => $this->lang->artifact->deleteDir, 'url' => $this->createLink('artifact', 'deleteDir', "artifactID={$item->artifactID}&entityID={$item->entityID}&path={$item->basePath}"), 'data-confirm' => array('message' => $this->lang->artifact->notice->confirmDeleteDir));

        $actions[] = array
        (
            'key'      => 'more',
            'icon'     => 'ellipsis-v',
            'hint'     => $this->lang->more,
            'type'     => 'dropdown',
            'caret'    => false,
            'trigger'  => 'hover',
            'dropdown' => array('items' => $dropdownItems)
        );

        return $actions;
    }

    /**
     * 通过路径获取节点。
     * Get node by path.
     *
     * @param  object $artifact
     * @param  string $path
     *
     * @access public
     * @return object|array
     */
    public function getNodeByPath(object $artifact, string $path): object|array
    {
        if(empty($path)) return array();

        $parentPath = dirname($path);
        if(empty($parentPath)) return array();

        $nodes = $this->artifact->getArtifactNodes($artifact, $parentPath);
        if(empty($nodes)) return array();

        foreach($nodes as $node)
        {
            if($node->path == $path)
            {
                $node->id       = $node->metadata->id;
                $node->type     = $node->metadata->type;
                $node->nodeID   = isset($node->metadata->nodeID) ? $node->metadata->nodeID : 0;
                $node->entityID = isset($node->metadata->entityID) ? $node->metadata->entityID : 0;
                return $node;
            }
        }

        return array();
    }
}
