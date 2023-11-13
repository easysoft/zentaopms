<?php
declare(strict_types=1);
/**
 * The zen file of client module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */
class clientZen extends client
{
    /**
     * 检查是否有权限添加或编辑客户端版本。
     * Check the permission of add or edit client version.
     *
     * @access public
     * @return void
     */
    public function checkSafeFile()
    {
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->app->loadLang('extension');
            $statusFile = str_replace('\\', '/', $statusFile);
            $error      = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);

            if($_POST)
            {
                $error = str_replace("\n", '', $error);
                return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({message: {html: \"{$error}\"}})"));
            }

            $this->view->error = $error;
        }
    }
}
