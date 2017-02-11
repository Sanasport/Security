<?php

namespace Tests\Integration;

use Arachne\DIHelpers\ResolverInterface;
use Arachne\Security\Authentication\Firewall;
use Codeception\TestCase\Test;
use Nette\DI\Container;
use Nette\Http\Session;
use Nette\Security\Identity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class FirewallResolverTest extends Test
{
    protected $tester;

    /**
     * @var ResolverInterface
     */
    private $resolver;

    public function _before()
    {
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.dihelpers.resolvers.tag.arachne.security.firewall');
    }

    public function testIdentityValidator()
    {
        $session = $this->tester->grabService(Session::class);

        $section = $session->getSection('Nette.Http.UserStorage/admin');
        $section->authenticated = true;
        $section->identity = new Identity(1);
        $section->identity->validated = false;

        $firewall = $this->resolver->resolve('admin');

        $this->assertInstanceOf(Firewall::class, $firewall);
        $this->assertTrue($firewall->getIdentity()->validated);
    }
}
