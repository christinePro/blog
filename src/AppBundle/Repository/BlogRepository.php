<?php

namespace AppBundle\Repository;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogRepository extends \Doctrine\ORM\EntityRepository
{

  public function  findAllForList()
  {
    $qb = $this->createQueryBuilder('c');
    $qb
      -> Select('c')
      ->addOrderBy('c.PublishAt', 'DESC')
      ;
      return $qb->getQuery()->getResult();
  }



}
