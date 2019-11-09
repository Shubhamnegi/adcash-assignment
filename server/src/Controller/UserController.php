<?php


namespace App\Controller;


use App\Service\UserService;
use App\Utility\CustomResponse;
use App\Utility\JsonHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
    private $userService;
    private $logger;

    /**
     * UserController constructor.
     * @param $userService
     * @param $logger
     */
    public function __construct(UserService $userService, LoggerInterface $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    function listUsers()
    {
        $users = $this->userService->listUsers();
        $response = new CustomResponse(JsonHelper::toJson($users));
        return new JsonResponse($response, 200);

    }
}