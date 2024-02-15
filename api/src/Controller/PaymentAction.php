<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Payment;
use App\Service\Emailing;
use Doctrine\ORM\EntityManagerInterface;
use http\Env;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Symfony\Component\Clock\now;

class PaymentAction extends AbstractController
{
    public function __construct(
        private readonly Emailing $emailing,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(Request $request) :JsonResponse
    {
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $requestData = $request->attributes->get('data');

        $user = $requestData->getUser();
        $price = $requestData->getPrice();
        $booking = $requestData->getBooking();
        $commission = round(($price*2)/100, 2);
        $priceStripe = str_replace('.', '', $price);

        try {
            $customer = $stripe->customers->create([
                'description' => 'Name : ' . $user[0]->getFirstname() . ' ' . $user[0]->getLastname(),
                'email' => $user[0]->getEmail(),
            ]);
            $cardCustomer = $stripe->customers->createSource($customer['id'], ['source' => 'tok_visa']);
            $charge = $stripe->charges->create([
                'customer' => $customer['id'],
                'amount' => $priceStripe,
                'currency' => 'EUR',
                'source' => $cardCustomer['id'],
            ]);
            $billingText = "Name : " . $user[0]->getFirstname() . " " . $user[0]->getLastname() .
                "\nAmount : " . $price . '€'
            ;
            $this->emailing->sendBillingRecap([$user[0]], 3, $billingText);

            $booking->setUser($user[0]);
            $booking->setStatus(true);
            $booking->setRating(0);

            $now = new \DateTime();
            $payment = new Payment();
            $payment->setPrice($price);
            $payment->setCommission($commission);
            $payment->setStripeId($charge['id']);
            $payment->setStatus(true);
            $payment->setPaymentDate($now);
            $payment->setBooking($booking);

            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            $json = [
                'status' => 'success',
                'code' => '200',
                'message' => 'Stripe call and mailing succeed '
            ];
        } catch (ApiErrorException $e) {
            $errorCode = $e->getError()->code;
            switch ($errorCode) {
                case 'card_declined':
                    $json = [
                        'status' => 'error',
                        'code' => '400',
                        'message' => 'Credit card declined. Please try another card.'
                    ];
                    break;
                case 'invalid_request_error':
                    $json = [
                        'status' => 'error',
                        'code' => '400',
                        'message' => 'Invalid request. Please check your input data.'
                    ];
                    break;
                default:
                    $json = [
                        'status' => 'error',
                        'code' => '500',
                        'message' => 'An error occurred. Please try again later.'
                    ];
                    break;
            }
        } catch (\Exception $e) {
            $json = [
                'status' => 'error',
                'code' => '500',
                'message' => 'An error occurred. Please try again later.'
            ];
        }
        return new JsonResponse($json);
    }
}
