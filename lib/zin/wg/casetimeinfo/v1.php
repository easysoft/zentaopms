<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class caseTimeInfo extends wg
{
    protected static array $defineProps = array
    (
        'case'  => '?object',   // 当前用例。
        'users' => '?array'     // 用户列表。
    );

    protected function getReviewedBy($users, $case): string
    {
        $reviewedBy = '';
        foreach(explode(',', $case->reviewedBy) as $account)
        {
            $reviewedBy .= ' ' . zget($users, trim($account));
        }
        $reviewedBy = trim($reviewedBy);
        return $reviewedBy;
    }

    protected function getItems(): array
    {
        global $lang;

        $case = $this->prop('case', data('case'));
        if(!$case) return array();

        $users = $this->prop('users', data('users'));

        $items = array();
        $items[$lang->testcase->openedBy]      = zget($users, $case->openedBy) . $lang->at . $case->openedDate;
        $items[$lang->testcase->reviewedBy]    = $this->getReviewedBy($users, $case);
        $items[$lang->testcase->reviewedDate]  = !empty($case->reviewedBy)   ? $case->reviewedDate : '';
        $items[$lang->testcase->lblLastEdited] = !empty($case->lastEditedBy) ? zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate : '';

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('case-time-info'),
            set::items($this->getItems())
        );
    }
}
