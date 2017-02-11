<?php

namespace Tests\Unit;

use Arachne\Security\Authorization\Permission;
use Codeception\Test\Unit;
use Eloquent\Phony\Phpunit\Phony;
use Nette\Security\IIdentity;
use Nette\Security\IResource;
use Nette\Security\IRole;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionTest extends Unit
{
    /**
     * @var Permission
     */
    private $permission;

    protected function _before()
    {
        $this->permission = new Permission();
    }

    public function testAllow()
    {
        $identityHandle = Phony::mock(IIdentity::class);

        $resourceHandle = Phony::mock(IResource::class);
        $resourceHandle
            ->getResourceId
            ->returns('resource');

        $roleHandle = Phony::mock(IRole::class);
        $roleHandle
            ->getRoleId
            ->returns('role');

        $identity = $identityHandle->get();
        $resource = $resourceHandle->get();
        $role = $roleHandle->get();

        $assertCallback = Phony::stub();
        $assertCallback
            ->returns(true);

        $this->permission->addResource('resource');
        $this->permission->addRole('role');
        $this->permission->allow('role', 'resource', 'privilege', $assertCallback);

        $this->permission->setIdentity($identity);

        $this->assertTrue($this->permission->isAllowed($role, $resource, 'privilege'));

        $assertCallback
            ->calledWith($identity, $resource, $role);
    }

    public function testDeny()
    {
        $identityHandle = Phony::mock(IIdentity::class);

        $resourceHandle = Phony::mock(IResource::class);
        $resourceHandle
            ->getResourceId
            ->returns('resource');

        $roleHandle = Phony::mock(IRole::class);
        $roleHandle
            ->getRoleId
            ->returns('role');

        $identity = $identityHandle->get();
        $resource = $resourceHandle->get();
        $role = $roleHandle->get();

        $assertCallback = Phony::stub();
        $assertCallback
            ->returns(true);

        $this->permission->addResource('resource');
        $this->permission->addRole('role');
        $this->permission->allow('role', 'resource');
        $this->permission->deny('role', 'resource', 'privilege', $assertCallback);

        $this->permission->setIdentity($identity);

        $this->assertFalse($this->permission->isAllowed($role, $resource, 'privilege'));

        $assertCallback
            ->calledWith($identity, $resource, $role);
    }
}
