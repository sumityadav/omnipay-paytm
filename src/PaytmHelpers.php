<?php

namespace Omnipay\Paytm;

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
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;

        return $this->encryptString($hashString, $key);
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
     * Add padding to the input string for block cyphers.
     *
     * @deprecated 0.1.1 This function is not used and should be removed.
     * @param string $text      Input text
     * @param int    $blocksize Length of the block
     */
    public function PKCS5Padding($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * Removed the padding from the input string.
     *
     * @deprecated 0.1.1 This function is not used and should be removed.
     * @param string $text Input text
     */
    public function PKCS5UnPadding($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }

    /**
     * Trim the input string
     *
     * @deprecated 0.1.1 This function is not used and should be removed.
     * @param  string $value Input string
     * @return string        Trimmed input string.
     */
    public function trimString($value)
    {
        $myvalue = ltrim($value);
        $myvalue = rtrim($myvalue);
        if ($myvalue == 'null') {
            $myvalue = '';
        }
        return $myvalue;
    }
}
