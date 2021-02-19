<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Image\Thumbnail\Type\Type;

class Image
{
    /** @var File */
    protected $file;

    /** @var Type */
    protected $typeSmall;

    /** @var Type */
    protected $typeLarge;

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string|null
     */
    public function getUrlSmall()
    {
        return $this->getFileVersion()->getThumbnailURL($this->typeSmall->getBaseVersion());
    }

    /**
     * @return string|null
     */
    public function getUrlLarge()
    {
        return $this->getFileVersion()->getThumbnailURL($this->typeLarge->getBaseVersion());
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        $fv = $this->getFileVersion();
        if ($fv->getTitle()) {
            return $fv->getTitle();
        }

        return $fv->getFileName();
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->getAlt();
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return \Concrete\Core\Entity\File\Version
     */
    protected function getFileVersion()
    {
        return $this->getFile()->getVersion();
    }

    /**
     * @param Type $typeSmall
     */
    public function setThumbnailTypeSmall($typeSmall)
    {
        $this->typeSmall = $typeSmall;
    }

    /**
     * @param Type $typeLarge
     */
    public function setThumbnailTypeLarge($typeLarge)
    {
        $this->typeLarge = $typeLarge;
    }
}
