<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\CheeseListing;
use Doctrine\ORM\QueryBuilder;

class CheeseListingIsPublishedExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== CheeseListing::class) {
            return;
        }

        /* note
         * Oh, and by the way - in addition to $resourceClass, this method receives the $operationName.
         * Normally only the get operation - like GET /api/cheese - would cause a query for a collection to be made...
         * but if you created some custom operations and needed to tweak the query on an operation-by-operation basis,
         * the $operationName can let you do that.
         */

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.isPublished = :isPublished', $rootAlias))
            ->setParameter('isPublished', true);
    }
}
