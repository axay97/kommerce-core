<?php
namespace inklabs\kommerce\EntityRepository;

use Doctrine\ORM\EntityRepository;
use inklabs\kommerce\Doctrine\ORM\QueryBuilder;
use inklabs\kommerce\Entity\EntityInterface;

abstract class AbstractRepository extends EntityRepository implements AbstractRepositoryInterface
{
    public function getQueryBuilder()
    {
        return new QueryBuilder($this->getEntityManager());
    }

    public function create(EntityInterface & $entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    public function update(EntityInterface & $entity)
    {
        $this->assertManaged($entity);
        $this->flush();
    }

    public function delete(EntityInterface $entity)
    {
        $this->remove($entity);
        $this->flush();
    }

    public function remove(EntityInterface $entity)
    {
        $this->getEntityManager()
            ->remove($entity);
    }

    public function persist(EntityInterface & $entity)
    {
        $this->getEntityManager()
            ->persist($entity);
    }

    public function flush()
    {
        $this->getEntityManager()
            ->flush();
    }

    public function findOneById($id)
    {
        return $this->returnOrThrowNotFoundException(
            parent::findOneBy(['id' => $id])
        );
    }

    protected function returnOrThrowNotFoundException($entity)
    {
        if ($entity === null) {
            throw $this->getEntityNotFoundException();
        }

        return $entity;
    }

    protected function getEntityNotFoundException()
    {
        return new EntityNotFoundException($this->getClassName() . ' not found');
    }

    private function assertManaged(EntityInterface $entity)
    {
        if (! $this->getEntityManager()->contains($entity)) {
            throw $this->getEntityNotFoundException();
        }
    }
}
