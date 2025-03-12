<?php
declare(strict_types=1);
/**
 * The browse report template view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xie qiyu<xieqiyu@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

if($mode == 'home' || !$libID) include './reporttemplatehome.html.php';
else include './reporttemplatelist.html.php';
