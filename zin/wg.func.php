<?php
/**
 * The wg helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'helper.php';
require_once 'h5.func.php';

function hx()
{

}

function btn()  {return createWg('btn', func_get_args());}
function page() {return createWg('page', func_get_args());}
