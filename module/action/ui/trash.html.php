<?php
declare(strict_types=1);
/**
 * The trash view file of action module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     action
 * @link        https://www.zentao.net
 */
namespace zin;
$lang->action->featureBar['trash']['all'] = $lang->all;

/* Output the objectType order by preferredTypeConfig. */
foreach($preferredTypeConfig as $objectType)
{
    if(in_array($objectType, $preferredType))
    {
        $lang->action->featureBar['trash'][$objectType] = zget($lang->action->objectTypes, $objectType);
        unset($preferredType[$objectType]);
    }
}

/* Output the remaining types which transformed from more type. */
foreach($preferredType as $objectType) $lang->action->featureBar['trash'][$objectType] = zget($lang->action->objectTypes, $objectType);

/* Output the more types. */
if(!empty($moreType))
{
    $lang->action->featureBar['trash']['more'] = $lang->more;
    foreach($moreType as $objectType) $lang->action->moreSelects['trash']['more'][$objectType] = zget($lang->action->objectTypes, $objectType, '');
}
featureBar
(
    set::current($currentObjectType),
    set::linkParams("objectType={key}&type={$type}"),
    $currentObjectType != 'all' ? li(
        searchToggle
        (
            set::module('trash'),
        )
    ) : null
);

toolbar
(
    $type == 'hidden' ? item(set(array
    (
        'icon'  => 'back',
        'text'  => $lang->goback,
        'class' => '',
        'url'   => inlink('trash', "browseType=all&type=all")
    ))) : null,
    $type == 'all' ? item(set(array
    (
        'icon'  => 'eye-off',
        'text'  => $lang->action->dynamic->hidden,
        'class' => 'danger',
        'url'   => inlink('trash', "browseType=all&type=hidden")
    ))) : null,
);

if($currentObjectType != 'execution')                               unset($config->action->dtable->fieldList['project']);
if(strpos(',story,requirement,', ",$currentObjectType,") === false) unset($config->action->dtable->fieldList['product']);
if($currentObjectType != 'task')                                    unset($config->action->dtable->fieldList['execution']);

if($type == 'all') $config->action->dtable->fieldList['actions']['menu'][] = 'hideone';

$trashes = initTableData($trashes, $config->action->dtable->fieldList, $this->action);
dtable
(
    set::cols(array_values($config->action->dtable->fieldList)),
    set::data(array_values($trashes)),
    set::fixedLeftWidth('0.44'),
    set::userMap($users),
    set::footPager(usePager())
);

render();
