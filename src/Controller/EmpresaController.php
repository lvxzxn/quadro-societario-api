<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Socio;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'api_')]
class EmpresaController extends AbstractController
{
    #[Route('/empresas', name: 'empresas_index', methods: ['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $empresas = $doctrine
            ->getRepository(Empresa::class)
            ->findAll();

        $data = [];

        foreach ($empresas as $empresa) {
            $data[] = [
                'id' => $empresa->getId(),
                'name' => $empresa->getName(),
                'email' => $empresa->getEmail(),
                'cnpj' => $empresa->getCNPJ(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/empresas/{id}', name: 'empresa_show', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $empresa = $entityManager->getRepository(Empresa::class)->find($id);

        if (!$empresa) {
            return $this->json('Nenhuma empresa foi encontrada com o id ' . $id, 404);
        }

        $data = [
            'id' => $empresa->getId(),
            'name' => $empresa->getName(),
            'email' => $empresa->getEmail(),
            'cnpj' => $empresa->getCNPJ(),
        ];

        return $this->json($data);
    }

    #[Route('/empresas/{id}/socios', name: 'empresa_socios_index', methods: ['get'])]
    public function indexSociosByEmpresa(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $empresa = $entityManager->getRepository(Empresa::class)->find($id);
    
        if (!$empresa) {
            return $this->json('Nenhuma empresa foi encontrada com o id ' . $id, 404);
        }
    
        $socios = $entityManager->getRepository(Socio::class)->findBy(['empresa_id' => $empresa->getId()]);
        
        $data = [];
    
        foreach ($socios as $socio) {
            $empresaDoSocio = [
                'id' => $empresa->getId(),
                'name' => $empresa->getName(),
                'email' => $empresa->getEmail(),
                'cnpj' => $empresa->getCNPJ(),
            ];
    
            $data[] = [
                'id' => $socio->getId(),
                'name' => $socio->getName(),
                'email' => $socio->getEmail(),
                'cpf' => $socio->getCPF(),
                'empresa' => $empresaDoSocio,
            ];
        }
    
        return $this->json($data);
    }

    #[Route('/empresas', name: 'empresa_create', methods: ['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $empresa = new Empresa();
        $empresa->setName($request->request->get('name'));
        $empresa->setEmail($request->request->get('email'));
        $empresa->setCNPJ($request->request->get('cnpj'));

        $entityManager->persist($empresa);
        $entityManager->flush();

        $data = [
            'id' => $empresa->getId(),
            'name' => $empresa->getName(),
            'email' => $empresa->getEmail(),
            'cnpj' => $empresa->getCNPJ(),
        ];

        return $this->json($data);
    }

    #[Route('/empresas/{id}', name: 'empresas_delete', methods: ['delete'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $empresa = $entityManager->getRepository(Empresa::class)->find($id);

        if (!$empresa) {
            return $this->json('Nenhuma empresa foi encontrada com o id ' . $id, 404);
        }

        $entityManager->remove($empresa);
        $entityManager->flush();

        return $this->json('A empresa de id ' . $id . ' foi deletada com sucesso.');
    }
}
