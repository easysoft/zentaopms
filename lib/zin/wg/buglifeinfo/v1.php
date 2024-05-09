<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class bugLifeInfo extends wg
{
    protected static array $defineProps = array
    (
        'bug'      => '?object', // 当前Bug。
        'users'     => '?array'  // 用户列表。
    );

    protected function getItems(): array
    {
        global $lang;

        $bug = $this->prop('bug', data('bug'));
        if(!$bug) return array();

        $users  = $this->prop('users',  data('users'));
        $builds = $this->prop('builds', data('builds'));
        $items  = array();

        $openedBuildList = explode(',', $bug->openedBuild);
        $openedBuildText = '';
        foreach($openedBuildList as $openedBuild)
        {
            if(!$openedBuild) continue;
            $openedBuildText .= zget($builds, $openedBuild) . ' ';
        }

        $items[$lang->bug->openedBy]      = zget($users, $bug->openedBy) . (formatTime($bug->openedDate) ? $lang->at . $bug->openedDate : '');
        $items[$lang->bug->openedBuild]   = trim($openedBuildText);
        $items[$lang->bug->lblResolved]   = zget($users, $bug->resolvedBy) . (formatTime($bug->resolvedDate) ? $lang->at . formatTime($bug->resolvedDate, DT_DATE1) : '');
        $items[$lang->bug->resolvedBuild] = zget($builds, $bug->resolvedBuild);

        $duplicateBugLink = $bug->duplicateBug ? createLink('bug', 'view', "bugID={$bug->duplicateBug}") : '';
        $duplicateBugText = $bug->duplicateBug ? " #{$bug->duplicateBug}: "  . "<a href='{$duplicateBugLink}' data-toggle='modal' data-size='lg'>{$bug->duplicateBugTitle}</a>": '';
        $duplicateBugText = div
        (
            span(zget($lang->bug->resolutionList, $bug->resolution)),
            $bug->duplicateBug ? span(" #{$bug->duplicateBug}: ") : null,
            $bug->duplicateBug ? a(
                $bug->duplicateBugTitle,
                set::href($duplicateBugLink),
                setData('toggle', 'modal'),
                setData('size', 'lg')
            ) : null
        );
        $items[$lang->bug->resolution] = array
        (
            'control' => 'div',
            'content' => $duplicateBugText
        );

        $items[$lang->bug->closedBy]      = zget($users, $bug->closedBy) . (formatTime($bug->closedDate) ? $lang->at . $bug->closedDate : '');
        $items[$lang->bug->lblLastEdited] = zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . (formatTime($bug->lastEditedDate) ? $lang->at . $bug->lastEditedDate : '');

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('bug-life-info'),
            set::items($this->getItems())
        );
    }
}
