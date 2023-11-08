<?php
declare(strict_types=1);
/**
 * The zen file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
class docZen extends doc
{
    /**
     * Process file field for table.
     *
     * @param  array     $files
     * @param  array     $fileIcon
     * @param  array     $sourcePairs
     * @access protected
     * @return arary
     */
    protected function processFiles(array $files, array $fileIcon, array $sourcePairs): array
    {
        $this->loadModel('file');
        foreach($files as $fileID => $file)
        {
            if(empty($file->pathname))
            {
                unset($files[$fileID]);
                continue;
            }

            $file->fileIcon   = isset($fileIcon[$file->id]) ? $fileIcon[$file->id] : '';
            $file->fileName   = str_replace('.' . $file->extension, '', $file->title);
            $file->sourceName = isset($sourcePairs[$file->objectType][$file->objectID]) ? $sourcePairs[$file->objectType][$file->objectID] : '';
            $file->sizeText   = number_format($file->size / 1024, 1) . 'K';

            $imageSize = $this->file->getImageSize($file);
            $file->imageWidth = isset($imageSize[0]) ? $imageSize[0] : 0;
            if($file->objectType == 'requirement')
            {
                $file->objectName = $this->lang->URCommon . ' : ';
            }
            else
            {
                if(!isset($this->lang->{$file->objectType}->common)) $this->app->loadLang($file->objectType);
                $file->objectName = $this->lang->{$file->objectType}->common . ' : ';
            }
        }
        return $files;
    }

    /**
     * 构造大纲的数据。
     * Build the data of outline.
     *
     * @param  int       $topLevel
     * @param  array     $content
     * @param  array     $includeHeadElement
     * @access protected
     * @return array
     */
    protected function buildOutlineList(int $topLevel, array $content, array $includeHeadElement): array
    {
        $preLevel     = 0;
        $preIndex     = 0;
        $parentID     = 0;
        $currentLevel = 0;
        $outlineList  = array();
        foreach($content as $index => $element)
        {
            preg_match('/<(h[1-6])([\S\s]*?)>([\S\s]*?)<\/\1>/', $element, $headElement);

            /* The current element is existed, the element is in the includeHeadElement, and the text in the element is not null. */
            if(isset($headElement[1]) && in_array($headElement[1], $includeHeadElement) && strip_tags($headElement[3]) != '')
            {
                $currentLevel = (int)ltrim($headElement[1], 'h');

                $item = array();
                $item['id']    = $index;
                $item['title'] = strip_tags($headElement[3]);
                $item['hint']  = strip_tags($headElement[3]);
                $item['url']   = '#anchor' . $index;
                $item['level'] = $currentLevel;

                if($currentLevel == $topLevel)
                {
                    $parentID = -1;
                }
                elseif($currentLevel > $preLevel)
                {
                    $parentID = $preIndex;
                }
                elseif($currentLevel < $preLevel)
                {
                    $parentID = $this->getOutlineParentID($outlineList, $currentLevel);
                }

                $item['parent'] = $parentID;

                $preIndex = $index;
                $preLevel = $currentLevel;
                $outlineList[$index] = $item;
            }
        }
        return $outlineList;
    }

    /**
     * 获取大纲的父级ID。
     * Get the parent ID of the outline.
     *
     * @param  array     $outlineList
     * @param  int       $currentLevel
     * @access protected
     * @return int
     */
    protected function getOutlineParentID(array $outlineList, int $currentLevel): int
    {
        $parentID    = 0;
        $outlineList = array_reverse($outlineList, true);
        foreach($outlineList as $index => $item)
        {
            if($item['level'] < $currentLevel)
            {
                $parentID = $index;
                break;
            }
        }
        return $parentID;
    }

    /**
     * 构造大纲的树形结构。
     * Build the tree structure of the outline.
     *
     * @param  array     $outlineList
     * @param  int       $parentID
     * @access protected
     * @return array
     */
    protected function buildOutlineTree(array $outlineList, int $parentID = -1): array
    {
        $outlineTree = array();
        foreach($outlineList as $index => $item)
        {
            if($item['parent'] != $parentID) continue;

            unset($outlineList[$index]);

            $items = $this->buildOutlineTree($outlineList, $index);
            if(!empty($items)) $item['items'] = $items;

            $outlineTree[] = $item;
        }

        return $outlineTree;
    }

    /**
     * 设置文档树默认展开的节点。
     * Set the default expanded nodes of the document tree.
     *
     * @param  int       $libID
     * @param  int       $moduleID
     * @param  string    $objectType mine|product|project|execution|custom
     * @access protected
     * @return array
     */
    protected function getDefacultNestedShow(int $libID, int $moduleID, string $objectType = ''): array
    {
        if(!$libID && !$moduleID) return array();

        $prefix = $objectType == 'mine' ? "0:" : '';
        if($libID && !$moduleID) return array("{$prefix}{$libID}" => true);

        $module = $this->loadModel('tree')->getByID($moduleID);
        $path   = explode(',', trim($module->path, ','));
        $path   = implode(':', $path);
        return array("{$prefix}{$libID}:{$path}" => true);
    }
}
