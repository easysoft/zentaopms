<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockZenTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'zen';

    /**
     * Test printBuildBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printBuildBlockTest(object $block)
    {
        $this->invokeArgs('printBuildBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->builds))
        {
            $result->count = count($view->builds);
            foreach($view->builds as $index => $build)
            {
                $result->$index = $build;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printCaseBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printCaseBlockTest(object $block)
    {
        $this->invokeArgs('printCaseBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->cases))
        {
            $result->count = count($view->cases);
            foreach($view->cases as $index => $case)
            {
                $result->$index = $case;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocDynamicBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocDynamicBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocDynamicBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->actions))
        {
            $result->actionsCount = count($view->actions);
            foreach($view->actions as $index => $action)
            {
                $result->$index = $action;
            }
        }
        else
        {
            $result->actionsCount = 0;
        }

        if(isset($view->users))
        {
            $result->usersCount = count($view->users);
        }
        else
        {
            $result->usersCount = 0;
        }
        return $result;
    }

    /**
     * Test printDocMyCollectionBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCollectionBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocMyCollectionBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocMyCreatedBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCreatedBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocMyCreatedBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocRecentUpdateBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocRecentUpdateBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocRecentUpdateBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocViewListBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocViewListBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocViewListBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }
}
