<?php
declare(strict_types=1);
/**
 * The view archived card view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

$cardItems = array();
$CRKanban  = !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed');
foreach($cards as $card)
{
    if($card->fromType == '')          $assignedToList = explode(',', $card->assignedTo);
    if($card->fromType == 'execution') $assignedToList = $card->PM;
    if($card->fromType == 'build')     $assignedToList = $card->builder;
    if($card->fromType == 'release' or $card->fromType == 'productplan') $assignedToList = $card->createdBy;
    if($card->fromType == 'ticket')    $assignedToList = $card->assignedTo;

    $members       = '';
    $count         = 0;
    $beginAndEnd   = '';
    $assignedToBox = array();
    if(is_array($assignedToList))
    {
        foreach($assignedToList as $index => $account)
        {
            if(!isset($users[$account]) or !isset($usersAvatar[$account]))
            {
                unset($assignedToList[$index]);
                continue;
            }
            $members .= $users[$account] . ' ';
        }
        $userCount = count($assignedToList);

        if($userCount > 0)
        {
            foreach($assignedToList as $account)
            {
                if($count > 2) continue;
                $assignedToBox[] = userAvatar(set::avatar($usersAvatar[$account]), set::account($account), set::realname($users[$account]), set::size('sm'));
                $count ++;
            }

            if($count > 2) $assignedToBox[] = span(setClass('font-bold'), '...');
        }
    }
    else
    {
        if(isset($usersAvatar[$assignedToList]) and isset($users[$assignedToList]))
        {
            $members .= $users[$assignedToList];
            $assignedToBox[] = userAvatar(set::avatar($usersAvatar[$assignedToList]), set::account($assignedToList), set::realname($users[$assignedToList]), set::size('sm'));
        }
    }

    if($card->begin < '1970-01-01' && $card->end > '1970-01-01')
    {
        $beginAndEnd = date("m/d", strtotime($card->end)) . $lang->kanbancard->deadlineAB;
    }
    else if($card->end < '1970-01-01' && $card->begin > '1970-01-01')
    {
        $beginAndEnd = date("m/d", strtotime($card->begin)) . $lang->kanbancard->beginAB;
    }
    else if($card->begin > '1970-01-01' && $card->end > '1970-01-01')
    {
        $beginAndEnd = date("m-d", strtotime($card->begin)) . ' ~ ' . date("m-d", strtotime($card->end));
    }

    $cardItems[] = div
    (
        setClass('card-list flex'),
        div
        (
            setClass('card'),
            div
            (
                setClass('card-heading'),
                a
                (
                    set
                    (
                        array
                        (
                            'href'        => inlink('viewCard', "cardID=$card->id"),
                            'data-toggle' => 'modal',
                            'data-size'   => 'lg',
                            'class'       => 'card-title'
                        )
                    ),
                    $card->name
                )
            ),
            div
            (
                setClass('card-content'),
                div
                (
                    setClass('flex items-center'),
                    span(setClass("pri-{$card->pri}"), $lang->kanbancard->priList[$card->pri]),
                    span(setClass('date ml-1'), $beginAndEnd),
                    div
                    (
                        setClass('flex-1 flex justify-end'),
                        set::title($members),
                        $assignedToBox
                    )
                )
            ),
            ($kanban->performable and ($card->fromType == 'execution' or empty($card->fromType))) ? div
            (
                setClass('card-footer'),
                div
                (
                    setClass('flex'),
                    div
                    (
                        setClass('circle progress mt-3'),
                        setStyle('width', '80%'),
                        div
                        (
                            setClass('progress-bar'),
                            setStyle('width', $card->progress . '%')
                        )
                    ),
                        div
                        (
                            setClass('mt-2 ml-2'),
                            $card->progress . '%'
                        )
                )
            ) : null
        ),
        div
        (
            setClass('card-action flex-1 flex justify-center items-center col ml-2'),
            (commonModel::hasPriv('kanban', 'restoreCard') and $CRKanban) ? btn
            (
                set
                (
                    array
                    (
                        'class'        => 'btn primary size-sm ajax-submit',
                        'url'          => inlink('restoreCard', "cardID=$card->id"),
                        'data-confirm' => $lang->kanbancard->confirmRestore
                    )
                ),
                $lang->kanban->restore
            ) : null,
            (commonModel::hasPriv('kanban', 'deleteCard') and $CRKanban) ? btn
            (
                set
                (
                    array
                    (
                        'class'        => 'btn size-sm ajax-submit mt-2',
                        'url'          => inlink('deleteCard', "cardID=$card->id"),
                        'data-confirm' => $lang->kanbancard->confirmDelete
                    )
                ),
                $lang->delete
            ) : null
        )
    );
}

panel
(
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            $lang->kanban->archivedCard
        )
    ),
    to::headingActions
    (
        btn
        (
            setClass('closeBtn ghost'),
            'x'
        )
    ),
    div
    (
        setClass('panel-body'),
        $cardItems
    )
);
