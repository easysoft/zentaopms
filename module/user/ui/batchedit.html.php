<?php
declare(strict_types=1);
/**
 * The batchCreate view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items['id'] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px');
$items = array_merge($items, $config->user->form->batchEdit);
$items['dept']['items']    = $depts;
$items['visions']['items'] = $visionList;

formBatchPanel
(
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::title($lang->user->batchEdit),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($users)),
    div
    (
        setClass('form-grid'),
        formGroup
        (
            setClass('flex verify-box'),
            set::width('400px'),
            set::label($lang->user->verifyPassword),
            set::labelClass('w-10 mr-2'),
            set::control('password'),
            set::name('verifyPassword'),
            set::value(''),
            set::required(true)
        )
    )
);
formHidden('verifyRand', $rand);

render();
