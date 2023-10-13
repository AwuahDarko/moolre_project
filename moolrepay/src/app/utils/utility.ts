import { EncryptionKey } from "../models/crypto";

export class Utility {

    static formatExpiryDate(expdate: string): string {

        return expdate.replace(/\//g, "").substring(0, 2) +
            (expdate.length > 2 ? '/' : '') +
            expdate.replace(/\//g, "").substring(2, 4);
    }


    static async generateEncryptionKey(): Promise<EncryptionKey> {
        const keyPair = await window.crypto.subtle.generateKey(
            {
                name: "ECDH",
                namedCurve: "P-256",
            },
            true,
            ["deriveKey", "deriveBits"]
        )

        const publicKeyJwk = await window.crypto.subtle.exportKey(
            "jwk",
            keyPair.publicKey
        )

        const privateKeyJwk = await window.crypto.subtle.exportKey(
            "jwk",
            keyPair.privateKey
        )

        return { privateKey: privateKeyJwk, publicKey: publicKeyJwk };
    }

   static async encryptData(data: any, public_key: string): Promise<any> {

        // let encryptedData = null;
        try {
            const publicKeyBinary = atob(public_key);
            const publicKeyBuffer = new ArrayBuffer(publicKeyBinary.length);
            const publicKeyView = new Uint8Array(publicKeyBuffer);
            for (let i = 0; i < publicKeyBinary.length; i++) {
                publicKeyView[i] = publicKeyBinary.charCodeAt(i);
            }
            const publicKey = await crypto.subtle.importKey(
                'spki', // Key format
                publicKeyBuffer,
                { name: 'RSA-OAEP', hash: { name: 'SHA-256' } },
                true,
                ['encrypt']
            );
            // Convert the data to an ArrayBuffer or an appropriate format
            const dataBuffer = new TextEncoder().encode(data);

            // Encrypt the data using RSA encryption with the recipient's public key
           const  encryptedData = await window.crypto.subtle.encrypt(
                {
                    name: 'RSA-OAEP',
                },
                publicKey,
                dataBuffer
            );

            const encryptedDataArray = Array.from(new Uint8Array(encryptedData));
            const base64String = btoa(encryptedDataArray.map(byte => String.fromCharCode(byte)).join(''));
            console.log('base64String', base64String)
            return base64String;

        } catch (error) {
            console.log(error)
        }

        return null;
    }

    static validatePhoneNumber(text: string): boolean {
        let regex = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;

        return regex.test(text);
    }

    static validateNumber(text: string): boolean {
        if (!/^[0-9]+$/.test(text)) {
            return false
        }

        return true;
    }

    static validateEmail(text: string): boolean{
        let regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

        return regex.test(text);
    }

   static parse(response: Object | null): any {
        if (response == null) return null
    
        return JSON.parse(JSON.stringify(response));
   }
    

}