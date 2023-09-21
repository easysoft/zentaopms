<?php
declare(strict_types=1);
/**
 * The zen file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */
class branchZen extends branch
{
    /**
     * 替换链接中的产品ID和项目ID。
     * Setting parameters for link.
     *
     * @param  string    $module
     * @param  string    $link
     * @param  int       $projectID
     * @param  int       $productID
     * @access protected
     * @return string
     */
    protected function setParamsForLink(string $module, string $link, int $projectID, int $productID): string
    {
        return strpos('programplan', $module) !== false ? sprintf($link, $projectID, $productID, '{id}') : sprintf($link, $productID, '{id}');
    }
}

