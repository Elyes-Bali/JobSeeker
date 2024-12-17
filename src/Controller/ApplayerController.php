<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Repository\OffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;
class ApplayerController extends AbstractController
{
    #[Route('/Alloffers', name: 'app_offers')]
    public function listOffers(OffersRepository $offersRepository, Security $security): Response
    {
        // Retrieve all offers
     // Retrieve the currently logged-in user
     $user = $security->getUser();

     // Filter offers where creator matches the current user
     $offers = $offersRepository->findBy(['creatorId' => $user]);
 
     return $this->render('applayer/index.html.twig', [
         'offers' => $offers,
     ]);
    }

    #[Route('/Alloffers/{id}', name: 'app_offer_detail')]
    public function offerDetail(Offers $offer, UserRepository $userRepository): Response
    {
        // Get the list of applicant IDs
        $applicantIds = $offer->getUserIds(); // Example: [1, 2, 3]
    
        // Fetch the actual User entities based on the applicant IDs
        $applicants = $userRepository->findBy(['id' => $applicantIds]);
    
        // Prepare a list of applicants with their names and other data
        $applicantData = [];
        foreach ($applicants as $applicant) {
            $applicantData[] = [
                'id' => $applicant->getId(),
                'nom' => $applicant->getNom(),
                'prenom' => $applicant->getPrenom(),
            ];
        }
    
        // Dump applicants data to inspect it (use Symfony profiler or logs)
        dump($applicantData);
        
        // Render the template with the offer and the structured applicants data
        return $this->render('applayer/detail.html.twig', [
            'offer' => $offer,
            'applicants' => $applicantData,  // Pass the structured data with nom and prenom
        ]);
    }
    
    
    #[Route('/Alloffers/{id}/select/{userId}', name: 'app_offer_select_applayer', methods: ['POST'])]
    public function selectApplayer(Offers $offer, int $userId, EntityManagerInterface $em): Response
    {
        // Set the selected applayer for the offer
        $offer->setSelectedApplayerId($userId);
        $em->persist($offer);
        $em->flush();

        $this->addFlash('success', 'Applayer selected successfully!');

        return $this->redirectToRoute('app_offer_detail', ['id' => $offer->getId()]);
    }


    #[Route('/Alloffers/edit/{id}', name: 'offers_edit', methods: ['GET', 'POST'])]
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


    #[Route('/Alloffers/delete/{id}', name: 'offers_delete', methods: ['POST'])]
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

}
