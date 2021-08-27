<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * For this Controller I decided to use CustomerRepository for build my custom functions
 *
 * prefixing routes to /api
 * @Route("/api", name="api@")
 */

class ApiController extends AbstractController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/", name="all", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];
        foreach ($customers as $customer){
            $data[] = [
                'id'    => $customer->getId(),
                'name'  => $customer->getName(),
                'email' => $customer->getEmail()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        /*
         * json_decode() Takes a JSON encoded string and converts it into a PHP variable
         * When setted to true, JSON objects will be returned as associative arrays
         * getContent() returns the request body
         */
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $email = $data['email'];

        if(empty($email)){
            throw new NotFoundHttpException('Email is mandatory');
        }

        $this->customerRepository->apiSaveCustomer($name, $email);

        return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $customer = $this->customerRepository->apiGetCustomer($id);

        if(empty($customer)){
            throw new NotFoundHttpException('Customer not found');
        }
        $data = $customer->toArray();

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        //Check if customer exists
        $customer = $this->customerRepository->apiGetCustomer($id);
        if(empty($customer)){
            throw new NotFoundHttpException('Customer not found');
        }
        //Preparing customer for update
        $data = json_decode($request->getContent(), true);
        empty($data['name']) ? true : $customer->setName($data['name']);
        empty($data['email']) ? true : $customer->setEmail($data['email']);

        //Update Customer
        $updateCustomer = $this->customerRepository->apiUpdateCustomer($customer);

        return new JsonResponse($updateCustomer->toArray(), Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="delete", methods={"delete"})
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $customer = $this->customerRepository->find($id);
        if(empty($customer)){
            throw new NotFoundHttpException('Customer not found');
        }
        $this->customerRepository->apiDeleteCustomer($customer);
        return new JsonResponse(['status' => 'Customer deleted'], Response::HTTP_NO_CONTENT);
    }
}
