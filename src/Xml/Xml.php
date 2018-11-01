<?php

namespace Spatie\Sitemap\Xml;

use DOMDocument;
use DOMNode;
use Spatie\Sitemap\Tags\Tag;

abstract class Xml
{
    /** @var DOMDocument */
    protected $dom;

    /** @var bool */
    protected $xhtml = false;

    /** @var DOMNode */
    private $node;

    /**
     * Xml constructor.
     *
     * @param Tag[] $tags
     */
    public function __construct(array $tags)
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->preserveWhiteSpace = false;

        $this->addRoot();
        $this->addTags($tags);
    }

    /**
     * Render XML.
     *
     * @return string
     */
    public function __toString(): string
    {
        if (! $this->dom->lastChild->hasChildNodes()) {
            $this->dom->lastChild->nodeValue = "\n";
        }
        return $this->dom->saveXML();
    }

    /**
     * Process a tag.
     *
     * @param Tag $tag
     */
    abstract protected function process($tag): void;

    /**
     * Defines whether a tag is valid or not.
     *
     * @param Tag $tag
     * @return bool
     */
    abstract protected function isValid($tag): bool;

    /**
     * Add an element to the tag.
     *
     * @param string $name
     * @param null|string $value
     * @param array $attributes
     */
    protected function add(string $name, ?string $value = null, array $attributes = []): void
    {
        $node = $this->dom->createElement($name, $value);
        foreach ($attributes as $attributeName => $attributeValue) {
            $node->setAttribute($attributeName, $attributeValue);
        }
        $this->node->appendChild($node);
    }

    /**
     * Only add an element when the condition is valid.
     *
     * @param bool $condition
     * @param string $name
     * @param string|null $value
     * @param array $attributes
     */
    protected function addWhen(bool $condition, string $name, ?string $value = null, array $attributes = []): void
    {
        if ($condition) {
            $this->add($name, $value, $attributes);
        }
    }

    /**
     * Add the root element.
     */
    private function addRoot(): void
    {
        $node = $this->dom->createElement(strtolower(class_basename(static::class)));
        $node->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        if ($this->xhtml) {
            $node->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        }
        $this->dom->appendChild($node);
    }

    /**
     * Add and process all tags.
     *
     * @param Tag[] $tags
     */
    private function addTags(array $tags): void
    {
        foreach ($tags as $tag) {
            if ($this->isValid($tag)) {
                $this->node = $this->dom->createElement($tag->getType());
                $this->process($tag);
                $this->dom->lastChild->appendChild($this->node);
            }
        }
    }
}
