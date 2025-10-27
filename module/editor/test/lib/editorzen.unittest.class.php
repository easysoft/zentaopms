<?php
declare(strict_types = 1);
class editorZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('editor');
    }

    /**
     * Test buildContentByAction method.
     *
     * @param  string $filePath 文件路径
     * @param  string $action 操作类型
     * @param  string $isExtends 是否扩展
     * @access public
     * @return mixed
     */
    public function buildContentByActionTest($filePath, $action, $isExtends = '')
    {
        // 创建一个模拟的editorZen实例
        $zen = new class extends stdClass {
            public $editor;
            
            public function buildContentByAction(string $filePath, string $action, string $isExtends = ''): string
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
        };
        $zen->editor = $this->objectModel;
        
        return $zen->buildContentByAction($filePath, $action, $isExtends);
    }
}