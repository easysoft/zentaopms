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
     * 获取制品库选择器树数据。
     * Get artifact Lib picker tree items.
     *
     * @param  string $type
     * @param  string $account
     * @access public
     * @return array
     */
    public function getArtifactLibPickerItems(string $scope, string $type, array $spaces, array $repos): array
    {
        if(empty($spaces)) return array();

        $spaceIDList  = array_keys($spaces);
        $repoIDList   = array_keys($repos);
        $artifactLibs = $this->artifact->getLibListByScope($scope, $type, $spaceIDList, $repoIDList);

        return $this->artifact->buildArtifactLibPickerItems($spaces, $repos, $artifactLibs);
    }

    /**
     * 获取节点的面包屑。
     * Get the breadcrumb of the node.
     *
     * @param  object $artifactLib
     * @param  array $selectPathList
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getBreadCrumbs(object $artifactLib, array $selectPathList, int $spaceID = 0, int $repoID = 0, string $type = 'space'): array
    {
        $breadCrumbs = array();
        if(empty($selectPathList)) return $breadCrumbs;

        $parentPath = '';
        foreach($selectPathList as $key => $path)
        {
            $parentPath .= $key == 0 ? '/' : '/' . $selectPathList[$key - 1];
            $nodes      = $this->artifact->getArtifactLibNodes($artifactLib, '/'. ltrim($parentPath, '/'));
            if(empty($nodes)) continue;
            $selectPath = '/' . ltrim($parentPath . '/' . $path, '/');
            foreach($nodes as $node)
            {
                if(!empty($node->leaf)) continue;
                $nodePath = helper::safe64Encode($node->path);
                $breadCrumbs[$selectPath][] = array('text' => $node->name, 'path'=> $node->path, 'value' => $nodePath, 'keys' => $node->name, 'url' => $this->createLink('artifact', 'view', "artifactLibID={$artifactLib->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath=$nodePath"));
            }
        }

        return $breadCrumbs;
    }

    /**
     * 获取制品库目录结构。
     * Get artifact folder structure.
     *
     * @param  object $artifactLib
     * @param  string $path
     * @param  string $selectPath
     * @access public
     * @return array
     */
    public function getArtifactLibTreeData(object $artifactLib, string $path = '/', string $selectPath = '', int $spaceID = 0, int $repoID = 0, $type = 'space', int $leaf = 0): array
    {
        $items = array();
        $nodes = $this->artifact->getArtifactLibNodes($artifactLib, $path);
        if(empty($nodes)) return $items;

        $privs = array();
        foreach(array('createDir', 'editDir', 'deleteDir') as $action)
        {
            $privs[$action] = common::hasPriv('artifact', $action);
        }

        $items = array();
        foreach($nodes as $node)
        {
            if(!isset($node->metadata)) continue;
            $path = helper::safe64Encode($node->path);
            $nodeLeaf = !empty($node->leaf) ? 1 : 0;

            $item = new stdclass();
            $item->id            = $path;
            $item->name          = $node->format == 'container' && !empty($node->metadata) && $node->leaf ? $node->metadata->name . ':' . $node->metadata->version : $node->name;
            $item->text          = $item->name;
            $item->path          = $node->path;
            $item->format        = $node->format;
            $item->active        = $node->path == $selectPath && $nodeLeaf == $leaf;
            $item->artifactLibID = $artifactLib->id;
            $item->spaceID       = $spaceID;
            $item->repoID        = $repoID;
            $item->viewType      = $type;
            $item->basePath      = $path;
            $item->entityID      = $node->metadata->entityID;
            $item->className     = 'text-clip';
            $item->hint          = $item->name;
            $item->hover         = true;
            $item->url           = $this->createLink('artifact', 'view', "artifactID={$artifactLib->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$path}&leaf={$nodeLeaf}");

            if(!$node->leaf)
            {
                $baseSelectPath = helper::safe64Encode($selectPath);
                $item->items = array('url' => $this->createLink('artifact', 'ajaxGetFolders', "artifactID={$artifactLib->id}&path={$path}&selectPath={$baseSelectPath}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&leaf={$leaf}"));
            }
            $item->actions = $node->metadata->type == 'asset' || $item->format != 'file' ? array() : $this->buildTreeAction($item, $privs);
            $items[] = $item;
        }

        return $items;
    }

    /**
     * 获取编辑目录页所属上级选项。
     * Get parent picker items for edit dir page.
     *
     * @param  object $artifactLib
     * @param  string $excludePath
     * @access public
     * @return array
     */
    public function getParentPickerItems(object $artifactLib, string $excludePath = ''): array
    {
        $items = array(array('text' => '/', 'value' => '/'));
        if(empty($artifactLib->id)) return $items;

        $children = $this->buildParentPickerChildren($artifactLib, '/', $excludePath);
        if(!empty($children)) $items[0]['items'] = $children;

        return $items;
    }

    /**
     * 构建编辑目录页所属上级树。
     * Build parent picker tree for edit dir page.
     *
     * @param  object $artifactLib
     * @param  string $path
     * @param  string $excludePath
     * @access protected
     * @return array
     */
    protected function buildParentPickerChildren(object $artifactLib, string $path = '/', string $excludePath = ''): array
    {
        $items = array();
        $nodes = $this->artifact->getArtifactLibNodes($artifactLib, $path);
        if(empty($nodes)) return $items;

        foreach($nodes as $node)
        {
            if(empty($node->metadata) || $node->metadata->type != 'group') continue;
            if($excludePath && $node->path == $excludePath) continue;

            $item = array('text' => $node->name, 'value' => $node->metadata->entityID, 'keys' => $node->name);
            $children = $this->buildParentPickerChildren($artifactLib, $node->path, $excludePath);
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
     * @param  array $privs
     * @access public
     * @return array
     */
    function buildTreeAction(object $item, array $privs): array
    {
        $actions = array();
        if(empty($item->format) || empty($item->id)) return $actions;

        $dropdownItems   = array();
        if(!empty($privs['createDir'])) $dropdownItems[] = array('key' => 'addSiblingDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->addSiblingDir, 'url' => $this->createLink('artifact', 'createDir', "artifactID={$item->artifactLibID}&path={$item->basePath}&isSubDir=0"));
        if(!empty($privs['createDir'])) $dropdownItems[] = array('key' => 'addSubDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->addSubDir, 'url' => $this->createLink('artifact', 'createDir', "artifactID={$item->artifactLibID}&path={$item->basePath}&isSubDir=1"));
        if(!empty($privs['editDir']) )  $dropdownItems[] = array('key' => 'editDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->editDir, 'url' => $this->createLink('artifact', 'editDir', "artifactID={$item->artifactLibID}&path={$item->basePath}"));
        if(!empty($privs['deleteDir'])) $dropdownItems[] = array('key' => 'deleteDir', 'innerClass' => 'ajax-submit', 'text' => $this->lang->artifact->deleteDir, 'url' => $this->createLink('artifact', 'deleteDir', "artifactID={$item->artifactLibID}&entityID={$item->entityID}&path={$item->basePath}"), 'data-confirm' => array('message' => $this->lang->artifact->notice->confirmDeleteDir));
        if(empty($dropdownItems)) return $actions;

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
     * @param  object $artifactLib
     * @param  string $path
     *
     * @access public
     * @return object|array
     */
    public function getNodeByPath(object $artifactLib, string $path): object|array
    {
        if(empty($path)) return array();

        $parentPath = $this->artifact->parseDirname($path);
        if(empty($parentPath)) return array();

        $nodes = $this->artifact->getArtifactLibNodes($artifactLib, $parentPath);
        if(empty($nodes)) return array();

        foreach($nodes as $node)
        {
            if($node->path == $path)
            {
                $node->id       = empty($node->metadata) && !empty($node->metadata->id) ? $node->metadata->id : $node->metadata->entityID;
                $node->type     = $node->metadata->type;
                $node->nodeID   = isset($node->metadata->nodeID) ? $node->metadata->nodeID : 0;
                $node->entityID = isset($node->metadata->entityID) ? $node->metadata->entityID : 0;
                return $node;
            }
        }

        return array();
    }
}
