<?php

namespace Omnipay\Paytm\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paytm Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $parameters = $this->httpRequest->request->all();
        $merchantKey = $this->getParameter('MerchantKey');

        if (!$this->verifyChecksum($parameters, $merchantKey)) {
            throw new InvalidResponseException("Checksum mismatch.");
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
