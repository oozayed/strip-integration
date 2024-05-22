<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripController extends Controller
{
    public $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(
            config('stripe.api_key.secret')
        );
    }

    public function pay(){
//        $id = $this->stripe->products->create([
//            'name' => 'gold plan'
//        ])->id;
//
//        $price = $this->stripe->prices->create([
//            'unit_amount' => 1000,
//            'currency' => 'usd',
//            'product' => $id,
//        ])->id;

        $coupon = $this->stripe->coupons->create([
            'percent_off' => 25,
            'duration' => 'repeating',
            'duration_in_months' => 3,
        ])->id;

          $promotion = $this->stripe->promotionCodes->create([
              'coupon' => $coupon,
              'code' => '25OFF',
              'active' => true,
              'expires_at' => time() + 60 * 60 * 24 * 7,
          ])->id;

        $session = $this->stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'success_url' => 'https://example.com/success',
            'line_items' => [
                [
                    'price' => 'price_1PJBOfHtpsFPAgEDDwLui9oV',
                    'quantity' => 2,
                ],
            ],
            'allow_promotion_codes' => true,
        ]);
        return redirect($session->url);
    }
}
