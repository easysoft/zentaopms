<?php
/**
 * The zen file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
class aiZen extends ai
{
    /**
     * Get post data from form post or JSON request body.
     *
     * @param  string $error
     * @access protected
     * @return mixed
     */
    protected function getPostData(&$error = '')
    {
        if(!empty($_POST)) return fixer::input('post')->get();

        $input = file_get_contents('php://input');
        $data  = json_decode($input);

        if(json_last_error() === JSON_ERROR_NONE) return $data;

        $error = sprintf($this->lang->ai->jsonParseFail, json_last_error_msg());
        return null;
    }
}
