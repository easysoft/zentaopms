<?php
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        http://www.zentao.net
 */
?>
<?php
class docModel extends model
{
    /**
     * Set menus
     * 
     * @param  array  $libs 
     * @param  int    $libID 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function setMenu($libs, $libID, $extra = '')
    {
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        $selectHtml = "<div class='dropdown'>" . html::a('javascript:;', $libs[$libID] . " <span class='caret'></span>", '', "data-toggle='dropdown'") . "<ul class='dropdown-menu'>";
        foreach ($libs as $key => $value)
        {
            $class = $libID == $key ? 'active' : '';
            $selectHtml .= "<li class='$class'>" . html::a("javascript:switchDocLib(\"$key\", \"$currentModule\", \"$currentMethod\", \"$extra\");", $value) . '</li>';
        }
        $selectHtml .= '</ul></div>';

        common::setMenuVars($this->lang->doc->menu, 'list', $selectHtml);
        foreach($this->lang->doc->menu as $key => $menu)
        {
            if($key != 'list') common::setMenuVars($this->lang->doc->menu, $key, $libID);
        }
    }

    /**
     * Get library by id.
     * 
     * @param  int    $libID 
     * @access public
     * @return object
     */
    public function getLibById($libID)
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * Get libraries.
     * 
     * @access public
     * @return array
     */
    public function getLibs()
    {
        $libs = $this->dao->select('id, name')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->fetchPairs();
        return $this->lang->doc->systemLibs + $libs;
    }

