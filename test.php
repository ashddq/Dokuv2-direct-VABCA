        $clientId = 'xxx';
        $secretKey = 'xxxx';
        $requestId = time();
        date_default_timezone_set('UTC');
        $path = '/bca-virtual-account/v2/payment-code';
        $url = 'https://api-sandbox.doku.com' . $path;
        $timestamp      = date('Y-m-d\TH:i:s\Z');
        $invoice = 'INV-' . time();
        $Body = array(
            'order' =>
            array(
                'invoice_number' => $invoice,
                'amount' => $amount
            ),
            'virtual_account_info' =>
            array(
                'billing_type' => 'FIX_BILL',
                'expired_time' => 60,
                'reusable_status' => false,
                'info1' => 'Thanks for shooping',
                'info2' => 'at Ashddq',
                'info3' => 'Enjoy!'
            ),
            'customer' =>
            array(
                'name' => $name,
                'email' => 'customer@gmail.com'
            )
        );
        $digest = base64_encode(hash('sha256', json_encode($Body), true));
        $abc = "Client-Id:" . $clientId . "\n" . "Request-Id:" . $requestId . "\n" . "Request-Timestamp:" . $timestamp . "\n" . "Request-Target:" . $path . "\n" . "Digest:" . $digest;
        $signature = base64_encode(hash_hmac('sha256', $abc, $secretKey, true));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Client-Id:' . $clientId,
            'Request-Id:' . $requestId,
            'Request-Timestamp:' . $timestamp,
            'Signature:' . "HMACSHA256=" . $signature,
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $hasil = json_decode($response, true);
