<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Locations;

class TravelController extends AbstractController
{
    /**
     * @Route("/locations", name="locations")
     */
    public function showAll(ManagerRegistry $doctrine)
    {
        $locations = $doctrine->getRepository(Locations::class)->findAll();
        return $this->render("travel/index.html.twig", array("locations" => $locations));
    }

    /**
     * @Route("locations/create", name="actionCreate")
     */
    public function actionCreate(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $location = new Locations();
        $location->setName("Vienna");
        $location->setPrice(800);

        $em->persist($location);
        $em->flush();

        //return new Response("New location: '" . $location->getName() . "' created with ID: '" . $location->getId() . "'");
        return $this->redirectToRoute("locations");
    }

    /**
     * @Route("locations/details/{id}", name="actionDetails")
     */
    public function actionDetails(ManagerRegistry $doctrine, $id)
    {
        $location = $doctrine->getRepository(Locations::class)->find($id);
        if (!$location) {
            //throw $this->createNotFoundException('No location found for id: ' . $id);
            return new Response("No location with ID: " . $id);
        } 
        else {
            return new Response("Name: " . $location->getName() . "<br/>Price: " . $location->getPrice());
        }
    }

    /**
     * @Route("locations/update/{id}")
     */
    public function actionUpdate(ManagerRegistry $doctrine, $id)
    {
        $em = $doctrine->getManager();
        $location = $em->getRepository(Locations::class)->find($id);

        if (!$location) {
            //throw $this->createNotFoundException('No location found for id: ' . $id);
            return new Response("No location with ID: " . $id);
        }
        else{
            $location->setName('Not Vienna');
            $location->setPrice(400);
            $em->flush();

            //return new Response("Location with ID: ".$location->getId()." updated");
            return $this->redirectToRoute("locations");
        }
    }

    /**
     * @Route("locations/delete/{id}")
     */
    public function actionDelete(ManagerRegistry $doctrine, $id)
    {
        $em = $doctrine->getManager();
        $location = $em->getRepository(Locations::class)->find($id);

        if (!$location) {
            //throw $this->createNotFoundException('No location found for id: ' . $id);
            return new Response("No location with ID: " . $id);
        }
        else{
            $em->remove($location);
            $em->flush();

            //return new Response("Location with ID: ".$location->getId()." removed");
            return $this->redirectToRoute("locations");
        }
    }
}
