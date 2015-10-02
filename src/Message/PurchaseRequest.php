<?php

namespace Omnipay\Paytm\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Paytm\PaytmHelpers;

/**
 * Paytm Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    use PaytmHelpers;

    protected $liveEndpoint = 'https://secure.paytm.in/oltp-web/processTransaction';
    protected $testEndpoint = 'https://pguat.paytm.com/oltp-web/processTransaction';

    public function getData()
    {
        $data['MID'] = $this->getMID();
        $data['ORDER_ID'] = $this->getOrderId();
        $data['CUST_ID'] = $this->getCustomerId();
        $data['INDUSTRY_TYPE_ID'] = $this->getIndustryType();
        $data['CHANNEL_ID'] = $this->getChannelId();
        $data['TXN_AMOUNT'] = $this->getTransactionAmount();
        $data['WEBSITE'] = $this->getWebsite();
        $checkSumHash = $this->getChecksumHash($data);
        $data['CHECKSUMHASH'] = $checkSumHash;
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
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

    public function getChecksumHash($data)
    {
        $merchantKey = $this->getParameter('MerchantKey');
        return $this->getChecksumFromArray($data, $merchantKey);
    }
}
