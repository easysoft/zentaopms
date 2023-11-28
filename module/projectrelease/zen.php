<?php
declare(strict_types=1);
class projectreleaseZen extends projectrelease
{
    /**
     * 获取当前项目的所有产品，当前产品，分支，项目
     * Get products of the project and current product, branch, project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    protected function commonAction(int $projectID = 0, int $productID = 0, string $branch = '')
    {
        /* 获取当前项目的所有产品。*/
        /* Get product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) $this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID));

        /* 获取当前的产品。*/
        /*  Get current product. */
        if(!$productID) $productID = key($this->products);
        $product = $this->product->getByID($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID, 'active', $projectID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
    }

    /**
     * 生成关联的需求的 html。
     * Generate story html.
     *
     * @access protected
     * @return void
     */
    protected function generateStoryHtml(): string
    {
        $this->loadModel('story');

        $html = "<h3>{$this->lang->release->stories}</h3>";

        $fields = array('id' => $this->lang->story->id, 'title' => $this->lang->story->title);

        /* 生成表头。*/
        /* Generate thead.*/
        $html .= '<table><tr>';
        foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
        $html .= '</tr>';

        $stories = $this->dbh->query($this->session->storyQueryCondition . " ORDER BY " . strtr($this->session->storyOrderBy, '_', ' '))->fetchAll();
        foreach($stories as $story)
        {
            $story->title = "<a href='" . common::getSysURL() . $this->createLink('story', 'view', "storyID=$story->id") . "' target='_blank'>$story->title</a>";

            $html .= "<tr valign='top'>\n";
            foreach($fields as $fieldName => $fieldLabel) $html .= '<td><nobr>' . zget($story, $fieldName, '') . "</nobr></td>\n";
            $html .= "</tr>\n";
        }
        $html .= '</table>';

        return $html;
    }

    /**
     * 生成 bug 的 html。
     * Generate bug html.
     *
     * @param  string    $type linked|left
     * @access protected
     * @return string
     */
    protected function generateBugHtml($type = 'linked'): string
    {
        $this->loadModel('bug');

        $html = '<h3>' . ($type == 'linked' ? $this->lang->release->bugs : $this->lang->release->generatedBugs) . '</h3>';

        $fields = array('id' => $this->lang->bug->id, 'title' => $this->lang->bug->title);

        $html .= '<table><tr>';
        foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
        $html .= '</tr>';

        $bugs = array();
        $queryConditionName = $type == 'linked' ? 'linkedBugQueryCondition' : 'leftBugsQueryCondition';
        if($this->session->$queryConditionName !== false) $bugs = $this->dao->select('id, title')->from(TABLE_BUG)->where($this->session->$queryConditionName)->beginIF($this->session->bugOrderBy !== false)->orderBy($this->session->bugOrderBy)->fi()->fetchAll('id');
        foreach($bugs as $bug)
        {
            $bug->title = "<a href='" . common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id") . "' target='_blank'>$bug->title</a>";

            $html .= "<tr valign='top'>\n";
            foreach($fields as $fieldName => $fieldLabel) $html .= "<td><nobr>" . zget($bug, $fieldName, '') . "</nobr></td>\n";
            $html .= "</tr>\n";
        }
        $html .= '</table>';

        return $html;
    }
}
