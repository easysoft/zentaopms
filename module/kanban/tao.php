<?php
declare(strict_types=1);
/**
 * The tao file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
class kanbanTao extends kanbanModel
{
    /*
     * 创建看板。
     * Create a kanban.
     *
     * @param  object $kanban
     * @access public
     * @return int
     */
    public function createKanban($kanban)
    {
        $this->dao->insert(TABLE_KANBAN)->data($kanban)
             ->autoCheck()
             ->batchCheck($this->config->kanban->create->requiredFields, 'notempty')
             ->checkIF(!$kanban->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
             ->batchCheckIF($kanban->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
             ->checkIF($kanban->fluidBoard && $kanban->minColWidth >= $this->config->minColWidth, 'maxColWidth', 'gt', $kanban->minColWidth)
             ->check('name', 'unique', "space = {$kanban->space}")
             ->exec();
    }
}
