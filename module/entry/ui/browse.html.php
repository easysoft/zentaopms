<?php
declare(strict_types=1);
/**
 * The browse view file of entry module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     entry
 * @link        https://www.zentao.net
 */
namespace zin;

toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('entry', 'create')),
            $lang->entry->create
        ),
    )
);

$tableData = initTableData($entries, $this->config->entry->dtable->fieldList, $this->entry);
dtable
(
    set::cols($this->config->entry->dtable->fieldList),
    set::data($tableData),
    set::footPager(usePager()),
);

render();

