<?php
declare(strict_types=1);
namespace zin;

class dragUl extends wg
{
    private $ul;

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function onAddChild($child)
    {
        if(!($child instanceof wg)) return false;

        if($child->prop('tagName') !== 'li') return false;

        $child->setProp('draggable', 'true');
        return $child;
    }

    private function bindDragstartEvent()
    {
        $func = <<<DRAGSTART
            const dragLi = e.target;
            dragLi.style.opacity = .5;

            const ul = document.querySelector('[data-zin-gid="{$this->ul->gid}"]');
            const liList = Array.from(ul.children);
            ul.dataset.dragIndex = liList.indexOf(dragLi);
        DRAGSTART;

        $this->ul->add(on::dragstart($func));
    }

    private function bindDragendEvent()
    {
        $func = <<<DRAGEND
            e.target.style.opacity = '';
            console.log('dragend');
        DRAGEND;
        $this->ul->add(on::dragend($func));
    }

    private function bindDragoverEvent()
    {
        $func = <<<DRAGOVER
            e.preventDefault();
        DRAGOVER;
        $this->ul->add(on::dragover($func));
    }

    private function bindDragexitEvent()
    {
        $func = <<<DRAGEXIT
            e.preventDefault();
        DRAGEXIT;
        $this->ul->add(on::dragexit($func));
    }

    private function bindDragenterEvent()
    {
        $func = <<<DRAGENTER
            const enterLi = e.target.closest('li');
            enterLi.classList.add('hold');

            const ul = document.querySelector('[data-zin-gid="{$this->ul->gid}"]');
            const liList = Array.from(ul.children);
            ul.dataset.enterIndex = liList.indexOf(enterLi);
        DRAGENTER;
        $this->ul->add(on::dragenter($func));
    }

    private function bindDragleaveEvent()
    {
        $func = <<<DRAGLEAVE
            e.target.classList.remove('hold');
        DRAGLEAVE;
        $this->ul->add(on::dragleave($func));
    }

    private function bindDropEvent()
    {
        $func = <<<DROP
            e.preventDefault();
            const ul = document.querySelector('[data-zin-gid="{$this->ul->gid}"]');
            const dragIndex = ul.dataset.dragIndex;
            const enterIndex = ul.dataset.enterIndex;
            const dragLi = Array.from(ul.children)[ul.dataset.dragIndex];
            const enterLi = Array.from(ul.children)[ul.dataset.enterIndex];
            enterLi.classList.remove('hold');
            if(dragIndex < enterIndex) {
                enterLi.after(dragLi);
            } else if(dragIndex > enterIndex) {
                enterLi.before(dragLi);
            }
        DROP;
        $this->ul->add(on::drop($func));
    }

    protected function build(): wg
    {
        $ul = ul
        (
            setClass('drag-ul'),
            set($this->getRestProps()),
            $this->children(),
        );

        $ul->setProp('data-zin-gid', $ul->gid);
        $this->ul = $ul;
        $this->bindDragstartEvent();
        $this->bindDragendEvent();
        $this->bindDragoverEvent();
        $this->bindDragexitEvent();
        $this->bindDragenterEvent();
        $this->bindDragleaveEvent();
        $this->bindDropEvent();
        return $ul;
    }
}
