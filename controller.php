<?php

namespace Concrete\Package\ThumbnailTypeGallery;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;

/**
 * @creator https://a3020.com
 */
class Controller extends Package
{
    protected $pkgHandle = 'thumbnail_type_gallery';
    protected $appVersionRequired = '8.0.0';
    protected $pkgVersion = '1.1';
    protected $pkgAutoloaderRegistries = [
        'src/ThumbnailTypeGallery' => '\A3020\ThumbnailTypeGallery',
    ];

    public function getPackageName()
    {
        return t('Thumbnail Type Gallery');
    }

    public function getPackageDescription()
    {
        return t('Create a thumbnail type based image gallery with a file set.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!is_object(BlockType::getByHandle('thumbnail_type_gallery'))) {
            BlockType::installBlockType('thumbnail_type_gallery', $pkg);
        }
    }
}
