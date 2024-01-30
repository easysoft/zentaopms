<?php
declare(strict_types=1);
/**
 * The setting view file of message module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     message
 * @link        https://www.zentao.net
 */
namespace zin;

/* First row. */
$messageSetting = is_string($config->message->setting) ? json_decode($config->message->setting, true) : $config->message->setting;

$headerThs = array(h::th());
foreach($lang->message->typeList as $type => $typeName)
{
    $headerThs[] = h::th
    (
        checkbox
        (
            on::click('toggleColumnChecked'),
            set::id("type-{$type}"),
            set::name("type-{$type}"),
            set::value(1),
            set::text($typeName)
        )
    );
}

$bodyTrs = array();
foreach($config->message->objectTypes as $objectType => $actions)
{
    $bodyTr = array();
    $bodyTr[] = h::td
    (
        div
        (
            width('150px'),
            checkbox
            (
                on::click('toggleLaneChecked'),
                set::id("objectType-{$objectType}"),
                set::name("objectType-{$objectType}"),
                set::value(1),
                set::text($objectTypes[$objectType])
            )
        )
    );
    foreach($lang->message->typeList as $type => $typeName)
    {
        $cell = array();
        if(isset($config->message->available[$type][$objectType]))
        {
            $availableActions = array();
            foreach($config->message->available[$type][$objectType] as $action)
            {
                if(!isset($objectActions[$objectType][$action])) continue;
                $availableActions[$action] = $objectActions[$objectType][$action];
            }

            $selected = isset($messageSetting[$type]['setting'][$objectType]) ? join(',', $messageSetting[$type]['setting'][$objectType]) : '';
            foreach($availableActions as $key => $value)
            {
                $cell[] = checkbox
                (
                    set::rootClass('w-1/2'),
                    set::id("messageSetting{$type}{$objectType}{$key}"),
                    set::name("messageSetting[$type][setting][$objectType][]"),
                    set::title($value),
                    set::value($key),
                    set::checked(strpos(",{$selected},", ",{$key},") !== false),
                    set::text($value)
                );
            }
            if(isset($config->message->condition[$type][$objectType]))
            {
                $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
                $this->app->loadLang($moduleName);
                foreach(explode(',', $config->message->condition[$type][$objectType]) as $condition)
                {
                    $listKey = $condition . 'List';
                    $list = isset($this->lang->{$moduleName}->{$listKey}) ? $this->lang->{$moduleName}->{$listKey} : $users;
                    $cell[] = picker
                    (
                        set::name("messageSetting[{$type}][condition][{$objectType}][{$condition}][]"),
                        set::items($list),
                        set::value(isset($messageSetting[$type]['condition'][$objectType][$condition]) ? join(',', $messageSetting[$type]['condition'][$objectType][$condition]) : ''),
                        set::multiple(true)
                    );
                }
            }
        }
        $bodyTr[] = h::td
        (
            div
            (
                setClass('flex content-center items-center flex-wrap'),
                $cell
            )
        );
    }
    $bodyTrs[] = h::tr($bodyTr);
}
$bodyTrs[] = h::tr
(
    h::td($lang->message->blockUser),
    h::td
    (
        picker
        (
            set::name('blockUser'),
            set::items($users),
            set::value(isset($config->message->blockUser) ? $config->message->blockUser: ''),
            set::menu(array('checkbox' => true)),
            set::multiple(true)
        )
    )
);

panel
(
    form
    (
        h::table
        (
            setClass('table condensed bordered'),
            h::thead(h::tr($headerThs)),
            h::tbody($bodyTrs)
        )
    )
);

render();

