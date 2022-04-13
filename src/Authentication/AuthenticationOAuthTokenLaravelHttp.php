<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Authentication;

use Illuminate\Http\Client\PendingRequest;
use Skitlabs\Bayeux\Authentication\Exception\AuthenticationException;

final class AuthenticationOAuthTokenLaravelHttp implements Authentication
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $username;
    private string $password;
    private ?Token $token = null;

    public function __construct(string $baseUrl, string $clientId, string $clientSecret, string $username, string $password)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->username = $username;
        $this->password = $password;
    }

    public function token() : string
    {
        if (! $this->token) {
            $this->authenticate();
        }

        return $this->token->token;
    }

    public function tokenType() : string
    {
        if (! $this->token) {
            $this->authenticate();
        }

        return $this->token->type;
    }

    public function reset() : void
    {
        $this->token = null;
    }

    private function authenticate() : void
    {
        try {
            $response = (new PendingRequest())
                ->baseUrl($this->baseUrl)
                ->asForm()
                ->acceptJson()
                ->timeout(3)
                ->post('/services/oauth2/token', [
                    'grant_type' => 'password',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'username' => $this->username,
                    'password' => $this->password,
                ]);

            $tokenType = $response->json('token_type');
            $accessToken = $response->json('access_token');
            $instanceUrl = $response->json('instance_url');

            if (! $accessToken || ! $instanceUrl || ! $tokenType) {
                throw new AuthenticationException('Authentication failed');
            }

            $this->token = new Token($tokenType, $accessToken, $instanceUrl);
        } catch (\Throwable $e) {
            throw new AuthenticationException('Failed to authenticate', 0, $e);
        }
    }
}
