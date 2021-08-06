<?php

namespace HttpKernel\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserAgentArgumentResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        // 3 requests!!
        // 1 main, 1 sub and 1 toolbar
        //dump($request);

        return $argument->getName() === 'partialMac' || $argument->getName() === 'fromResolver' && $request->attributes->has('_alpha'); // From Subscriber
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield uniqId();
    }
}