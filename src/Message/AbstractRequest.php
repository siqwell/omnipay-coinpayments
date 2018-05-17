<?php

namespace Omnipay\CoinPayments\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\CoinPayments\Message
 */
abstract class AbstractRequest extends OmnipayRequest
{
    /**
     * @var string
     */
    protected $liveMerchantEndpoint = 'https://www.coinpayments.net/index.php';

    /**
     * @var string
     */
    protected $liveApiEndpoint = 'https://www.coinpayments.net/api.php';

    /**
     * @return string
     */
    protected function getMerchantEndpoint()
    {
        return $this->liveMerchantEndpoint;
    }

    /**
     * @return string
     */
    protected function getApiEndpoint()
    {
        return $this->liveApiEndpoint;
    }

    /**
     * @return mixed
     */
    protected function getIpnSecret()
    {
        return $this->getParameter('ipn_secret');
    }

    /**
     * @param $value
     *
     * @return OmnipayRequest
     */
    protected function setIpnSecret($value)
    {
        return $this->setParameter('ipn_secret', $value);
    }

    /**
     * @param      $method
     * @param null $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequest($method, $data = null)
    {
        $url  = $this->getApiEndpoint();
        $body = $data ? http_build_query($data, '', '&') : null;
        $hmac = hash_hmac('sha512', $body, $data['private_key']);

        return $this->httpClient->request($method, $url, [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'HMAC'         => $hmac
        ], $body);
    }
}