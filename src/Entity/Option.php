<?php
namespace inklabs\kommerce\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Option
{
    use Accessor\Time;

    protected $id;
    protected $name;
    protected $description;
    protected $sortOrder;

    /* @var int */
    protected $type;
    const TYPE_SELECT   = 0;
    const TYPE_RADIO    = 1;
    const TYPE_CHECKBOX = 2;
    const TYPE_TEXT     = 3;
    const TYPE_TEXTAREA = 4;
    const TYPE_FILE     = 5;
    const TYPE_DATE     = 6;
    const TYPE_TIME     = 7;
    const TYPE_DATETIME = 8;

    /* @var Product[] */
    protected $products;

    public function __construct()
    {
        $this->setCreated();
        $this->products = new ArrayCollection;
    }

    public static function getTypeMapping()
    {
        return [
            static::TYPE_SELECT => 'Select',
            static::TYPE_RADIO => 'Radio',
            static::TYPE_CHECKBOX => 'Checkbox',
            static::TYPE_TEXT => 'Text',
            static::TYPE_TEXTAREA => 'Textarea',
            static::TYPE_FILE => 'File',
            static::TYPE_DATE => 'Date',
            static::TYPE_TIME => 'Time',
            static::TYPE_DATETIME => 'Datetime',
        ];
    }

    public function getTypeText()
    {
        return $this->getTypeMapping()[$this->type];
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = (int) $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getView()
    {
        return new View\Option($this);
    }
}
