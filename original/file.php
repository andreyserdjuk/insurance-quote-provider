<?php

class Insurance
{
    public function quote($providers = null)
    {
        if (!$providers) {
            $providers = ['bank','insurance-company'];
        } else $providers = [$providers];
        $quote = array();

        $bank_url = 'http://demo9084693.mockable.io/bank';
        for ($i = 0; $i < count($providers); $i++) {
            switch ($providers[$i]) {
                case 'bank':
                    $prices = file_get_contents($bank_url);
                case 'insurance-company':
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => '"http://demo9084693.mockable.io/insurance"',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            'month' => 3,
                        )
                    ));
                    $prices = json_decode(curl_exec($curl));
                    curl_close($curl);
            }
            $quote[$providers[$i]] = $prices;
        }
        return $quote;
    }
}

$insurance = new Insurance();
$quote = $insurance->quote();

var_dump($quote);
