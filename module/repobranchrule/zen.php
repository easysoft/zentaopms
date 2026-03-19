<?php
declare(strict_types=1);
/**
 * The zen file of repobranchrule module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhiYuan Ma <mazhiyuan@chandao.com>
 * @package     repobranchrule
 * @link        https://www.zentao.net
 */
class repobranchruleZen extends repobranchrule
{
    /**
     * 检查规则。
     * Check rules.
     *
     * @param  object $formData
     * @access public
     * @return object|bool
     */
    public function checkRules(object $formData): object|bool
    {
        $allDefault = true;
        foreach($formData as $data)
        {
            if(empty($data)) continue;
            $allDefault = false;
        }

        if($allDefault) return false;

        return $formData;
    }
}
