<?php
declare(strict_types=1);
/**
 * The tao file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     program
 * @link        http://www.zentao.net
 */

class programTao extends programModel
{
    /**
     * 通过项目集ID列表批量获取项目集基本数据。
     * Get program base data with program ID array.
     *
     * @param  array     $programIdList
     * @access protected
     * @return array
     */
    protected function getBaseDataList(array $programIdList): array
    {
        return $this->dao->select('id,name,PM')
            ->from(TABLE_PROGRAM)
            ->where('id')->in($programIdList)
            ->fetchAll('id');
    }
}