    /**
     * Create a library.
     * 
     * @access public
     * @return void
     */
    public function createLib()
    {
        $lib = fixer::input('post')->get();
        $this->dao->insert(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Update a library.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function updateLib($libID)
    {
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib = fixer::input('post')->get();
        $this->dao->update(TABLE_DOCLIB)
            ->data($lib)
            ->autoCheck()
            ->batchCheck('name', 'notempty')
            ->check('name', 'unique', "id != $libID")
            ->where('id')->eq($libID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldLib, $lib);
    }

    /**
     * Get docs.
     * 
     * @param  int|string   $libID 
     * @param  int          $productID 
     * @param  int          $projectID 
     * @param  int          $module 
     * @param  string       $orderBy 
     * @param  object       $pager 
     * @access public
     * @return void
     */
    public function getDocs($libID, $productID, $projectID, $module, $orderBy, $pager)
    {
        $products = $this->loadModel('product')->getPairs();
        $projects = $this->loadModel('project')->getPairs();
        $keysOfProducts = array_keys($products);
        $keysOfProjects = array_keys($projects);
        $allKeysOfProjects = $keysOfProjects;
        $allKeysOfProjects[] = 0;

        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF(is_numeric($libID))->andWhere('lib')->eq($libID)->fi()
            ->beginIF($libID == 'product')->andWhere('product')->in($keysOfProducts)->andWhere('project')->in($allKeysOfProjects)->fi()
            ->beginIF($libID == 'project')->andWhere('project')->in($keysOfProjects)->fi()
            ->beginIF($productID > 0)->andWhere('product')->eq($productID)->fi()
            ->beginIF($projectID > 0)->andWhere('project')->eq($projectID)->fi()
            ->beginIF((string)$projectID == 'int')->andWhere('project')->gt(0)->fi()
            ->beginIF($module)->andWhere('module')->in($module)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get doc info by id.
     * 
     * @param  int    $docID 
     * @param  bool   $setImgSize 
     * @access public
     * @return void
     */
    public function getById($docID, $setImgSize = false)
    {
        $doc = $this->dao->select('*')
            ->from(TABLE_DOC)
            ->where('id')->eq((int)$docID)
            ->fetch();
        if(!$doc) return false;
        if($setImgSize) $doc->content = $this->loadModel('file')->setImgSize($doc->content);
        $doc->files = $this->loadModel('file')->getByObject('doc', $docID);

        $doc->libName     = '';
        $doc->productName = '';
        $doc->projectName = '';
        $doc->moduleName  = '';
        if($doc->lib)     $doc->libName     = $this->dao->findByID($doc->lib)->from(TABLE_DOCLIB)->fetch('name');
        if($doc->product) $doc->productName = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->project) $doc->projectName = $this->dao->findByID($doc->project)->from(TABLE_PROJECT)->fetch('name');
        if($doc->module)  $doc->moduleName  = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
        return $doc;
    }

    /**
     * Create a doc.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $now = helper::now();
        $doc = fixer::input('post')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', $now)
            ->setDefault('product, project, module', 0)
            ->stripTags($this->config->doc->editor->create['id'], $this->config->allowedTags)
            ->encodeURL('url')
            ->cleanInt('product, project, module')
            ->remove('files, labels')
            ->get();
        $condition = "lib = '$doc->lib' AND module = $doc->module";

        $result = $this->loadModel('common')->removeDuplicate('doc', $doc, $condition);
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $this->dao->insert(TABLE_DOC)
            ->data($doc)
            ->autoCheck()
            ->batchCheck($this->config->doc->create->requiredFields, 'notempty')
            ->check('title', 'unique', $condition)
            ->exec();
        if(!dao::isError())
        {
            $docID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('doc', $docID);
            return array('status' => 'new', 'id' => $docID);
        }
        return false;
    }

    /**
     * Update a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function update($docID)
    {
        $oldDoc = $this->getById($docID);
        $now = helper::now();
        $doc = fixer::input('post')
            ->cleanInt('module')
            ->setDefault('module', 0)
            ->setIF($this->post->lib == 'product', 'project', 0)
            ->setIF(($this->post->lib != 'product' and $this->post->lib != 'project'), 'project', 0)
            ->setIF(($this->post->lib != 'product' and $this->post->lib != 'project'), 'product', 0)
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->encodeURL('url')
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', $now)
            ->remove('comment,files, labels')
            ->get();

        $condition = "lib = '$doc->lib' AND module = $doc->module AND id != $docID";
        $this->dao->update(TABLE_DOC)->data($doc)
            ->autoCheck()
            ->batchCheck($this->config->doc->edit->requiredFields, 'notempty')
            ->check('title', 'unique', $condition)
            ->where('id')->eq((int)$docID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldDoc, $doc);
    }
 
    /**
     * Get docs of a product.
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getProductDocs($productID)
    {
        return $this->dao->select('t1.*, t2.name as module')
            ->from(TABLE_DOC)->alias('t1')
            ->leftjoin(TABLE_MODULE)->alias('t2')->on('t1.module = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id_desc')
            ->fetchAll();
    }

    /**
     * Get docs of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProjectDocs($projectID)
    {
        return $this->dao->findByProject($projectID)->from(TABLE_DOC)->andWhere('deleted')->eq(0)->orderBy('id_desc')->fetchAll();
    }

    /**
     * Get pairs of product modules.
     * 
     * @access public
     * @return array
     */
    public function getProductModulePairs()
    {
        return $this->dao->findByType('productdoc')->from(TABLE_MODULE)->fetchPairs('id', 'name');
    }

    /**
     * Get pairs of project modules.
     * 
     * @access public
     * @return array
     */
    public function getProjectModulePairs()
    {
        return $this->dao->findByType('projectdoc')->from(TABLE_MODULE)->andWhere('type')->eq('projectdoc')->fetchPairs('id', 'name');
    }

    /**
     * Extract css styles for tables created in kindeditor.
     *
     * Like this: <table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
     * 
     * @param  string    $content 
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css = '';
        $rule = '/<table class="ke(.*)" .*/';
        if(preg_match_all($rule, $content, $results))
        {
            foreach($results[0] as $tableLine)
            {
                $attributes = explode(' ', str_replace('"', '', $tableLine));
                foreach($attributes as $attribute)
                {
                    if(strpos($attribute, '=') === false) continue;
                    list($attributeName, $attributeValue) = explode('=', $attribute);
                    $$attributeName = trim(str_replace('>', '', $attributeValue));
                }

                if(!isset($class)) continue;
                $className   = $class;
                $borderSize  = isset($border)      ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css .= ".$className$borderStyle";
                $css .= ".$className td$borderStyle";
            }
        }
        return $css;
    }
}
