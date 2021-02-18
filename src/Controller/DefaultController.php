<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @param HttpClientInterface $client
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(HttpClientInterface $client, LoggerInterface $logger): Response
    {
        $draws = [];

        try {
            $draws = $client->request('GET', 'https://www.fdj.fr/api/service-draws/v1/games/euromillions/draws?include=results,addons&range=0-0')->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $logger->error($e->getMessage());
        }

        return $this->render('default/index.html.twig', [
            'draws' => $draws,
        ]);
    }
}
