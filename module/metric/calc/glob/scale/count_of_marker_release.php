<?php
/**
 * 发布里程碑总数。
 * Count of marker release.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_marker_release extends baseCalc
{
    public $dataset = 'getReleases';

    public $fieldList = array('t1.id', 't1.marker');

    public $result = 0;

    public function calculate($data)
    {
        if(!empty($data->marker) and $data->marker == 1) $this->result ++;
    }
}
