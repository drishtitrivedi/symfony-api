<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Entity\Contacts;
use App\Repository\ContactsRepository;


class ContactsController extends AbstractController
{
    /**
     * @Route("/", name="contacts")
     */
    public function index(Request $request,ContactsRepository $contactsRepository)
    {
        $contacts = $contactsRepository->findAll();

        if($request->request->get('search_text')){
            $search_text = $request->request->get('search_text');        
            $contacts = $contactsRepository->findByNameLike($search_text);
        }
       
        return $this->render('contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */

    public function create(Request $request)
    {
        $contact = new Contacts();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addflash('success',"Contact added successfully");
    
            return $this->redirectToRoute('index');
        }

        return $this->render('contacts/create.html.twig',[
            'form' => $form->createView()
        ]);
    
    }

     /**
     * @Route("/edit/{id}", name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id,Request $request,ContactsRepository $contactsRepository) {

        $contact = $contactsRepository->find($id);
        
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Contact Updated! ');
            
            return $this->redirectToRoute('index');
        }
        return $this->render('contacts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("remove/{id}", name="delete")
     */

     public function remove($id,ContactsRepository $contactsRepository){
        $contact = $contactsRepository->find($id);
         //entity manager
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();
        
        $this->addflash('success',"Contact deleted successfully");
        return $this->redirectToRoute('index');
        
     }

     /**
     * @Route("/search/{id}", name="edit")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request,ContactsRepository $contactsRepository) {

        $search_text = $request->request->get('search_text');
        
        $contacts = $contactsRepository->findByNameLike($search_text);

        return $this->render('contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
