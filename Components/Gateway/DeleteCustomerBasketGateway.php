<?php

namespace FroshShareBasket\Components\Gateway;

use Doctrine\DBAL\Connection;

class DeleteCustomerBasketGateway implements DeleteCustomerBasketGatewayInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * DeleteCustomerBasketGateway constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function deleteBasket($basketId, $customerId)
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->setParameter(':basketId', $basketId)
            ->setParameter(':customerId', $customerId)
        ;

        $qb->execute();
    }

    protected function createQueryBuilder()
    {
        $qb = $this->connection->createQueryBuilder();
        $subQb = $this->connection->createQueryBuilder();

        $subQb
            ->select('basket.id')
            ->from('s_plugin_sharebasket_baskets', 'basket')
            ->where($qb->expr()->eq('basket.basket_id', ':basketId'))
            ->andWhere($qb->expr()->eq('basket.id', 's_plugin_sharebasket_basket_customer.basket_id'))
        ;

        $qb
            ->delete('s_plugin_sharebasket_basket_customer')
            ->andWhere('EXISTS(' . $subQb->getSQL() . ')')
            ->andWhere($qb->expr()->eq('s_plugin_sharebasket_basket_customer.customer_id', ':customerId'))
        ;

        return $qb;
    }
}
