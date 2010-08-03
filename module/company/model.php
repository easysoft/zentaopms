<?php
/**
 * The model file of company module of ZenTaoMS.
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
 * @package     company
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php
class companyModel extends model
{
    /* 设置菜单。*/
    public function setMenu($dept = 0)
    {
        common::setMenuVars($this->lang->company->menu, 'name', array($this->app->company->name));
        common::setMenuVars($this->lang->company->menu, 'addUser', array($dept));
    }

    /* 获得公司列表。*/
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
    }

    /* 获得第一个公司。*/
    public function getFirst()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->orderBy('id')->limit(1)->fetch();
    }
    
    /**
     * 通过域名查找公司信息。
     * 
     * @param   string  $domain     访问的域名，如果为空，则取HTTP_HOST变量。
     * @access  public
     * @return  object
     */
    public function getByDomain($domain = '')
    {
        if(empty($domain)) $domain = $_SERVER['HTTP_HOST'];
        return $this->dao->findByPMS($domain)->from(TABLE_COMPANY)->fetch();
    }

    /* 通过id获取公司信息。*/
    public function getByID($companyID = '')
    {
        return $this->dao->findById((int)$companyID)->from(TABLE_COMPANY)->fetch();
    }

    /* 新增一个公司。*/
    public function create()
    {
        $company = fixer::input('post')->get();
        $this->dao->insert(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->create->requiredFields, 'notempty')
            ->batchCheck('name,pms', 'unique')
            ->exec();
    }

    /* 更新一个公司信息。*/
    public function update()
    {
        $company   = fixer::input('post')->get();
        $companyID = $this->app->company->id;
        $this->dao->update(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->edit->requiredFields, 'notempty')
            ->batchCheck('name,pms', 'unique', "id != '$companyID'")
            ->where('id')->eq($companyID)
            ->exec();
    }
    
    /* 删除一个公司。*/
    public function delete($companyID)
    {
        return $this->dao->delete()->from(TABLE_COMPANY)->where('id')->eq((int)$companyID)->limit(1)->exec();
    }
}
