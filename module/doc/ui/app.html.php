<?php
declare(strict_types=1);
/**
 * The app view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

zui::docApp
(
    set::_class('shadow rounded ring canvas'),
    set::_style(array('height' => 'calc(100vh - 72px)')),
    set::spaceType($type),
    set::spaceID($spaceID),
    set::fetcher(createLink('doc', 'ajaxGetSpaceData', 'type={spaceType}&spaceID={spaceID}')),
    set::docFetcher(createLink('doc', 'ajaxGetDoc', 'docID={docID}')),
    set::width('100%'),
    set::height('100%')
);
