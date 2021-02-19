<?php

namespace Concrete\Package\ThumbnailTypeGallery\Block\ThumbnailTypeGallery;

use A3020\ThumbnailTypeGallery\FileSet;
use A3020\ThumbnailTypeGallery\ListFactory;
use A3020\ThumbnailTypeGallery\ThumbType;
use Concrete\Core\Application\Application;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Facade;

class Controller extends BlockController
{
    protected $btTable = 'btThumbnailTypeGallery';
    protected $btExportTables = ['btThumbnailTypeGallery'];
    protected $btInterfaceWidth = '500';
    protected $btInterfaceHeight = '550';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0;
    protected $btDefaultSet = 'multimedia';

    /** @var int */
    protected $file_set_id;

    /** @var int */
    protected $small_thumbnail_type_id;

    /** @var int */
    protected $large_thumbnail_type_id;

    /** @var bool */
    protected $show_captions;

    /** @var int */
    protected $styling;

    /** @var int */
    protected $items_per_page;

    /** @var Application */
    protected $appInstance;

    public function getBlockTypeName()
    {
        return t('Thumbnail Type Gallery');
    }

    public function getBlockTypeDescription()
    {
        return t('Create a thumbnail type based image gallery with a file set.');
    }

    public function on_start()
    {
        // For some reason, '$this->app' sometimes returns NULL
        $this->appInstance = Facade::getFacadeApplication();
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    public function view()
    {
        $this->set('c', Page::getCurrentPage());

        $list = $this->getList();
        if (!$list) {
            return;
        }

        $pagination = $list->getPagination();

        $this->set('hasImages', (bool) $list->getTotalResults());
        $this->set('images', $pagination->getCurrentPageResults());

        if ($pagination->haveToPaginate()) {
            $this->set('pagination', $pagination->renderDefaultView());
        }
    }

    public function save($args)
    {
        $args['show_captions'] = isset($args['show_captions']) ? 1 : 0;

        parent::save($args);
    }

    public function validate($args)
    {
        // For some reason on_start is not called when this method is called.
        $app = Facade::getFacadeApplication();
        $error = $app->make('helper/validation/error');

        $required = [];
        $required['file_set_id'] = t('File set');
        $required['small_thumbnail_type_id'] = t('Small thumbnail type');
        $required['large_thumbnail_type_id'] = t('Large thumbnail type');

        foreach ($required as $handle => $label) {
            if (empty($args[$handle])) {
                $error->add(t('Field "%s" is required.', $label));
            }
        }

        return $error;
    }

    public function registerViewAssets($outputContent = '')
    {
        $js = 'var ttgi18n = '.json_encode($this->getJavaScriptStrings()).';';
        $this->addFooterItem('<script>'.$js.'</script>');

        $this->requireAsset('javascript', 'core/lightbox');
        $this->requireAsset('css', 'core/lightbox');

        $al = AssetList::getInstance();
        $al->register('javascript', 'thumbnail_type_gallery/magnific-popup', 'blocks/thumbnail_type_gallery/js_files/initialize.js', [], 'thumbnail_type_gallery');
        $this->requireAsset('javascript', 'thumbnail_type_gallery/magnific-popup');

        if ((int) $this->styling === 1) {
            $al->register('css', 'thumbnail_type_gallery/styling', 'blocks/thumbnail_type_gallery/css_files/basic.css', [], 'thumbnail_type_gallery');
            $this->requireAsset('css', 'thumbnail_type_gallery/styling');
        }
    }

    /**
     * @inheritdoc
     */
    public function getJavaScriptStrings()
    {
        return [
            'imageNotLoaded' => t('%sThe image%s could not be loaded.', '<a href=\"%url%\">', '</a>'),
            'close' => t('Close (Esc)'),
            'loading' => t('Loading...'),
            'previous' => t('Previous (Left arrow key)'),
            'next' => t('Next (Right arrow key)'),
            'counter' => t('%curr% of %total%'),
        ];
    }

    protected function addEdit()
    {
        /** @var FileSet $fileSet */
        $fileSet = $this->appInstance->make(FileSet::class);

        /** @var ThumbType $thumbType */
        $thumbType = $this->appInstance->make(ThumbType::class);

        $this->set('app', $this->appInstance);
        $this->set('fileSetOptions', $fileSet->getSelectOptions());
        $this->set('smallThumbnailTypeOptions', $thumbType->getSelectOptions());
        $this->set('largeThumbnailTypeOptions', $thumbType->getSelectOptions());
    }

    /**
     * @return int
     */
    protected function getItemsPerPage()
    {
        $max = (int) $this->items_per_page;

        return $max ? $max : 100;
    }

    /**
     * @return \A3020\ThumbnailTypeGallery\ImageList|void
     */
    private function getList()
    {
        /** @var ThumbType $thumbType */
        $thumbType = $this->appInstance->make(ThumbType::class);

        $small = $thumbType->getById($this->small_thumbnail_type_id);
        $large = $thumbType->getById($this->large_thumbnail_type_id);

        if (!$small || !$large) {
            return;
        }

        /** @var ListFactory $factory */
        $factory = $this->appInstance->make(ListFactory::class);

        $list = $factory->make($this->file_set_id);
        $list->setThumbnailTypeSmall($small);
        $list->setThumbnailTypeLarge($large);
        $list->setItemsPerPage($this->getItemsPerPage());

        return $list;
    }
}
