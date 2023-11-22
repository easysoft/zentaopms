<?php
declare(strict_types=1);
/**
 * The zen file of editor module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     editor
 * @link        https://www.zentao.net
 */
class editorZen extends editor
{
    protected function buildContentByAction(string $filePath, string $action, string $isExtends = ''): string
    {
        if(empty($filePath)) return '';
        if($action == 'extendModel') return $this->editor->extendModel($filePath);
        if($action == 'newPage')     return $this->editor->newControl($filePath);
        if($action == 'extendControl' && !empty($isExtends)) return $this->editor->extendControl($filePath, $isExtends);

        if(($action == 'edit' or $action == 'override') && file_exists($filePath))
        {
            $fileContent = file_get_contents($filePath);
            if($action == 'override')
            {
                $fileContent = str_replace("'../../", '$this->app->getModuleRoot() . \'', $fileContent);
                $fileContent = str_replace(array('\'./', '"./'), array('\'../../view/', '"../../view'), $fileContent);
            }
            return $fileContent;
        }

        if(strrpos(basename($filePath), '.php') !== false) return "<?php\n";
        return '';
    }
}
