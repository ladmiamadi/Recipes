<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @apiResource(
 *    normalizationContext={"groups"={"ingrédient:read"}},
 *     denormalizationContext={"groups"={"ingrédient:write"}}
 * )
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"ingrédient:read", "ingrédient:write"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, inversedBy="ingredients")
     * @Groups("ingrédient:read")
     */
    private $recipe;

    /**
     * @ORM\OneToMany(targetEntity=Quantity::class, mappedBy="ingredient")
     *  @Groups("ingrédient:read")
     */
    private $quantities;

    public function __construct()
    {
        $this->recipe = new ArrayCollection();
        $this->quantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipe(): Collection
    {
        return $this->recipe;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipe->contains($recipe)) {
            $this->recipe[] = $recipe;
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        $this->recipe->removeElement($recipe);

        return $this;
    }

    /**
     * @return Collection|Quantity[]
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(Quantity $quantity): self
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities[] = $quantity;
            $quantity->setIngredient($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): self
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getIngredient() === $this) {
                $quantity->setIngredient(null);
            }
        }

        return $this;
    }
}
