<?php
class region
{
    public $layout = array();

    public function __set($attr, $value)
    {
        $this->layout[$attr] = $value;
    }
}

class page
{
    public $top;
    public $left;
    public $right;
    public $bottom;

    public function __construct($layout)
    {
        global $app, $config;
        $this->app    = $app;
        $this->view   = $app->control->view;
        $this->config = $config;
        $this->cookie = $app->cookie;

        $this->top    = new region();
        $this->left   = new region();
        $this->right  = new region();
        $this->bottom = new region();
    }

    /**
     * 获取某一个视图文件的扩展。
     * Get the extension file of an view.
     *
     * @param  string $viewFile
     * @access public
     * @return string|bool  If extension view file exists, return the path. Else return fasle.
     */
    public function getExtViewFile($viewFile)
    {
        return $this->app->control->getExtViewFile($viewFile);
    }

    /**
     * 加载指定模块的model文件。
     * Load the model file of one module.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    The app name, if empty, use current app's name.
     * @access public
     * @return object|bool 如果没有model文件，返回false，否则返回model对象。If no model file, return false, else return the model object.
     */
    public function loadModel($moduleName = '', $appName = '')
    {
        return $this->app->control->loadModel($moduleName, $appName);
    }

    public function x($page = 'list')
    {
        extract((array)$this->app->control->view);

        ob_start();

        /* Header. */
        if(isset($hookFiles)) foreach($hookFiles as $hookFile) if(file_exists($hookFile)) include $hookFile;
        include $this->app->getModuleRoot() . 'common' . DS . 'view' . DS . 'header.html.php';

        /* Body. */
        if($page == 'list')
        {
            if(!empty($this->top->layout))
            {
                echo '<div id="mainMenu">';
                foreach($this->top->layout as $key => $block) $block->x();
                echo '</div>';
            }

            echo '<div id="mainContent" class="clearfix">';
            if(!empty($this->left->layout))
            {
                echo '<div id="sidebar" class="side-col">';
                foreach($this->left->layout as $key => $block) $block->x();
                echo '</div>';
            }

            if(!empty($this->right->layout))
            {
                echo '<div class="main-col">';
                foreach($this->right->layout as $key => $block) $block->x();
                echo '</div>';
            }
            echo '</div>';
        }
        elseif($page == 'create')
        {
            echo '<div id="mainContent" class="main-content">';
            if(!empty($this->right->layout))
            {
                echo '<div class="center-block">';
                foreach($this->right->layout as $key => $block) $block->x();
                echo '</div>';
            }
            echo '</div>';
        }

        /* Footer. */
        include $this->app->getModuleRoot() . 'common' . DS . 'view' . DS . 'footer.html.php';

        $output .= ob_get_contents();
        ob_end_clean();

        echo $output;
    }
}

function page($layout)
{
    return new page($layout);
}
