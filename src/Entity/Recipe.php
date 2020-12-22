<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @Vich\Uploadable
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
    
     * 
     * @Vich\UploadableField(mapping="recipe_image", fileNameProperty="filename")
     * @ORM\Column(type="string", length=255)
     *  ** @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $steps;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $prepTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $cookTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $servings;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class, mappedBy="recipe")
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity=Quantity::class, mappedBy="recipe")
     */
    private $quantities;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->quantities = new ArrayCollection();
        
            $this->created_at=new \DateTime();
        
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }
 /**
    * Get the value of imageFile
    *
    * @return  File
    */ 
    public function getImageFile()
    {
       return $this->imageFile;
    }
 
    /**
     * Set the value of imageFile
     *
     * @param  File  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
       $this->imageFile = $imageFile;
       if ($this->imageFile instanceof UploadedFile) {
        
         $this->updatedAt = new \DateTime('now');
     }
 
       return $this;
    }
 
    /**
     * Get the value of fileName
     *
     * @return  string
     */ 
    public function getFileName()
    {
       return $this->fileName;
    }
 
    /**
     * Set the value of fileName
     *
     * @param  string  $fileName
     *
     * @return  self
     */ 
    public function setFileName($fileName)
    {
       $this->fileName = $fileName;
 
       return $this;
    }
 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
 
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
 
        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prepTime;
    }

    public function setPrepTime(int $prepTime): self
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getCookTime(): ?int
    {
        return $this->cookTime;
    }

    public function setCookTime(int $cookTime): self
    {
        $this->cookTime = $cookTime;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(int $servings): self
    {
        $this->servings = $servings;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removeRecipe($this);
        }

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
            $quantity->setRecipe($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): self
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getRecipe() === $this) {
                $quantity->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of steps
     */ 
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Set the value of steps
     *
     * @return  self
     */ 
    public function setSteps($steps)
    {
        $this->steps = $steps;

        return $this;
    }
}
