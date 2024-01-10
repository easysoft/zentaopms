<?php
declare(strict_types=1);
/**
 * The view view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

detailHeader
(
    to::prefix(''),
    to::title(entityLabel(set(array('level' => 1, 'text' => $lang->company->view)))),
    hasPriv('company', 'edit') ? to::suffix(
        btn
        (
            setClass('btn primary'),
            set::icon('edit'),
            set::url(inlink('edit')),
            setData(array('toggle' => 'modal', 'size' => 'sm')),
            $lang->edit
        )
    ) : null
);

detailBody
(
    sectionList
    (
        tableData
        (
            item
            (
                set::name($lang->company->name),
                $company->name
            ),
            item
            (
                set::name($lang->company->phone),
                $company->phone
            ),
            item
            (
                set::name($lang->company->fax),
                $company->fax
            ),
            item
            (
                set::name($lang->company->address),
                $company->address
            ),
            item
            (
                set::name($lang->company->zipcode),
                $company->zipcode
            ),
            item
            (
                set::name($lang->company->website),
                $company->website
            ),
            item
            (
                set::name($lang->company->backyard),
                $company->backyard
            ),
            item
            (
                set::name($lang->company->guest),
                zget($lang->company->guestOptions, $company->guest)
            )
        )
    )
);

render();
