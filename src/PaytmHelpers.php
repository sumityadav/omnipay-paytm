<?php

namespace Omnipay\Paytm;

use Omnipay\Common\Exception\InvalidResponseException;
use phpseclib\Crypt\Rijndael;

trait PaytmHelpers
{
    /**
     * Initialization vector for the encryption algorithm
     *
     * @var string
     */
    protected $iv = '@@@@&&&&####$$$$';

    /**
     * Generate checksum of the input array.
     *
     * @param  array   $arrayList
     * @param  string  $key       Paytm Merchant Key
     * @param  integer $sort
     * @return string             Generated checksum
     */
    public function getChecksumFromArray($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateRandomSalt(4);
        $finalString = $str . "|" . $salt;
        $hash = $this->hashString($finalString);
        $hashString = $hash . $salt;

        return $this->encryptString($hashString, $key);
    }

    /**
     * Verify if the received checksum is valid.
     *
     * @param  array  $arrayList
     * @param  string $key           Paytm Merchant Key
     * @return boolean               true|false
     */
    public function verifyChecksum($arrayList, $key)
    {
        if (isset($arrayList['CHECKSUMHASH']) && !empty($arrayList['CHECKSUMHASH'])) {
            $checksumParam = $arrayList['CHECKSUMHASH'];
            unset($arrayList['CHECKSUMHASH']);
        } else {
            throw new InvalidResponseException("Checksum hash not found.");
        }

        ksort($arrayList);
        $str = $this->getArray2Str($arrayList);

        $paytm_hash = $this->decryptString($checksumParam, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;

        $website_hash = $this->hashString($finalString);
        $website_hash .= $salt;

        return $website_hash === $paytm_hash;
    }

    /**
     * Trim values and return | separated string.
     *
     * @param  array $arrayList
     * @return string | Separated string
     */
    public function getArray2Str(array $arrayList)
    {
        array_walk($arrayList, function (&$item, $key) use ($arrayList) {
            $item = trim($arrayList[$key]);
        });

        return implode("|", is_array($arrayList) ? $arrayList : array());
    }

    /**
     * Encrypt the plain text using Rijndael algorithm
     *
     * @param  string $plaintext Plain text
     * @param  string $key       Paytm Merchant Key
     * @return string            base64 encoded encrypted string
     */
    public function encryptString($plaintext, $key)
    {
        $cipher = new Rijndael();
        $cipher->setKey($key);
        $cipher->iv = $this->iv;

        $encryptedString = $cipher->encrypt($plaintext);
        return base64_encode($encryptedString);
    }

    /**
     * Decrypt the encrypted string using Rijndael algorithm
     *
     * @param  string $encryptedString Encrypted string
     * @param  string $key             Paytm Merchant Key
     * @return string                  Plain text
     */
    public function decryptString($encryptedString, $key)
    {
        $cipher = new Rijndael();
        $cipher->setKey($key);
        $cipher->iv = $this->iv;
        $encryptedString = base64_decode($encryptedString);

        $plaintext = $cipher->decrypt($encryptedString);
        if (!$plaintext) {
            throw new InvalidResponseException("Invalid checksum.");
        }

        return $plaintext;
    }

    /**
     * Generate random sting which will be used as the salt for encryption
     *
     * @param  int    $length Length of the salt.
     * @return string         Random string
     */
    public function generateRandomSalt($length)
    {
        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        $random = "";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    /**
     * Hash string using the sha256 algo.
     *
     * @param  string $string string to hash
     * @return string         Hashed string
     */
    protected function hashString($string)
    {
        return hash("sha256", $string);
    }
}
