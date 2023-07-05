<?php
include dirname(__FILE__, 4) . DS . 'func.class.php';

/**
 * 产品总数。
 * Count of product.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_product extends func
{
    public $dao = null;

    public function getResult()
    {
        $record = $this->dao->select('count(id) as count')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->fetch();

        return $record->count;
    }
}
