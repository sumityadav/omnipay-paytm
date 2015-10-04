<?php

namespace Omnipay\Paytm;

use Omnipay\Common\AbstractGateway;

/**
 * Paytm Gateway
 *
 * @link http://paywithpaytm.com/developer.html
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Paytm';
    }

    public function getDefaultParameters()
    {
        return array(
            'MID' => 'wVhtoq05771472615938',
            'OrderId' => 'ORDS' . rand(10000, 99999999),
            'CustomerId' => 'CUST_001',
            'IndustryType' => 'Retail',
            'ChannelId' => 'WEB',
            'TransactionAmount' => 10,
            'Website' => 'MarketplaceBeta',
            'MerchantKey' => '5HFgd9GPiwzGOkvKD53N_Vq9SKR8Mmqx',
            'testMode' => true,
        );
    }

    public function getMID()
    {
        return $this->getParameter('MID');
    }

    public function setMID($value)
    {
        return $this->setParameter('MID', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('OrderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('OrderId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('CustomerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('CustomerId', $value);
    }

    public function getIndustryType()
    {
        return $this->getParameter('IndustryType');
    }

    public function setIndustryType($value)
    {
        return $this->setParameter('IndustryType', $value);
    }

    public function getChannelId()
    {
        return $this->getParameter('ChannelId');
    }

    public function setChannelId($value)
    {
        return $this->setParameter('ChannelId', $value);
    }

    public function getTransactionAmount()
    {
        return $this->getParameter('TransactionAmount');
    }

    public function setTransactionAmount($value)
    {
        return $this->setParameter('TransactionAmount', $value);
    }

    public function getWebsite()
    {
        return $this->getParameter('Website');
    }

    public function setWebsite($value)
    {
        return $this->setParameter('Website', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('MerchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('MerchantKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paytm\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paytm\Message\CompletePurchaseRequest', $parameters);
    }
}
