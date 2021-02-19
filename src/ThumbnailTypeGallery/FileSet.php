<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\File\Set\Set;
use Concrete\Core\File\Set\SetList;

class FileSet implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        $options = [];

        foreach ($this->getFileSets() as $set) {
            $options[$set->getFileSetID()] = $set->getFileSetName();
        }

        return $options;
    }

    /**
     * @return Set[]
     */
    protected function getFileSets()
    {
        /** @var SetList $list */
        $list = $this->app->make(SetList::class);

        return $list->get();
    }
}
