<?php


namespace App\Services;

use Endroid\QrCode\QrCode;

class QrCodeService

{
    public function genQrCode(string $message)
    {
        $qrCode = new QrCode($message);
        $qrCode->setSize(300);
        $qrCode->setValidateResult(true);
        $qrCode->setLabel($message);

        header('Content-Type: ' . $qrCode->getContentType());

        $path = 'img/qrconfirm/qr' . substr($message, 19, 9) . '.png';

        $qrCode->writeFile($path);         // Save it to a file
        return $path;

        // Create a response object         $response = new QrCodeResponse($qrCode);
    }

    public function unlinkQrCode(string $message)
    {
        $path = 'img/qrconfirm/qr' . substr($message, 19, 9) . '.png';

        unlink($path);

    }

}