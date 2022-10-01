<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ];

            $event->setResponse($this->prepareResponse($data, $data['code']));
        } else {
            $data = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $exception->getMessage(),
            ];

            $event->setResponse($this->prepareResponse($data, $data['code']));
        }
    }

    private function prepareResponse(array $data, int $statusCode): JsonResponse
    {
        $response = new JsonResponse($data, $statusCode);
        $response->headers->set('Server-Time', time());
        $response->headers->set('X-Error-Code', $statusCode);

        return $response;
    }
}