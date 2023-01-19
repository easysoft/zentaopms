<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class pagebase extends \zin\core\wg
{
    static $tag = 'html';

    static $customProps = 'metas,title,bodyProps,zui,print';

    public $bodyProps;

    public function init()
    {
        global $app, $config;
        $clientLang = $app->getClientLang();

        $this->bodyProps = \zin\core\wg::createClass($this->prop('bodyProps'));

        $this->setDefaultProps(array('lang' => $clientLang, 'print' => true));

        $this->addMeta('<meta charset="utf-8">')
            ->addMeta('<meta http-equiv="X-UA-Compatible" content="IE=edge">')
            ->addMeta('<meta name="viewport" content="width=device-width, initial-scale=1">')
            ->addMeta('<meta name="renderer" content="webkit">');

        if($this->prop('zui') && isset($config->zin->zuiPath))
        {
            $this->importJs($config->zin->zuiPath . 'zui.zentao.umd.cjs')
                ->importCss($config->zin->zuiPath . 'zui.zentao.css');
        }
    }

    public function onCreated()
    {
        if($this->prop('print'))
        {
            $this->print();
        }
    }

    public function title($title)
    {
        return $this->prop('title', $title);
    }

    public function addMeta($meta)
    {
        $metas = $this->props->get('metas', array());
        $metas[] = $meta;
        $this->prop('metas', $metas);
        return $this;
    }

    public function bodyClass($className, $reset = false)
    {
        $this->bodyProps->class->set($className, $reset);
    }

    public function bodyStyle($prop, $value = NULL, $removeEmpty = false)
    {
        $this->bodyProps->style->set($prop, $value, $removeEmpty);
        return $this;
    }

    protected function buildHead($isPrint = false, $parent = NULL)
    {
        global $lang;

        $headBuilder = \zin\core\wg::createBuilder('head')
            ->before($this->prop('metas'))
            ->before('<title>' . htmlspecialchars($this->props->get('title', '')) . " - $lang->zentaoPMS</title>")
            ->css($this->cssList)
            ->importCss($this->cssImports)
            ->renderInTag();

        if(isset($this->slots['head']))
        {
            $headBuilder->append($this->buildChildren($this->slots['head'], $isPrint, $parent));
        }

        return $headBuilder;
    }

    protected function buildBody($isPrint = false, $parent = NULL)
    {
        $builder = \zin\core\wg::createBuilder('body')
            ->props($this->bodyProps)
            ->importJs($this->jsImports)
            ->js($this->jsList)
            ->append($this->buildInnerHtml($isPrint, $parent))
            ->renderInTag();

        global $config;
        if($config->debug)
        {
            $builder->jsVar('window.zin', array('page' => $this));
            $builder->js('console.table("zin.page", window.zin.page)');
        }

        return $builder;
    }

    public function build($isPrint = false, $parent = NULL)
    {
        return \zin\core\wg::createBuilder('html')
            ->props($this->props)
            ->before('<!DOCTYPE html>')
            ->append($this->buildHead()->build())
            ->append($this->buildBody()->build());
    }
}
