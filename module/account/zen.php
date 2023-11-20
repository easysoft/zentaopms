<?php
declare(strict_types=1);
/**
 * The zen file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     account
 * @link        https://www.zentao.net
 */
class accountZen extends account
{
    /**
     * Build account data for create method.
     *
     * @access protected
     * @return object
     */
    protected function buildDataForCreate(): object
    {
        return form::data()->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();
    }

    /**
     * Build account data for edit method.
     *
     * @access protected
     * @return object
     */
    protected function buildDataForEdit(): object
    {
        return form::data()->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();
    }
}

