<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class demandLifeInfo extends wg
{
    protected static array $defineProps = array
    (
        'demand' => '?object', // 当前需求。
    );

    protected function getItems(): array
    {
        global $lang;

        $demand = $this->prop('demand', data('demand'));
        if(!$demand) return array();

        $users = data('users');

        $reviewerList = '';
        if($demand->reviewer)
        {
            foreach($demand->reviewer as $reviewer) $reviewerList .= zget($users, $reviewer) . ' ';
        }

        $items = array();
        $items[$lang->demand->createdBy]    = zget($users, $demand->createdBy)  . $lang->at . $demand->createdDate;
        $items[$lang->demand->assignedTo]   = zget($users, $demand->assignedTo) . $lang->at . (helper::isZeroDate($demand->assignedDate) ? '' : $demand->assignedDate);
        $items[$lang->demand->reviewer]     = $reviewerList;
        $items[$lang->demand->reviewedDate] = helper::isZeroDate($demand->reviewedDate) ? '' : $demand->reviewedDate;
        $items[$lang->demand->closedBy]     = zget($users, $demand->closedBy);
        $items[$lang->demand->closedReason] = zget($lang->demand->reasonList, $demand->closedReason, '');
        $items[$lang->demand->lastEditedBy] = zget($users, $demand->lastEditedBy) . $lang->at . helper::isZeroDate($demand->lastEditedDate) ? '' : $demand->lastEditedDate;

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('demand-life-info'),
            set::items($this->getItems())
        );
    }
}
