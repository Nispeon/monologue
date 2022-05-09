<?php

namespace App\Controller;

use App\Repository\DebtRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepository,
        private DebtRepository $debtRepository
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function homepage(Request $request)
    {
        try {
            $date = new \DateTimeImmutable($request->query->get('date'));
        } catch (\Exception $e) {
            return new Response('Bad date format.', 400);
        }

        return $this->render('homepage/homepage.html.twig', [
            'events' => $this->eventRepository->findByCreatedAt($date),
            'debts' => $this->debtRepository->findPendings(),
            'date' => $date,
        ]);
    }
}
