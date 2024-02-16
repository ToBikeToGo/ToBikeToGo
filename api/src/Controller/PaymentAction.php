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
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function __invoke(Request $request): JsonResponse
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        $requestData = $request->attributes->get('data');

        $user = $requestData->getUser();
        $priceRequest = $requestData->getPrice();
        $booking = $requestData->getBooking();
        $commission = round(($priceRequest*2)/100, 2);
        $priceStripe = $priceRequest * 100;
        $booking->setUser($user[0]);
        $booking->setStatus(true);
        $booking->setRating(0);

        $now = new \DateTime();
        $payment = new Payment();
        $payment->setPrice($priceRequest);
        $payment->setCommission($commission);
        $payment->setStripeId(0);
        $payment->setStatus(true);
        $payment->setPaymentDate($now);
        $payment->setBooking($booking);

        $this->entityManager->persist($booking);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $billingText = "Name : " . $user[0]->getFirstname() . " " . $user[0]->getLastname() .
            "\nAmount : " . $priceRequest . '€';
        $this->emailing->sendBillingRecap([$user[0]], 3, $billingText);


        $YOUR_DOMAIN = $_ENV['URL_FRONT'];

        $price = \Stripe\Price::create([
            'unit_amount' => $priceStripe, // Montant en centimes (par exemple, 300 pour 3,00 €)
            'currency' => 'eur', // Devise (par exemple, 'eur' pour euros)
            'product_data' => [
                'name' => $booking->getBike()->getBrand(),
            ],
            'nickname' =>  $booking->getBike()->getId(), // Utilisez 'nickname' pour spécifier la description
        ]);

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price' => $price->id, // Identifiant de l'objet de prix
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/last-booking' . '?success=true',
            'cancel_url' => $YOUR_DOMAIN . '/rent/bike/' . $booking->getBike()->getId() . '?canceled=true',
        ]);
        // Rediriger l'utilisateur vers l'URL de la session de paiement Stripe
        return new JsonResponse([
        'redirect_url' => $checkout_session->url
        ]);
    }
}
