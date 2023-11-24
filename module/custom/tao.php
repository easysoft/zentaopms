<?php
declare(strict_types=1);
/**
 * The tao file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
class customTao extends customModel
{
    /**
     * 获取更新项目权限的数据。
     * Get data for update project acl.
     *
     * @access protected
     * @return array
     */
    protected function getDataForUpdateProjectAcl(): array
    {
        $projectGroup = $this->dao->select('id,parent,whitelist,acl')->from(TABLE_PROJECT)
            ->where('parent')->ne('0')
            ->andwhere('type')->eq('project')
            ->andWhere('acl')->eq('program')
            ->fetchGroup('parent', 'id');

        $programPM = $this->dao->select("id,PM")->from(TABLE_PROGRAM)
            ->where('id')->in(array_keys($projectGroup))
            ->andWhere('type')->eq('program')
            ->fetchPairs();

        $stakeholders = $this->dao->select('*')->from(TABLE_STAKEHOLDER)
            ->where('objectType')->eq('program')
            ->andWhere('objectID')->in(array_keys($projectGroup))
            ->fetchGroup('objectID', 'user');

        return array($projectGroup, $programPM, $stakeholders);
    }

    /**
     * 获取自定义语言项。
     * Get custom lang.
     *
     * @access protected
     * @return array|false
     */
    protected function getCustomLang(): array|false
    {
        $currentLang   = $this->app->getClientLang();
        $allCustomLang = array();

        try
        {
            $sql  = $this->dao->select('*')->from(TABLE_LANG)->where('`lang`')->in("$currentLang,all")->andWhere('vision')->eq($this->config->vision)->orderBy('lang,id')->get();
            $stmt = $this->app->dbQuery($sql);

            $allCustomLang = array();
            while($row = $stmt->fetch())
            {
                /* Replace common lang for menu. */
                if(strpos($row->module, 'Menu') !== false || strpos($row->section, 'featureBar-') !== false || $row->section == 'mainNav' || strpos($row->section, 'moreSelects-') !== false)
                {
                    $row->value = strtr($row->value, $this->config->custom->commonLang);
                }
                $allCustomLang[$row->id] = $row;
            }
        }
        catch(PDOException $e)
        {
            return false;
        }

        return $allCustomLang;
    }
}
