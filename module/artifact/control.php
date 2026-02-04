<?php
declare(strict_types=1);
/**
 * The control file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     atifact
 * @link        https://www.zentao.net
 */
class artifact extends control
{
    /**
     * 创建制品库。
     * create artifact repo.
     *
     * @param  int $space
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function create(int $space = 0, int $repoID = 0, string $type = 'space')
    {
        if($_POST)
        {
            $formData = form::data($this->config->artifact->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('repoID', $repoID)
                ->add('spaceID', $space)
                ->add('type', $type)
                ->get();

            $id = $this->artifact->create($formData, $type);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('artifact', $id, 'created');
            $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->artifact->create;
        $this->display();
    }
}
