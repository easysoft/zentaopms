<?php
declare(strict_types=1);
/**
 * The browse view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('confirmDeleteTip', $lang->project->confirmDelete);

if($projectType == 'bycard')
{
    include 'browsebycard.html.php';
}
else
{
    include 'browsebylist.html.php';
}
