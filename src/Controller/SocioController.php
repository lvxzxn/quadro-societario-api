<?php

namespace App\Controller;

use App\Entity\Socio;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'api_')]
class SocioController extends AbstractController
{
    #[Route('/socios', name: 'socios_create', methods:['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $socio = new Socio();
        $socio->setName($request->request->get('name'));
        $socio->setEmail($request->request->get('email'));
        $socio->setEmpresaId($request->request->get('empresa_id'));

        $entityManager->persist($socio);
        $entityManager->flush();

        $data = [
            'id' => $socio->getId(),
            'name' => $socio->getName(),
            'email' => $socio->getEmail(),
            'empresa_id' => $socio->getEmpresaId(),
        ];

        return $this->json($data);
    }

    
    #[Route('/socios/{id}', name: 'socios_delete', methods:['delete'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse 
    {
        $entityManager = $doctrine->getManager();
        $socio = $entityManager->getRepository(Socio::class)->find($id);

        if (!$socio){
            return $this->json('Nenhum sócio foi encontrado com o id ' . $id, 404);
        }

        $entityManager->remove($socio);
        $entityManager->flush();

        return $this->json('O sócio de id '. $id . ' foi deletado com sucesso.');
    }
}
