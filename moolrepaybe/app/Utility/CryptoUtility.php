<?php

namespace App\Utility;


class CryptoUtility{

    public function generateEncryptionKeys(){
        $config = array(
            'private_key_bits' => 1024, // Key size ( 2048 bits is a common choice )
            'private_key_type' => OPENSSL_KEYTYPE_RSA, // Key type   OPENSSL_KEYTYPE_EC
            // "curve_name" => "prime256v1"
            "digest_alg" => "sha256",
        );

        // Generate a new private key
        $privateKey = openssl_pkey_new( $config );

        // Extract the public key from the private key
        $publicKeyDetails = openssl_pkey_get_details( $privateKey );
        $publicKey = $publicKeyDetails[ 'key' ];

        // Save the private and public keys to files
        openssl_pkey_export( $privateKey, $privateKeyPEM );

        $publicKey = str_replace(["-----BEGIN PUBLIC KEY-----", "-----END PUBLIC KEY-----", "\n"], "", $publicKey);
        $privateKeyPEM = str_replace(["-----BEGIN PRIVATE KEY-----", "-----END PRIVATE KEY-----", "\n"], "", $privateKeyPEM);

        return array('public_key' => $publicKey,
        'private_key' => $privateKeyPEM
         );
    }

    public function decryptData($private_key, $encryptedData){
        $privateKey = openssl_get_privatekey($private_key);
        $decryptedData = null;

        return response()->json( [ 'data' => $privateKey, 'data2'=> $private_key]);

        if (openssl_private_decrypt($encryptedData, $decryptedData, $private_key)) {
            openssl_free_key($privateKey);
            return $decryptedData;
        } else {
            openssl_free_key($privateKey);
            return null;
        }
    } 
}