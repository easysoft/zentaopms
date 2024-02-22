<?php
declare(strict_types=1);
/**
 * The releases view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->api->releases);

$releases = initTableData($releases, $config->api->dtable->release->fieldList, $this->api);

dtable
(
    set::cols(array_values($config->api->dtable->release->fieldList)),
    set::data(array_values($releases)),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(inlink('releases', "libID={$libID}&orderBy={name}_{sortType}"))
);

render();
