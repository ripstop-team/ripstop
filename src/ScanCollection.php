<?php namespace Ripstop;

use ArrayObject;

class ScanCollection extends ArrayObject
{
    public static function fromAPIResponse($response): ScanCollection
    {
        $collection = new self();
        foreach ($response as $entry) {
            $scan = Scan::fromAPIResponse($entry);
            $collection->append($scan);
        }

        return $collection;
    }

    public function limit($limit)
    {
        return new self(array_slice($this->getArrayCopy(), 0, $limit));
    }
}
