<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Entity\File\Image\Thumbnail\Type\Type as ThumbnailType;
use Concrete\Core\File\FileList;
use Concrete\Core\File\Set\Set;
use Concrete\Core\File\Type\Type;

class Images implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Generator to get Image objects from a file set.
     *
     * We use a generator for memory and performance reasons.
     *
     * @param int $fileSetId
     *
     * @param ThumbnailType $smallThumbnailType
     * @param ThumbnailType $largeThumbnailType
     *
     * @return \Generator
     */
    public function get($fileSetId, $smallThumbnailType, $largeThumbnailType)
    {
        foreach ($this->getFiles($fileSetId) as $file) {
            /** @var Image $image */
            $image = $this->app->make(Image::class);
            $image->setFile($file);
            $image->setThumbnailTypeSmall($smallThumbnailType);
            $image->setThumbnailTypeLarge($largeThumbnailType);

            yield $image;
        }
    }

    /**
     * @param int $fileSetId
     *
     * @return bool
     */
    public function has($fileSetId)
    {
        $fileSet = $this->getFileSet($fileSetId);

        if (!is_object($fileSet)) {
            return false;
        }

        /** @var FileList $fileList */
        $fileList = $this->app->make(FileList::class);
        $fileList->filterBySet($fileSet);
        $fileList->filterByType(Type::T_IMAGE);

        return (bool) $fileList->getTotalResults();
    }

    /**
     * @param int $fileSetId
     *
     * @return \Concrete\Core\Entity\File\File[]
     */
    private function getFiles($fileSetId)
    {
        $images = [];

        $fileSet = $this->getFileSet($fileSetId);

        if (is_object($fileSet)) {
            $fileList = $this->app->make(FileList::class);
            $fileList->filterBySet($fileSet);
            $fileList->filterByType(Type::T_IMAGE);
            $fileList->sortByFileSetDisplayOrder();

            $images = $fileList->getResults();
        }

        return $images;
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
