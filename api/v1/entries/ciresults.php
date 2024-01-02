 <?php
/**
 * The ci results entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class ciresultsEntry extends entry
{
    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $control = $this->loadController('ci', 'commitResult');
        $control->commitResult();

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        return $this->send(201, array());
    }
}
