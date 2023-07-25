<?php
declare(strict_types=1);
/**
 * The ajaxGetRecommendTree view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($data as $recommendPrivs)
{
    checkboxGroup
        (
            set::title(array('text' => $recommendPrivs['title'], 'id' => "recommendPrivs[{$recommendPrivs['id']}]", 'name' => 'recommendPrivs[]', 'data-id' => $recommendPrivs['id'], 'data-has-children' => !empty($recommendPrivs['children']))),
            !empty($recommendPrivs['children']) ? set::items($recommendPrivs['children']) : null,
        );
}
