<?php
declare(strict_types=1);
/**
 * The browse view file of client module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */

namespace zin;

$canCreate = hasPriv('client', 'create');

$cols = $config->client->dtable->fieldList;
$data = initTableData($clients, $cols, $this->client);

panel
(
    set::title($lang->client->browseVersion),
    set::shadow(false),
    $canCreate ? toolbar
    (
        btn
        (
            setClass('primary'),
            setData(array('toggle' => 'modal')),
            set::icon('plus'),
            set::url(inlink('create')),
            $lang->client->create
        )
    ) : null,
    dtable
    (
        set::cols($cols),
        set::data($data),
        set::footPager(usePager()),
        set::emptyTip($lang->client->noClient),
        set::createTip($lang->client->create),
        set::createLink($canCreate ? inlink('create') : ''),
        set::createAttr("data-toggle='modal'")
    )
);

render();
