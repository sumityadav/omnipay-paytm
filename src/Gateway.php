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
}
