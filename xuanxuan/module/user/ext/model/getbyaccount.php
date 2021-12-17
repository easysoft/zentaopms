<?php 
/**
 * Get user by his account.
 *
 * @param mixed $account
 * @access public
 * @return object           the user.
 */
public function getByAccount($account)
{
    return $this->dao->select('*')->from(TABLE_USER)
        ->beginIF(validater::checkEmail($account))->where('email')->eq($account)->fi()
        ->beginIF(!validater::checkEmail($account))->where('account')->eq($account)->fi()
        ->andWhere('deleted')->eq('0')
        ->fetch();
}