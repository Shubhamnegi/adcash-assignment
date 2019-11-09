<?php


namespace App\EventListener;


use App\Constant\ApplicationConstants;
use App\Utility\CustomResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new Response();
        $message = $exception->getMessage();
        if ($exception->getCode() >= 500) {
            $message = ApplicationConstants::MESSAGE_INTERNAL_ERROR;
        }
        $customResponse = new CustomResponse(null, $message);
        $response->setContent(json_encode($customResponse));

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $response->headers->set("content-type", "application/json");
        $event->setResponse($response);
    }
}