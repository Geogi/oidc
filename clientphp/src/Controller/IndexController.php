<?php

namespace App\Controller;

use App\Form\OpAuthenticatedType;
use App\Form\OpAuthorizedType;
use App\Form\OpPublicType;
use App\Security\ApiClient;
use GuzzleHttp\Exception\GuzzleException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @param ApiClient $client
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, ApiClient $client)
    {
        $opPublicForm = $this->createForm(OpPublicType::class);
        $opAuthenticatedForm = $this->createForm(OpAuthenticatedType::class);
        $opAuthorizedForm = $this->createForm(OpAuthorizedType::class);

        $opPublicRes = null;
        $opAuthenticatedRes = null;
        $opAuthorizedRes = null;

        $opPublicForm->handleRequest($request);
        if ($opPublicForm->isSubmitted() && $opPublicForm->isValid()) {
            try {
                $response = $client->get('/public');
                $opPublicRes = $response->getStatusCode();
            } catch (GuzzleException $e) {
                $opPublicRes = $e->getMessage();
            }
        }

        $opAuthenticatedForm->handleRequest($request);
        if ($opAuthenticatedForm->isSubmitted() && $opAuthenticatedForm->isValid()) {
            try {
                $response = $client->get('/authenticated');
                $opAuthenticatedRes = $response->getStatusCode();
            } catch (GuzzleException $e) {
                $opAuthenticatedRes = $e->getMessage();
            }
        }

        $opAuthorizedForm->handleRequest($request);
        if ($opAuthorizedForm->isSubmitted() && $opAuthorizedForm->isValid()) {
            try {
                $response = $client->get('/authorized');
                $opAuthorizedRes = $response->getStatusCode();
            } catch (GuzzleException $e) {
                $opAuthorizedRes = $e->getMessage();
            }
        }

        return $this->render('index/index.html.twig', [
            'op_public_form' => $opPublicForm->createView(),
            'op_authenticated_form' => $opAuthenticatedForm->createView(),
            'op_authorized_form' => $opAuthorizedForm->createView(),
            'op_public_res' => $opPublicRes,
            'op_authenticated_res' => $opAuthenticatedRes,
            'op_authorized_res' => $opAuthorizedRes,
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('oidc')
            ->redirect([
                'openid'
            ]);
    }

    /**
     * @Route("/login_check", name="login_check")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginCheck()
    {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout_check", name="logout_check")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutCheck()
    {
        return $this->redirectToRoute('index');
    }
}
