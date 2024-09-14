<?php
declare(strict_types=1);
/**
 * The browse view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

$statusMap  = array();
$canInstall = hasPriv('instance', 'manage');
if(!$canInstall) $config->space->dtable->fieldList['actions']['menu'] = array('visit');

foreach($instances as $instance) if('store' === $instance->type) $statusMap[$instance->id] = $instance->status;
jsVar('statusMap', $statusMap);
jsVar('idList',    array_keys($statusMap));
jsVar('inQuickon', $config->inQuickon);

$this->loadModel('instance');
$instances = initTableData($instances, $config->space->dtable->fieldList, $this->instance);

featureBar
(
    set::current($browseType),
    set::linkParams("spaceID=&browseType={key}")
);

$app->loadLang('solution');
toolBar
(
    $config->inQuickon && $solutionID && hasPriv('install', 'progress') ? item(set(array
    (
        'text'            => $lang->solution->progress,
        'icon'            => 'spinner-indicator',
        'class'           => 'ghost',
        'url'             => createLink('install', 'progress', "solutionID=$solutionID&install=true"),
        'data-size'       => array('width' => '900', 'height' => '500'),
        'data-class-name' => 'install-progress',
        'data-id'         => 'installProgress',
        'data-toggle'     => 'modal'
    ))) : null,
    $config->inQuickon && $canInstall ? item(set(array
    (
        'text'  => $lang->store->cloudStore,
        'icon'  => 'program',
        'class' => 'btn ghost',
        'url'   => createLink('store', 'browse')
    ))) : null,
    $canInstall ? item(set(array
    (
        'text'  => $lang->space->install,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('space', 'createApplication')
    ))) : null
);

$langConfirmUninstal          = $lang->instance->notices['confirmUninstall'];
$langConfirmUninstallStoreApp = $lang->instance->notices['confirmUninstallStoreApp'];
dtable
(
    set::actionItemCreator(jsRaw(<<<JS
        (item, info) => {
            if(item.url)
            {
                if(typeof item.url == 'string') item.url = zui.formatString(item.url, info.row.data);
                else item.url.params = zui.formatString(item.url.params, info.row.data);
            }
            if(info.row.data.type != 'store' && item.icon == 'trash')
            {
                item['data-confirm']['message'] = '$langConfirmUninstal';
                Object.assign(item, item);
                return item;
            }
            if(info.row.data.type == 'store' && item.icon == 'trash')
            {
                item['data-confirm']['message'] = '$langConfirmUninstallStoreApp';
                Object.assign(item, item);
                return item;
            }
            return item;
        }
    JS)),
    set::userMap($users),
    set::cols($config->space->dtable->fieldList),
    set::data($instances),
    set::onRenderCell(jsRaw('window.renderInstanceList')),
    set::sortLink(createLink('space', 'browse', "spaceID=&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBys($orderBy),
    set::footPager(usePager())
);

a(setStyle('display', 'none'), setID('editLinkContainer'), setData('toggle', 'modal'));
