<?php
declare(strict_types=1);
/**
 * The tao file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testreport
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class testreportTao extends testreportModel
{
    /**
     * 处理搜索查询。
     * Process search query.
     *
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function processSearchQuery(int $queryID = 0): string
    {
        $queryName = 'testreportQuery';
        $formName  = 'testreportForm';

        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($formName, $query->form);
            }
        }
        if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');

        $reportQuery = '(' . $this->session->$queryName;
        $reportQuery .= ')';

        return $reportQuery;
    }
}
