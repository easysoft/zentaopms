<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * The index of pivot, goto project deviation.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('preview'));
    }

    /**
     * Preview a pivot.
     *
     * @param  int    $dimension
     * @param  string $group
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @access public
     * @return void
     */
    public function preview($dimension = 0, $group = '', $module = 'pivot', $method = '', $params = '')
    {
        $dimension = $this->loadModel('dimension')->getDimension($dimension);
        $this->prepare4Preview($dimension, $group, $module, $method, $params);
        $this->display();
    }
}
