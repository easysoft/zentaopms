<?php
if(strtolower($actionType) == 'reconnectxuanxuan' or strtolower($actionType) == 'reconnectxuanxuan')
{
    $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($actor)->exec();
}
