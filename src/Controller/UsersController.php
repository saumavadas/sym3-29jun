<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\HttpFoundation\JsonResponse;


class UsersController extends AbstractController
{
    private $weather_app_id = "58fe595a877c084fc797adfc56976096";
    
    /**
     * @Route("/app/v1/", name="index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php'
        ]);
    }  

    /**
     * @Route("/app/v1/users/{userid}/weather", name="weather")
     */
    public function weather(Request $request): Response
    {       
        $userId = $request->get('userid');
        $user  = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $userId]);


        $httpClient = HttpClient::create();
        //$response = $httpClient->request('GET', 'http://api.openweathermap.org/data/2.5/weather?q='.$user->getCity().','.$user->getState().'&appid=58fe595a877c084fc797adfc56976096');

        $response = $httpClient->request('GET', 'http://api.openweathermap.org/data/2.5/weather?q='.$user->getCity().','.$user->getState().'&appid='.$this->weather_app_id);

        $content = $response->getContent();
        $content = $response->toArray();

        if (200 !== $response->getStatusCode()) 
        {
            // handle the HTTP request error (e.g. retry the request)
        } 
        else 
        {
            $content = $response->getContent();
        }
        return new JsonResponse(json_decode($content));
    }
}#End of the class
