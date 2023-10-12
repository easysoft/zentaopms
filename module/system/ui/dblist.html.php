<?php
declare(strict_types=1);
/**
 * The dblist view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$config->system->dtable->dbList->fieldList['actions']['list']['dblist']['url'] = 'javascript:manageDb("{name}", "{db_type}", "{namespace}")';

$dbList = initTableData($dbList, $config->system->dtable->dbList->fieldList);

panel
(
    set::size('lg'),
    set::title($lang->system->dbList),
    div
    (
        setStyle('width', '66.6%'),
        dtable
        (
            set::cols($config->system->dtable->dbList->fieldList),
            set::data($dbList),
            set::onRenderCell(jsRaw('window.renderDbList'))
        ),
    ),
);

render();

