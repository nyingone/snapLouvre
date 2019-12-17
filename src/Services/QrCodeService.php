<?php


namespace App\Services;

use Endroid\QrCode\QrCode;

class QrCodeService

{
    public function genQrCode(string $message)
    {
        $qrCode = new QrCode($message);
        $qrCode->setSize(150);
        $qrCode->setValidateResult(true);

        header('Content-Type: ' . $qrCode->getContentType());

        $ref = './img/qrconfirm/qr' . substr($message, 19, 9) . '.png';  // ref. internal bookingNumber
        $path = 'img/qrconfirm/qr' . substr($message, 19, 9) . '.png';

        $qrCode->writeFile($path);         // Save it to a file
        return $ref;

        // Create a response object         $response = new QrCodeResponse($qrCode);
    }


}