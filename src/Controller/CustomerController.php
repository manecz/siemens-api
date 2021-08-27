<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/*
 * With annotations I can adjust my routes inside my controller
 * this was used in all controllers
 */

    /**
     * prefixing routes to /customer
     * @Route("/", name="customer@")
     */

class CustomerController extends AbstractController
{
    /**
     *
     * List all customers
     *
     * @Route("/", name="index")
     * @param CustomerRepository $repository
     * @return Response
     */

    //Dependency injection using repository
    public function index(CustomerRepository $repository): Response
    {
        $customers = $repository->findAll();
        return $this->render('customer/index.html.twig', ['customers' => $customers]);
    }

    /**
     *
     * Create new customer
     * I used form to create a form type for Customer
     *
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $customer = new Customer();

        //CustomerType form
        $customerForm = $this->createForm(CustomerType::class, $customer);

        $customerForm->handleRequest($request);

        //Verification and database flush. sets flash message then redirects
        if($customerForm->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->addFlash('customer', 'Customer created');

            return $this->redirectToRoute('customer@index');
        }

        return $this->render('customer/create.html.twig', ['customerForm'=>$customerForm->createView()]);
    }

    /**
     *
     * Show customer by id
     * This uses Automatic Querie (parameter converter) provided by SensioFrameworkExtraBundle bundle
     *
     * @Route("/show/{id}", name="show")
     * @param Customer $customer
     * @return Response
     */
    public function show(Customer $customer): Response
    {
        //dump($customer); die;

        return $this->render('customer/show.html.twig', ['customer'=>$customer]);
    }

    /**
     *
     * Edit customer
     *
     * @Route("/update/{id}", name="update")
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function update(Request $request, Customer $customer): Response
    {


        //Create a form
        $form = $this->createFormBuilder($customer)
            ->add('name')
            ->add('email')
            ->add('update', submitType::class, [
                'label'=>'update',
                'attr'=>['class'=>'btn btn-dark float-right']
            ])->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->addFlash('customer', 'Customer updated');
            return $this->redirectToRoute('customer@index');
        }

        return $this->render('customer/update.html.twig',['updateCustomForm'=>$form->createView()]);
    }


    /**
     *
     * Remove customer
     *
     * @Route("/delete/{id}", name="delete")
     * @param Customer $customer
     * @return Response
     */
    public function remove(Customer $customer):Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();
        $this->addFlash('customer', 'Customer deleted');
        return $this->redirectToRoute('customer@index');
    }
}
