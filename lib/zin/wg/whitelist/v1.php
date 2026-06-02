<?php
declare(strict_types=1);
/**
 * The whitelist widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

requireWg('userpicker');

class whitelist extends userPicker
{
    protected static array $defaultProps = array(
        'name' => 'whitelist[]'
    );

    protected function created()
    {
        global $app;

        $users = $app->control->loadModel('user')->getPairs('noclosed|nodeleted|all');
        $items = array_map(function($account, $name){return array('text' => $name, 'value' => $account);}, array_keys($users), $users);

        $this->setProp('items', $items);
    }
}
