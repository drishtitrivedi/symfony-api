<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use App\Form\ContactType;
use App\Entity\Contacts;
use App\Repository\ContactsRepository;

/**
 * REST controller.
 * @Route("/api",name="api_")
 * */

class RESTApiController extends AbstractFOSRestController
{
   
    /**
    * List all Contacts.
    *@Rest\Get("/contacts")
    *
    *@return Response
    */

    public function getContacts()
    {
        $repository = $this->getDoctrine()->getRepository(Contacts::class);
        $contacts = $repository->findAll();
        return $this->handleView($this->view($contacts));
    }


    /**
    * Search Contacts by name.
    *@Rest\Get("/search")
    *
    *@return Response
    */

    public function searchContacts(Request $request,ContactsRepository $contactsRepository)
    {
        $search_text = $request->query->get('name');
        $repository = $this->getDoctrine()->getRepository(Contacts::class);
        $contacts = $repository->findByNameLike($search_text);
        return $this->handleView($this->view($contacts));
    }



    /**
    * Create new Contacts.
    *@Rest\Post("/contacts")
    *
    *@return Response
    */   

    public function postContact(Request $request)
    {
        $contact = new Contacts();
        $form = $this->createForm(ContactType::class, $contact);
        
        $data = json_decode($request->getContent(),true);
        
        $form->submit($data);

        if($form->isSubmitted()) 
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $this->handleView($this->view(['status'=>'ok',"message"=>"contact inserted!"],Response::HTTP_CREATED));
        }
            
        return $this->handleView($this->view($form->getErrors()));
        //return $this->handleView($this->view($request->getContent()));
    }
}
