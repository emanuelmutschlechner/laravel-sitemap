<?php

namespace Spatie\Sitemap\Xml;

use DateTime;
use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class UrlSet extends Xml
{
    /** @var bool */
    protected $xhtml = true;

    /**
     * Process a tag.
     *
     * @param Url $tag
     */
    protected function process($tag): void
    {
        $this->add('loc', url($tag->url));
        foreach ($tag->alternates as $alternate) {
            $this->add('xhtml:link', null, [
                'rel' => 'alternate',
                'hreflang' => $alternate->locale,
                'href' => url($alternate->url),
            ]);
        }
        $this->addWhen(!empty($tag->lastModificationDate), 'lastmod', $tag->lastModificationDate->format(DateTime::ATOM));
        $this->addWhen(!empty($tag->changeFrequency), 'changefreq', $tag->changeFrequency);
        $this->addWhen(is_numeric($tag->priority), 'priority', $tag->priority);
    }

    /**
     * Defines whether a tag is valid or not.
     *
     * @param Tag $tag
     * @return bool
     */
    protected function isValid($tag): bool
    {
        return !empty($tag->url);
    }
}
