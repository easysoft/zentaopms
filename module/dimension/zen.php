<?php
declare(strict_types=1);
/**
 * The control file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dimension
 * @link        http://www.zentao.net
 */
class dimensionZen extends dimension
{
    /**
     * 构造1.5级导航的数据。
     * Build the data of 1.5 level navigation.
     *
     * @param  array     $dimensions
     * @param  int       $parentID
     * @access protected
     * @return void
     */
    protected function buildTree(array $dimensions): array
    {
        $result = array();
        foreach($dimensions as $dimension)
        {
            $itemArray = array
            (
                'id'    => $dimension->id,
                'text'  => $dimension->name,
                'keys'  => zget(common::convert2Pinyin(array($dimension->name)), $dimension->name, ''),
            );

            $result[] = $itemArray;
        }
        return $result;
    }

    /**
     * 获取下拉树菜单的链接。
     * Get link for drop tree menu.
     *
     * @param  string    $moduleName
     * @param  string    $methodName
     * @param  int       $programID
     * @param  string    $vars
     * @param  string    $from
     * @access protected
     * @return string
     */
    protected function getLink(string $moduleName, string $methodName, string $dimensionID, string $vars = '', string $from = 'dimension'): string
    {
        return helper::createLink($moduleName, $methodName, "dimensionID={$dimensionID}");
    }
}

