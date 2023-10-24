<?php
declare(strict_types=1);
/**
 * The zen file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     jenkins
 * @link        https://www.zentao.net
 */
class jenkinsZen extends jenkins
{
    /**
     * 构建流水线下拉菜单树。
     * Build pipeline dropmenu tree.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    function buildTree(array $tasks): array
    {
        $result = array();
        foreach($tasks as $groupName => $task)
        {
            if(empty($task)) continue;

            $itemArray = array
            (
                'id'    => is_array($task) ? '' : $groupName,
                'text'  => is_array($task) ? urldecode($groupName) : urldecode($task),
                'keys'  => urldecode(zget(common::convert2Pinyin(array($groupName)), $groupName, '')),
            );
            if(is_array($task))
            {
                $itemArray['items'] = $this->buildTree($task);
                $itemArray['type']  = 'folder';
            }

            $result[] = $itemArray;
        }
        return $result;
    }
}

