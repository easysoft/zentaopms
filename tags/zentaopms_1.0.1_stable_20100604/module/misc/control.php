<?php
/**
 * The control file of misc of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
class misc extends control
{
    /* 通过隐藏的iframe定时刷新此页面，保证session不过期。*/
    public function ping()
    {
        die("<html><head><meta http-equiv='refresh' content='300' /></head><body></body></html>");
    }

    /* 显示phpinfo信息。*/
    public function phpinfo()
    {
        die(phpinfo());
    }

    /* 关于禅道。*/
    public function about()
    {
        $this->display();
        exit;
    }
}
