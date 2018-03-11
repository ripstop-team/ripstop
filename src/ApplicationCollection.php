<?php namespace Ripstop;

use ArrayObject;

class ApplicationCollection extends ArrayObject
{
    public static function fromAPIResponse($response): self
    {
        $collection = new self();
        foreach ($response as $entry) {
            $scan = Application::fromAPIResponse($entry);
            $collection->append($scan);
        }

        return $collection;
    }

    public function limit($limit)
    {
        return new self(array_slice($this->getArrayCopy(), 0, $limit));
    }
}
