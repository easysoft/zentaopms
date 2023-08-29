<?php
declare(strict_types=1);
/**
* The uploadimages file of file module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     file
* @link        https://www.zentao.net
*/

namespace zin;

set::title(array('html' => div(span($lang->uploadImages), span(set::className('text-gray text-sm font-normal'), $lang->uploadImagesTip))));
uploadImgs();

render();
