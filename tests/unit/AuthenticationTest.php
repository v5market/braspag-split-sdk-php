<?php
declare(strict_types=1);

use \Braspag\Split\Test\BraspagSplitTestCase;
use \Braspag\Split\Domains\Authentication;
use \Braspag\Split\Domains\Environment;

final class AuthenticationTest extends BraspagSplitTestCase
{
    /**
     * @dataProvider dataProviderConstructor
     */
    public function testConstructorWithArgumentValid($arg)
    {
        $instance = new Authentication($arg);
        $this->assertInstanceOf(Authentication::class, $instance);
    }

    public function testSetterClientIdWithArgumentValid()
    {
        $instance = new Authentication(Environment::sandbox('clientId', 'clientSecret'));
        $instance->setClientId($this->clientId);

        $this->assertEquals($this->clientId, $instance->getClientid());
    }

    public function testSetterAndGetterClientSecretWithArgumentValid()
    {
        $instance = new Authentication(Environment::production('clientId', 'clientSecret'));
        $instance->setClientSecret($this->clientSecret);

        $this->assertEquals($this->clientSecret, $instance->getClientSecret());
    }

    public function testWithAccessTokenExpiredDefinedInConstructor()
    {
        $accessToken = [
        "access_token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjbGllbnRfbmFtZSI6Im9wZW5jYXJ0IiwiY2xpZW50X2lkIjoiNjUyZjY5NzAtMmFkNy00YjYxLWFhOTUtNzMyMTY2M2JjNjViIiwic2NvcGVzIjpbIntcIlNjb3BlXCI6XCJTcGxpdE1hc3RlclwiLFwiQ2xhaW1zXCI6W119Iiwie1wiU2NvcGVcIjpcIkNpZWxvQXBpXCIsXCJDbGFpbXNcIjpbXX0iXSwicm9sZSI6WyJTcGxpdE1hc3RlciIsIkNpZWxvQXBpIl0sImlzcyI6Imh0dHBzOi8vYXV0aHNhbmRib3guYnJhc3BhZy5jb20uYnIiLCJhdWQiOiJVVlF4Y1VBMmNTSjFma1EzSVVFbk9pSTNkbTl0Zm1sNWVsQjVKVVV1UVdnPSIsImV4cCI6MTU5MjA3OTI4NiwibmJmIjoxNTkxOTkyODg2fQ.yfYGUY0jUzat_KWJJlFszd397Z04YKuLsHMc0WH7P3o",
        "expires_in" => time() + 10000,
        "token_type" => "bearer"
        ];

        $instance = new Authentication(Environment::sandbox(
        $this->clientId,
        $this->clientSecret
        ), $accessToken);

        $result = $instance->accessToken();

        $this->assertFalse($accessToken["access_token"] !== $result);
    }

    public function dataProviderConstructor()
    {
        return [
        [Environment::sandbox('clientId', 'clientSecret')],
        [Environment::production('clientId', 'clientSecret')]
        ];
    }
}
