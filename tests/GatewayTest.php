<?php

namespace Omnipay\Paytm;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
        );
    }

    /**
     * @test
     */
    public function verify_purchase_redirects_to_payment_gateway()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertContains('https://pguat.paytm.com/oltp-web/processTransaction', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $data = $response->getRedirectData();
        $this->assertSame('wVhtoq05771472615938', $data['MID']);
        $this->assertArrayHasKey('CHECKSUMHASH', $data);
    }

    /**
     * @test
     */
    public function verify_purchase_was_successful()
    {
        $this->getHttpRequest()->request->replace($this->getResponseStub());

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('ORDS10625654', $response->getOrderId());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals('INR', $response->getCurrent());
        $this->assertEquals('375265', $response->getTransactionReference());
        $this->assertEquals('118285', $response->getBankTransactionReference());
        $this->assertEquals('TXN_SUCCESS', $response->getTransactionStatus());
        $this->assertEquals('01', $response->getResponseCode());
        $this->assertEquals('Txn Successful.', $response->getResponseMessage());
        $this->assertEquals('2015-10-05 00:49:26.0', $response->getTransactionDate());
        $this->assertEquals('WALLET', $response->getGatewayName());
        $this->assertEquals('', $response->getBankName());
        $this->assertEquals('PPI', $response->getPaymentMode());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseError()
    {
        $this->getHttpRequest()->request->replace(array('CHECKSUMHASH' => 'Invalid checksum'));

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    /**
     * @test
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function checksum_missing()
    {
        $response = $this->gateway->completePurchase($this->options)->send();
    }

    /**
     * @test
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function verify_checksum_failed()
    {
        $validResponse = $this->getResponseStub();
        $validResponse['MID'] = 'This will invalidate checksum';

        $this->getHttpRequest()->request->replace($validResponse);
        $response = $this->gateway->completePurchase($this->options)->send();
    }

    protected function getResponseStub()
    {
        return array(
            'MID' => 'wVhtoq05771472615938',
            'ORDERID' => 'ORDS10625654',
            'TXNAMOUNT' => '1.00',
            'CURRENCY' => 'INR',
            'TXNID' => '375265',
            'BANKTXNID' => '118285',
            'STATUS' => 'TXN_SUCCESS',
            'RESPCODE' => '01',
            'RESPMSG' => 'Txn Successful.',
            'TXNDATE' => '2015-10-05 00:49:26.0',
            'GATEWAYNAME' => 'WALLET',
            'BANKNAME' => '',
            'PAYMENTMODE' => 'PPI',
            'CHECKSUMHASH' => 'I1GKR2Lw11aoYm+pSnqW0M7VdRUaLUoA8xuGJQLWCOWTEVaaOs8DEZ6lTEpCDePG0pcq45F8fhO3f9k4ctBk2k8XweqDaj7XFGpnHSGi+Y8=',
        );
    }
}
