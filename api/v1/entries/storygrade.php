<?php
/**
 * The story grade entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class storygradeEntry extends entry
{
    /**
     * GET method.
     * 获取所有层级
     * Get all story grades.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $type   = $this->param('type', '');
        $status = $this->param('status', 'enable');

        $storyModel = $this->loadModel('story');

        /* 如果指定了类型，获取该类型的层级列表 */
        if($type)
        {
            $gradeList = $storyModel->getGradeList($type);
        }
        else
        {
            /* 获取所有类型的层级，按类型分组 */
            $gradeList = array();
            $types = array('story', 'requirement', 'epic');
            foreach($types as $storyType)
            {
                $grades = $storyModel->getGradeList($storyType);
                if(!empty($grades))
                {
                    foreach($grades as $grade)
                    {
                        $gradeList[] = $grade;
                    }
                }
            }
        }

        /* 根据status参数过滤 */
        if($status != 'all' && !empty($gradeList))
        {
            $filteredList = array();
            foreach($gradeList as $grade)
            {
                if(isset($grade->status) && $grade->status == $status)
                {
                    $filteredList[] = $grade;
                }
            }
            $gradeList = $filteredList;
        }

        /* 格式化返回数据 */
        $result = array();
        foreach($gradeList as $grade)
        {
            /* 如果指定了类型，返回数组格式 */
            if($type)
            {
                $result[] = $grade;
            }
            else
            {
                /* 如果未指定类型，按类型分组返回 */
                $gradeType = isset($grade->type) ? $grade->type : '';
                if(!isset($result[$gradeType]))
                {
                    $result[$gradeType] = array();
                }
                $result[$gradeType][] = $grade;
            }
        }

        return $this->send(200, $result);
    }
}