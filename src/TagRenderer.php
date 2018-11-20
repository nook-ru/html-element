<?php

namespace Spatie\HtmlElement;

class TagRenderer
{
    /** @var string */
    protected $element;

    /** @var \Spatie\HtmlElement\Attributes */
    protected $attributes;

    /** @var string */
    protected $contents;

    /**
     * @param string $element
     * @param Attributes $attributes
     * @param string $contents
     * @return string
     */
    public static function render($element, Attributes $attributes, $contents)
    {
        return (new static($element, $attributes, $contents))->renderTag();
    }

    /**
     * @param string $element
     * @param Attributes $attributes
     * @param string $contents
     */
    protected function __construct($element, Attributes $attributes, $contents)
    {
        $this->element = $element;
        $this->attributes = $attributes;
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    protected function renderTag()
    {
        if ($this->isSelfClosingTag()) {
            return $this->renderOpeningTag();
        }

        return "{$this->renderOpeningTag()}{$this->contents}{$this->renderClosingTag()}";
    }

    /**
     * @return string
     */
    protected function renderOpeningTag()
    {
        return $this->attributes->isEmpty() ?
            "<{$this->element}>" :
            "<{$this->element} {$this->attributes}>";
    }

    /**
     * @return string
     */
    protected function renderClosingTag()
    {
        return "</{$this->element}>";
    }

    /**
     * @return bool
     */
    protected function isSelfClosingTag()
    {
        return in_array(strtolower($this->element), [
            'area', 'base', 'br', 'col', 'embed', 'hr',
            'img', 'input', 'keygen', 'link', 'menuitem',
            'meta', 'param', 'source', 'track', 'wbr',
        ]);
    }
}
