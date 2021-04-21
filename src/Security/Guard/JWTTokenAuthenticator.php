<?php

namespace App\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidPayloadException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserTokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class JWTTokenAuthenticator extends BaseAuthenticator
{
    private JWTTokenManagerInterface $jwtManager;
    private EventDispatcherInterface $dispatcher;
    private TokenExtractorInterface $tokenExtractor;
    private TokenStorageInterface $preAuthenticationTokenStorage;

    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher, TokenExtractorInterface $tokenExtractor, TokenStorageInterface $preAuthenticationTokenStorage)
    {
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
        $this->tokenExtractor = $tokenExtractor;
        $this->preAuthenticationTokenStorage = $preAuthenticationTokenStorage;
    }

    public function getUser($preAuthToken, UserProviderInterface $userProvider)
    {
        if (!$preAuthToken instanceof PreAuthenticationJWTUserTokenInterface) {
            throw new \InvalidArgumentException(sprintf('The first argument of the "%s()" method must be an instance of "%s".', __METHOD__, PreAuthenticationJWTUserTokenInterface::class));
        }

        $payload = $preAuthToken->getPayload();
        $idClaim = $this->jwtManager->getUserIdClaim();

        if (!isset($payload[$idClaim])) {
            throw new InvalidPayloadException($idClaim);
        }

        $identity = $payload[$idClaim];

        try {
            $user = $this->loadUser($userProvider, $payload, $identity);

            if (!$user || !$user->isVerified()) {
                throw new BadRequestException('Utilisateur non activé', 401);
            }

        } catch (UsernameNotFoundException $e) {
            throw new UserNotFoundException($idClaim, $identity);
        }

        $this->preAuthenticationTokenStorage->setToken($preAuthToken);

        return $user;
    }
}
