<?php

namespace Spatie\Sitemap\Xml;

use DateTime;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\Tags\Tag;

class SitemapIndex extends Xml
{
    /**
     * Process a tag.
     *
     * @param Sitemap $tag
     */
    protected function process($tag): void
    {
        $this->add('loc', url($tag->url));
        $this->addWhen(!empty($tag->lastModificationDate), 'lastmod', $tag->lastModificationDate->format(DateTime::ATOM));
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
