<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\EntityDTO\Builder\ShipmentCommentDTOBuilder;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class ShipmentComment implements IdEntityInterface, ValidationInterface
{
    use IdTrait, TimeTrait;

    /** @var string */
    protected $comment;

    /** @var Shipment */
    protected $shipment;

    /**
     * @param Shipment $shipment
     * @param string $comment
     */
    public function __construct(Shipment $shipment, $comment)
    {
        $this->setId();
        $this->setCreated();
        $this->comment = (string) $comment;

        $shipment->addShipmentComment($this);
        $this->shipment = $shipment;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('comment', new Assert\NotBlank);
        $metadata->addPropertyConstraint('comment', new Assert\Length([
            'max' => 65535,
        ]));
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setShipment(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function getDTOBuilder()
    {
        return new ShipmentCommentDTOBuilder($this);
    }
}
