<?php


namespace App\Services\Payment;
use App\Models\Order;
use GuzzleHttp;

class RazorPayService
{

    public $merchantkey='';
    public $api_key='';
    protected $api_secret='';

    public function __construct(GuzzleHttp\Client $client){
        $this->client=$client;
        $this->api_key=env('RAZORPAY_KEY');
        $this->api_secret=env('RAZORPAY_SECRET');
        $this->merchantkey=env('RAZORPAY_MERCHANT_ID');

    }


    public function generateorderid($data){

        try{
            //die('dsd');
            $response = $this->client->post('https://api.razorpay.com/v1/orders', [GuzzleHttp\RequestOptions::JSON =>$data, GuzzleHttp\RequestOptions::AUTH => [$this->api_key,$this->api_secret]]);
            //die('dsd');
            $body=$response->getBody()->getContents();

        }catch(GuzzleHttp\Exception\TransferException $e){
            $body=$e->getResponse()->getBody()->getContents();
        }
        return $body;
    }

    public function verifypayment($data){
        //return true;
        $generated_signature = hash_hmac('sha256', $data['razorpay_order_id'] . "|" . $data['razorpay_payment_id'], $this->api_secret);
        ///return true;
        if ($generated_signature == $data['razorpay_signature']) {
           return true;
        }
        return false;
    }
}
