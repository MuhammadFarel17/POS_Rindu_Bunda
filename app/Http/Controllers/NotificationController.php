<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Fungsi utama untuk mengirim pesan WhatsApp menggunakan API Fonnte.
     * Sesuai dengan instruksi integrasi di modul.
     */
    public function sendMessage($target, $pesan)
    {
        // Token diambil dari akun Fonnte kamu, sebaiknya diletakkan di file .env
        $token = "ISI_TOKEN_FONNTE_KAMU_DISINI"; 

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan,
                'countryCode' => '62', // Kode negara Indonesia
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token" 
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}