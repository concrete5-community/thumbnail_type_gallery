<?php

namespace A3020\ThumbnailTypeGallery;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Entity\File\Image\Thumbnail\Type\Type;
use Doctrine\ORM\EntityManager;

class ThumbType implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getSelectOptions()
    {
        $options = [];

        foreach ($this->getThumbnailTypes() as $type) {
            $name = $type->getDisplayName() .' ('.$type->getWidth().' x ';

            if ($type->getHeight()) {
                $name .= $type->getHeight();
            } else {
                $name .= t('auto');
            }

            $name .= ')';

            $options[$type->getID()] = $name;
        }

        return $options;
    }

    /**
     * @param int $id
     *
     * @return Type|null
     */
    public function getById($id)
    {
        return $this->entityManager->find(Type::class, $id);
    }

    /**
     * @return Type[]
     */
    protected function getThumbnailTypes()
    {
        return $this->entityManager->getRepository(Type::class)
            ->findBy([], [
                'ftTypeWidth' => 'asc'
            ]);
    }
}
