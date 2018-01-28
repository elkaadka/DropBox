<?php

namespace Kanel\DropBox\Parameters;

class GetTemporaryLinkParameters
{

    public $metadata = [];
    public $link = '';

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }
}