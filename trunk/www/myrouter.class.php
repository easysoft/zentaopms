<?php
/**
 * The router class file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id: myrouter.class.php 1363 2009-09-29 01:19:26Z wwccss $
 * @link        http://www.zentao.cn
 */
/**
 * 从router类中继承，增加了若干和pms应用相关的方法。
 * 
 * @package ZenTaoMS
 */
class myRouter extends router
{
    private $configCached = false;
    private $langCached   = false;

    /* 设置会话期间的company信息。*/
    public function setSessionCompany($company)
    {
        $this->company = $company;
    }

    /* 设置会话期间的用户信息。*/
    public function setSessionUser($user)
    {
        $this->user = $user;
    }
}
