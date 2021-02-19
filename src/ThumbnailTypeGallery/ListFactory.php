<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\File\Set\Set;
use Concrete\Core\File\Type\Type;

class ListFactory implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @param int $fileSetId
     *
     * @return ImageList
     */
    public function make($fileSetId)
    {
        /** @var ImageList $fileList **/
        $fileList = $this->app->make(ImageList::class);

        $fileSet = $this->getFileSet($fileSetId);
        if (!is_object($fileSet)) {
            $fileList->filterByExtension('not-existing');
        } else {
            $fileList->filterBySetId($fileSet->getFileSetID());
        }

        $fileList->filterByType(Type::T_IMAGE);
        $fileList->sortByFileSetDisplayOrder();

        return $fileList;
    }

    /**
     * @param int $fileSetId
     *
     * @return Set
     */
    private function getFileSet($fileSetId)
    {
        return Set::getByID($fileSetId);
    }
}
