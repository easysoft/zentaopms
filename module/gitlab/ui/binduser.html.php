<?php
declare(strict_types=1);
/**
 * The bind user file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */

namespace zin;

/* zin: Define the set::module('compile') feature bar on main menu. */
featureBar
(
    to::leading(array(backBtn(set::icon('back'), set::className('secondary'), $lang->goback))),
    set::current($type),
    set::link($this->createLink('gitlab', 'binduser', "gitlabID=$gitlabID&type={key}"))
);

/* zin: Define the toolbar on main menu. */
toolbar();

jsVar('zentaoUsers', $zentaoUsers);
jsVar('gitlabID', $gitlabID);
jsVar('type', $type);
$config->gitlab->dtable->bindUser->fieldList['gitlabEmail']['onRenderCell'] = jsRaw('renderGitlabUser');
$config->gitlab->dtable->bindUser->fieldList['zentaoUsers']['controlItems'] = $userPairs;
form
(
    setID('bindForm'),
    setClass('mb-4 h-full'),
    set::action(createLink('gitlab', 'bindUser', "gitlabID={$gitlabID}")),
    set::actions(array()),
    on::change('input[name^="zentaoUsers"]', 'setUserEmail'),
    dtable
    (
        set::cols($config->gitlab->dtable->bindUser->fieldList),
        set::data($userList),
        set::plugins(array('form')),
        set::rowHeight(50),
        set::showToolbarOnChecked(false),
        set::footer(array('toolbar')),
        set::rowKey('gitlabID'),
        set::footToolbar(array(
            'className' => 'w-full form-actions form-group no-label',
            'items'     => array(
                array(
                    'text'    => $lang->save,
                    'btnType' => 'primary',
                    'onClick' => jsRaw("bindUser")
                ),
                array(
                    'text'    => $lang->goback,
                    'btnType' => 'info',
                    'onClick' => jsRaw('() => {goBack()}')
                )
            )
        ))
    )
);
