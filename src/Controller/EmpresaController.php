<?php

namespace App\Controller;

use App\Entity\Empresa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'api_')]
class EmpresaController extends AbstractController
{
    #[Route('/empresas', name: 'empresa_create', methods:['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $empresa = new Empresa();
        $empresa->setName($request->request->get('name'));
        $empresa->setEmail($request->request->get('email'));

        $entityManager->persist($empresa);
        $entityManager->flush();

        $data = [
            'id' => $empresa->getId(),
            'name' => $empresa->getName(),
            'email' => $empresa->getEmail(),
        ];

        return $this->json($data);
    }
}
