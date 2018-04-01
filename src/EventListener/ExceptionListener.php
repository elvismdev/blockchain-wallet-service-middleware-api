<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();

		$responseData = [
			'error'		=> true,
			'message'	=> $exception->getMessage()
		];

		$event->setResponse(new JsonResponse($responseData));
	}
}