<?php
namespace inklabs\kommerce\Event;

use inklabs\kommerce\Lib\Event\EventInterface;
use inklabs\kommerce\Lib\UuidInterface;

class PasswordChangedEvent implements EventInterface
{
    /** @var UuidInterface */
    private $userId;

    /** @var string */
    private $email;

    /** @var string */
    private $fullName;

    public function __construct(UuidInterface $userId, string $email, string $fullName)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->fullName = $fullName;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}
