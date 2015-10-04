<?php

namespace Omnipay\Paytm\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Paytm Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Check if the payment was successful.
     *
     * @return boolean Payment status.
     */
    public function isSuccessful()
    {
        return $this->getTransactionStatus() === 'TXN_SUCCESS';
    }

    /**
     * Get Order Id that was sent to Paytm when processing.
     *
     * @return int Transaction Id (generated).
     */
    public function getOrderId()
    {
        return $this->data['ORDERID'];
    }

    /**
     * Get payment amount.
     *
     * @return double Payment amount.
     */
    public function getAmount()
    {
        return $this->data['TXNAMOUNT'];
    }

    /**
     * Get payment currency.
     *
     * @return string Payment current. INR
     */
    public function getCurrent()
    {
        return $this->data['CURRENCY'];
    }

    /**
     * Get Transaction Id
     *
     * @return int Paytm reference number.
     */
    public function getTransactionReference()
    {
        return $this->data['TXNID'];
    }

    /**
     * Get Bank transaction id.
     *
     * @return int Bank reference number.
     */
    public function getBankTransactionReference()
    {
        return $this->data['BANKTXNID'];
    }

    /**
     * Get Transaction status
     *
     * @return string Transaction Status. TXN_SUCCESS
     */
    public function getTransactionStatus()
    {
        return $this->data['STATUS'];
    }

    /**
     * Get Response code
     *
     * @return int Response code. (e.g.) 01
     */
    public function getResponseCode()
    {
        return $this->data['RESPCODE'];
    }

    /**
     * Get Response message
     *
     * @return string Response message. (e.g.) Txn Successful.
     */
    public function getResponseMessage()
    {
        return $this->data['RESPMSG'];
    }

    /**
     * Get Transaction date
     *
     * @return string Transaction Date/Time YYYY-MM-DD H:i:s
     */
    public function getTransactionDate()
    {
        return $this->data['TXNDATE'];
    }

    /**
     * Get gateway name
     *
     * @return string Name of the gateway used. WALLET|CREDITCARD etc.
     */
    public function getGatewayName()
    {
        return $this->data['GATEWAYNAME'];
    }

    /**
     * Get bank name
     *
     * @return string Bank name
     */
    public function getBankName()
    {
        return $this->data['BANKNAME'];
    }

    /**
     * Get payment mode
     *
     * @return string PPI
     */
    public function getPaymentMode()
    {
        return $this->data['PAYMENTMODE'];
    }
}
