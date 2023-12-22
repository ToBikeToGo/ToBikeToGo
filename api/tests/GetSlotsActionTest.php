<?php

namespace App\Tests;

use App\Entity\Shop;
use App\Entity\Franchise;
use Doctrine\Persistence\ObjectManager;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\ORM\EntityRepository;

class GetSlotsActionTest extends ApiTestCase
{
    private static Client $client;

    private static $shopRepository;

    private static $franchiseRepository;

    private const CONTENT_TYPE = 'application/json';
    private const API_PATH = '/api/shops/';
    private const SLOTS_PATH = '/slots';

    public function setUp(): void
    {
        self::initClient();
        self::initRepository();
    }

    public static function initClient(): void
    {
        self::$client = static::createClient();
    }

    public static function initRepository(): void
    {
        $objectManager = self::bootKernel()->getContainer()->get('doctrine')->getManager();

        if (null == self::$shopRepository) {
            self::$shopRepository = $objectManager->getRepository(Shop::class);
        }
        if (null == self::$franchiseRepository) {
            self::$franchiseRepository = $objectManager->getRepository(Franchise::class);
        }
    }

    /**
     * @return void
     */
    public function testGetSlotsActionReturnsJsonResponse(): void
    {
        $shops = self::$shopRepository->findAll();
        $firstShopId = $shops[0]->getId();

        $today = (new \DateTime())->format('Y-m-d');
        define('SLOTS_PATH', '/slots');

        self::$client->request('POST', self::API_PATH . $firstShopId . SLOTS_PATH, [
            'headers' => [
                'Content-Type' => self::CONTENT_TYPE,
            ],
            'json' => [
                'date' => $today,
            ],
        ]);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertJsonResponse(self::$client->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testGetSlotsActionReturnsEmptyArrayWhenNoSlotsAvailable(): void
    {
        $franchises = self::$franchiseRepository->findAll();
        $lastFranchiseId = $franchises[count($franchises) - 1]->getId();

        $shop = self::$shopRepository->findOneBy(['franchise' => $lastFranchiseId]);
        $shopId = $shop->getId();

        $yesterday = (new \DateTime('-1 day'))->format('Y-m-d');

        self::$client->request('POST', self::API_PATH . $shopId . self::SLOTS_PATH, [
            'headers' => [
                'Content-Type' => self::CONTENT_TYPE,
            ],
            'json' => [
                'date' => $yesterday,
            ],
        ]);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertJsonResponse(self::$client->getResponse()->getContent());
        $this->assertEmpty(json_decode(self::$client->getResponse()->getContent(), true)['slots']);

        $today = (new \DateTime())->format('Y-m-d');

        self::$client->request('POST', self::API_PATH . $shopId . self::SLOTS_PATH, [
            'headers' => [
                'Content-Type' => self::CONTENT_TYPE,
            ],
            'json' => [
                'date' => $today,
            ],
        ]);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertJsonResponse(self::$client->getResponse()->getContent());
        $this->assertEmpty(json_decode(self::$client->getResponse()->getContent(), true)['slots']);
    }

    /**
     * @return void
     */
    public function testGetSlotsActionReturnsUnprocessableEntityWhenInvalidDate(): void
    {
        $shops = self::$shopRepository->findAll();
        $firstShopId = $shops[0]->getId();

        self::$client->request('POST', self::API_PATH . $firstShopId . self::SLOTS_PATH, [
            'headers' => [
                'Content-Type' => self::CONTENT_TYPE,
            ],
            'json' => [
                'date' => '01-01-2021',
            ],
        ]);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, self::$client->getResponse()->getStatusCode());
    }

    /**
     * Asserts that the response is a JSON response.
     *
     * @param string $responseContent
     * @return void
     */
    private function assertJsonResponse(string $responseContent): void
    {
        $this->assertJson($responseContent);
    }
}
