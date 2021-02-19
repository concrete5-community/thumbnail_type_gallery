<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Entity\File\Image\Thumbnail\Type\Type;
use Concrete\Core\File\File;
use Concrete\Core\Search\ItemList\Database\ItemList;
use Concrete\Core\Search\Pagination\PaginationProviderInterface;
use Pagerfanta\Adapter\DoctrineDbalAdapter;

class ImageList extends ItemList implements PaginationProviderInterface
{
    protected $paginationPageParameter = 'ccm_paging_ttg';
    protected $sortByDirection = 'asc';
    protected $sortBy = 'set';

    /** @var Type */
    protected $typeSmall;

    /** @var Type */
    protected $typeLarge;

    public function createQuery()
    {
        $this->query->select('f.fID')
            ->from('Files', 'f')
            ->innerJoin('f', 'FileVersions', 'fv', 'f.fID = fv.fID and fv.fvIsApproved = 1')
            ->leftJoin('f', 'FileSearchIndexAttributes', 'fsi', 'f.fID = fsi.fID');
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        $query = $this->deliverQueryObject();

        return (int) $query->resetQueryParts([
            'groupBy',
            'orderBy'
        ])->select('count(distinct f.fID)')->setMaxResults(1)->execute()->fetchColumn();
    }

    /**
     * @return mixed|DoctrineDbalAdapter
     */
    public function getPaginationAdapter()
    {
        return new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {
            $query->resetQueryParts(['groupBy', 'orderBy'])->select('count(distinct f.fID)')->setMaxResults(1);
        });
    }

    /**
     * @param $queryRow
     *
     * @return Image
     */
    public function getResult($queryRow)
    {
        $file = File::getByID($queryRow['fID']);

        $image = new Image();
        $image->setFile($file);
        $image->setThumbnailTypeSmall($this->typeSmall);
        $image->setThumbnailTypeLarge($this->typeLarge);

        return $image;
    }

    /**
     * @param string $extension
     */
    public function filterByExtension($extension)
    {
        $this->query->andWhere('fv.fvExtension = :fvExtension');
        $this->query->setParameter('fvExtension', $extension);
    }

    /**
     * @param int $fileSetId
     */
    public function filterBySetId($fileSetId)
    {
        $table = 'fsf' . $fileSetId;
        $this->query->leftJoin('f', 'FileSetFiles', $table, 'f.fID = ' . $table . '.fID');
        $this->query->andWhere($table . '.fsID = :fsID' . $fileSetId);
        $this->query->setParameter('fsID' . $fileSetId, $fileSetId);
    }

    public function filterByType($type)
    {
        $this->filter('fvType', $type);
    }

    /**
     * Sorts by file set display order in ascending order.
     */
    public function sortByFileSetDisplayOrder()
    {
        $this->query->orderBy('fsDisplayOrder', 'asc');
    }

    protected function getAttributeKeyClassName()
    {
        // TODO: Implement getAttributeKeyClassName() method.
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
