<?php

namespace App\Controller;
use App\Entity\Offers;
use App\Form\OffersType;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;
class OffersController extends AbstractController
{
    #[Route('/offers/new', name: 'CreateOffer')]
    public function create(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $offer = new Offers();
        $form = $this->createForm(OffersType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the current user
            $user = $security->getUser();

            if ($user) {
                // Set the creatorId to the current user's ID
                $offer->setCreatorId($user->getId());
            }

            // Persist and flush the new offer
            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Offer created successfully.');

            return $this->redirectToRoute('offers_list');
        }

        return $this->render('offers/CreateOffer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/offers', name: 'offers_list')]
    public function list(OffersRepository $offersRepository): Response
    {
        $offers = $offersRepository->findAll();

        return $this->render('offers/ListeOffers.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/api/offers/statistics', name: 'offers_statistics', methods: ['GET'])]
    public function statistics(OffersRepository $offersRepository): JsonResponse
    {
        $offers = $offersRepository->findAll();
        
        // Calcul des statistiques
        $totalOffers = count($offers);
        $averageSalary = $this->calculateAverageSalary($offers);
        $requiredExperiences = array_map(fn($offer) => $offer->getAnneesExperience(),$offers);
        $salaries = array_map(fn($offer) => $offer->isSalaire(),$offers);
        // $expiredOffers = array_filter($offers, fn($offer) => $offer->isExpired());
        // $activeContracts = array_filter($offers, fn($offer) => $offer->isActive());
        // Retourner les statistiques sous forme de JSON
        return new JsonResponse([
            'totalOffers' => $totalOffers,
            'averageSalary' => $averageSalary,
            "requiredExperiences"=>$requiredExperiences,
            "salaries"=>$salaries
        ]);
    }

    private function calculateAverageSalary($offers): float
        {
            if (count($offers) === 0) {
                return 0;  // Pas d'offres, donc salaire moyen est 0
            }

            $totalSalary = array_reduce($offers, fn($sum, $offer) => $sum + $offer->getSalaire(), 0);
            return $totalSalary / count($offers);
        }
        

    #[Route('/offers/{id}', name: 'offers_view')]
    public function view(int $id, OffersRepository $offersRepository): Response
    {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        return $this->render('offers/view.html.twig', [
            'offer' => $offer,
        ]);
    }

    #[Route('/offers/edit/{id}', name: 'offers_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        OffersRepository $offersRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        $form = $this->createForm(OffersType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Offer updated successfully.');

            return $this->redirectToRoute('app_offers');
        }

        return $this->render('offers/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    #[Route('/offers/delete/{id}', name: 'offers_delete', methods: ['POST'])]
    public function delete(
        int $id,
        OffersRepository $offersRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $offer = $offersRepository->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }

        $entityManager->remove($offer);
        $entityManager->flush();
        $this->addFlash('success', 'Offer deleted successfully.');

        return $this->redirectToRoute('offers_list');
    }


    #[Route('/offers/stats', name: 'offers_stats', methods: ['GET'])]
    public function stats(OfferRepository $offerRepository): JsonResponse
    {
        // Récupérer les données nécessaires depuis la base
        $totalBySpeciality = $offerRepository->countBySpeciality();
        $totalByCity = $offerRepository->countByCity();
        $monthlyData = $offerRepository->countByMonth();
        $totalByContract = $offerRepository->countByContractType();

        return new JsonResponse([
            'totalBySpeciality' => $totalBySpeciality,
            'totalByCity' => $totalByCity,
            'monthlyData' => $monthlyData,
            'totalByContract' => $totalByContract,
        ]);
    }


    #[Route('/offers/{id}/apply', name: 'offers_apply', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')] // Ensure only authenticated users can apply
    public function apply(int $id, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the Offer by ID
        $offer = $entityManager->getRepository(Offers::class)->find($id);

        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        // Get the current user's ID
        $currentUserId = $this->getUser()->getId();

        // Check if the user has already applied
        $userIds = $offer->getUserIds();
        if (in_array($currentUserId, $userIds)) {
            $this->addFlash('warning', 'You have already applied for this offer.');
            return $this->redirectToRoute('offers_view', ['id' => $id]);
        }

        // Add the user ID to the array
        $userIds[] = $currentUserId;
        $offer->setUserIds($userIds);

        // Persist the changes
        $entityManager->persist($offer);
        $entityManager->flush();

        $this->addFlash('success', 'You have successfully applied for the offer.');

        return $this->redirectToRoute('offers_view', ['id' => $id]);
    }

    #[Route('/myoffers', name: 'app_user_offers')]
    public function userOffers(OffersRepository $offerRepository): Response
    {
        // Get current user ID
        $currentUserId = $this->getUser()->getId(); // Assuming the user entity has an 'id' field
        
        // Get filtered offers based on current user
        $offers = $offerRepository->findByCurrentUserId($currentUserId);

        // Return the filtered offers to the Twig template
        return $this->render('offers/user_offers.html.twig', [
            'offers' => $offers,
        ]);
    }
}
