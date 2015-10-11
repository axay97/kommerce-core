<?php
namespace inklabs\kommerce\Service;

use inklabs\kommerce\Entity\Pagination;
use inklabs\kommerce\Entity\Tag;
use inklabs\kommerce\EntityRepository\TagRepositoryInterface;

class TagService extends AbstractService implements TagServiceInterface
{
    /** @var TagRepositoryInterface */
    private $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function create(Tag & $tag)
    {
        $this->throwValidationErrors($tag);
        $this->tagRepository->create($tag);
    }

    public function edit(Tag & $tag)
    {
        $this->throwValidationErrors($tag);
        $this->tagRepository->save($tag);
    }

    public function remove($tagId)
    {
        $tag = $this->tagRepository->findOneById($tagId);
        $this->tagRepository->remove($tag);
    }

    public function findOneById($id)
    {
        return $this->tagRepository->findOneById($id);
    }

    public function findOneByCode($code)
    {
        return $this->tagRepository->findOneByCode($code);
    }

    public function getAllTags($queryString = null, Pagination & $pagination = null)
    {
        return $this->tagRepository->getAllTags($queryString, $pagination);
    }

    public function getTagsByIds($tagIds, Pagination & $pagination = null)
    {
        return $this->tagRepository->getTagsByIds($tagIds, $pagination);
    }

    public function getAllTagsByIds($tagIds, Pagination & $pagination = null)
    {
        return $this->tagRepository->getAllTagsByIds($tagIds, $pagination);
    }
}
