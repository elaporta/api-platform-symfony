<?php

namespace App\Entity;

use App\Repository\DragonTreasureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use function Symfony\Component\String\u;

// metadata
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
// use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Link;

// filters
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

use ApiPlatform\Serializer\Filter\PropertyFilter;

// validators 
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]

// affects swagger doc
#[ApiResource(
    shortName: 'Treasure', // entity name
    description: 'A rare and valuable treasure.', // entity description
    operations: [ // force which methods will appear
        new Get(
            uriTemplate: 'treasures/{id}/info', // change method url
            normalizationContext: [
                'groups' => ['treasure:read', 'treasure:item:get'] // override normalization group in get method
            ]
        ),
        new GetCollection(),
        new Post(),
        new Patch(),
        // new Delete() // skipped method
    ],
    normalizationContext: [
        'groups' => ['treasure:read'] // force which read-attrubutes will appear
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'] // force which read-attrubutes will appear
    ],
    paginationItemsPerPage: 10
)]

// affect filters available
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]

// add a properties section to filter specific properties and not whole object
#[ApiFilter(PropertyFilter::class)]

// filter by a specific property of owner relationship
#[ApiFilter(SearchFilter::class, properties: ['owner.username' => 'partial'])]

// extra and custom API sub-resource
#[ApiResource(
    uriTemplate: '/users/{user_id}/treasures.{_format}',
    shortName: 'Treasure',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromClass: User::class,
            fromProperty: 'dragonTreasures',
            description: 'User identifier'
        )
    ],
    normalizationContext: [
        'groups' => ['treasure:read'] // force which read-attrubutes will appear
    ]
)]

class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['treasure:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write', 'user:read'])] // specific in which group will appear
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Assert\NotBlank] // input validation: not blank
    #[Assert\Length(min: 2, max: 50, maxMessage: 'Describe your loot in 50 characters or less')] // input validation: lenght
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read', 'user:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write', 'user:read'])]
    #[ApiFilter(RangeFilter::class)]
    #[Assert\GreaterThanOrEqual(0)] // input validation: greater or equal
    private ?int $value = 0;

    // adds a description in the attribute
    /**
     * Estimated value of the treasure, in gold coins.
    */
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write', 'user:read'])]
    #[Assert\GreaterThanOrEqual(0)] // input validation: greater or equal
    #[Assert\LessThanOrEqual(10)] // input validation: less or equal
    private ?int $coolFactor = 0;

    #[ORM\Column]
    private bool $isPublished = false;

    #[ORM\Column]
    #[Groups(['treasure:read'])]
    #[ApiFilter(BooleanFilter::class)] // another way to set up a filter
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['treasure:read'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'dragonTreasures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[Assert\Valid]
    private ?User $owner = null;

    // Construct method
    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups(['treasure:read'])]
    public function getShortDescription(): ?string
    {
        return u($this->description)->truncate(40, '...');
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    // custom method - It appears as a valid attribute in POST/PATCH
    #[Groups(['treasure:write'])]
    #[SerializedName('description')] // name alias
    public function setTextDescription(string $description): static
    {
        $this->description = nl2br($description);
        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;
        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }
}
