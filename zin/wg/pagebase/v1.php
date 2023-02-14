<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'directive.func.php';

use zin\core\h;
use function zin\core\html;
use function zin\core\set;
use function zin\core\before;
use function zin\core\import;
use function zin\core\js;

class pagebase extends \zin\core\wg
{
    static $tag = 'html';

    static $defineProps = array
    (
        'metas' => array('type' => 'string|array', 'default' => array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')),
        'title' => array('type' => 'string', 'default' => ''),
        'bodyProps' => array('type' => 'array', 'optional' => true),
        'zui' => array('type' => 'bool', 'default' => false),
        'print' => array('type' => 'bool', 'default' => false)
    );

    protected function created()
    {
        if($this->prop('print'))
        {
            $this->print();
        }
    }

    protected function build($isPrinted = false)
    {
        global $lang, $config;

        $zui = $this->prop('zui');
        return h::html
        (
            before(html('<!DOCTYPE html>')),
            h::head
            (
                html($this->prop('metas')),
                h::title($this->props->get('title', '') . " - $lang->zentaoPMS"),
                $zui ? h::import(array($config->zin->zuiPath . 'zui.zentao.umd.cjs', $config->zin->zuiPath . 'zui.zentao.css')) : null,
                h::js('window.domReady = function(fn){if (document.readyState !== \'loading\') {fn();} else {document.addEventListener(\'DOMContentLoaded\', fn);}}'),
                $this->block('head'),
            ),
            h::body
            (
                set($this->prop('bodyProps')),
                $this->block('body'),
                parent::build($isPrinted),
                $config->debug ? h::js('window.zin = ' . json_encode($this) . ';console.log("page", window.zin.page)') : null
            )
        );
    }
}
