<?php
declare(strict_types=1);
/**
 * The view view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

$isInModal = isAjaxRequest('modal');

data('caselib', $lib);
detailHeader
(
    $isInModal ? to::prefix('') : '',
    to::title(entityLabel(set(array('entityID' => $lib->id, 'level' => 1, 'text' => $lib->name))))
);

$commonActions = array();
foreach($config->caselib->actionList as $operate => $settings)
{
    if(!common::hasPriv('caselib', $operate)) continue;

    $commonActions[] = $settings;
}
detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->caselib->legendDesc),
            set::content($lib->desc),
            set::useHtml(true)
        ),
    ),
    history(set::objectID($lib->id)),
    floatToolbar
    (
        !$isInModal ? set::prefix
        (
            array(array('icon' => 'back', 'text' => $lang->goback, 'url' => 'javascript:goBack("execution-task", "execution-task")'))
        ) : '',
        set::suffix($commonActions),
        set::object($lib)
    )
);

render();

