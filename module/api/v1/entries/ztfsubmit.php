<?php
/**
 * The host entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class ztfSubmitEntry extends baseEntry
{
    /**
     * Listen ztf task finish submit.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        /* Check authorize. */
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? substr($header['Authorization'], 7) : '';
        if(!$token) return $this->sendError(401, 'Unauthorized');

        $now    = helper::now();

        $this->dao = $this->loadModel('common')->dao;
        $id = $this->dao->select('id')->from(TABLE_ZAHOST)
            ->where('tokenSN')->eq($token)
            ->andWhere('tokenTime')->gt($now)->fi()
            ->fetch('id');
            
        if(!$id) return $this->sendError(400, 'Secret error.');

        $post = file_get_contents('php://input');
        $post = json_decode($post);

        $this->loadModel('testresult');

        if(!empty($post->task)) 
        {
            $post->log = !empty($post->data->log) ? $post->data->log: $post->error;

            $this->app->user = new stdClass;
            $this->app->user->account = '';

            if(!empty($post->data->funcResult))
            {
                $result = $this->loadModel('testtask')->parseZTFFuncResult($post->data->funcResult, "", 0, 0, 0);
            }
            else
            {
                $result = array();
            }

            unset($post->data);
            if(!empty($result['results'][0]))
            {
                $this->dao->update(TABLE_TESTRESULT)
                    ->set('caseResult')->eq($result['results'][0][0]->caseResult)
                    ->set('stepResults')->eq($result['results'][0][0]->stepResults)
                    ->set('ZTFResult')->eq(json_encode($post))
                    ->where('id')->eq($post->task)
                    ->exec();
            }
            else
            {
                $this->dao->update(TABLE_TESTRESULT)
                    ->set('ZTFResult')->eq(json_encode($post))
                    ->set('caseResult')->eq('n/a')
                    ->where('id')->eq($post->task)->exec();
            }
        }

        return $this->sendSuccess(200, 'success');
    }
}
