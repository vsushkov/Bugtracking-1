<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Projects", mappedBy="author", orphanRemoval=true)
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        //return $this;
    }
    
    public function getRoles() : array
    {
       $roles = $this -> roles;
       $roles [] = 'ROLE_USER' ;

       return array_unique ( $roles );
    }

//    public function setRoles()
//    {
//        return $this->roles;
//    }

//    public function getRoles()
//    {
//        return array('ROLE_USER','ROLE ADMIN');
//    }

    public function getSalt()
    {
        return null;
    }

     public function eraseCredentials() { }

     /**
      * @return Collection|Projects[]
      */
     public function getProjects(): Collection
     {
         return $this->projects;
     }

     public function addProject(Projects $project): self
     {
         if (!$this->projects->contains($project)) {
             $this->projects[] = $project;
             $project->setAuthor($this);
         }

         return $this;
     }

     public function removeProject(Projects $project): self
     {
         if ($this->projects->contains($project)) {
             $this->projects->removeElement($project);
             // set the owning side to null (unless already changed)
             if ($project->getAuthor() === $this) {
                 $project->setAuthor(null);
             }
         }

         return $this;
     }
}
