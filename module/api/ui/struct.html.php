<?php
declare(strict_types=1);
/**
 * The struct view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    li(backBtn(setClass('ghost'), set::icon('back'), $lang->goback)),
    div(setClass('divider')),
    li
    (
        set::className('nav-item'),
        $lang->struct->list
    )
);

toolbar
(
    hasPriv('api', 'createStruct') ? item(set(array
    (
        'icon'        => 'plus',
        'text'        => $lang->api->createStruct,
        'class'       => 'primary',
        'url'         => createLink('api', 'createStruct', "libID=$libID"),
    ))) : null
);

$structs = initTableData($structs, $config->api->dtable->struct->fieldList, $this->api);
$cols = array_values($config->api->dtable->struct->fieldList);
$data = array_values($structs);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::footPager(usePager())
);
