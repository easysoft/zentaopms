<?php
declare(strict_types=1);
/**
 * The tao file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
class executionTao extends executionModel
{
    /**
     * 将执行ID保存到session中。
     * Save the execution ID to the session.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function saveSession(int $executionID): void
    {
        $this->session->set('execution', $executionID, $this->app->tab);
        $this->setProjectSession($executionID);
    }
}
