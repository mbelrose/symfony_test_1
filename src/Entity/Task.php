<?php

namespace App\Entity;

use App\Entity\TaskCategory;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity = "TaskCategory", inversedBy="tasks")
     * @ORM\JoinColumn(name="category_id")
     * @Assert\Type(type="App\Entity\TaskCategory")
     * @Assert\Valid()
     */
    private $taskCategory;

    /**
     * @Assert\NotBlank(message = "Please enter a task",groups={"create"})
     * @Assert\Length(max=5,groups={"create"}, maxMessage="less than 5")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $task;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $dueDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(?string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getTaskCategory(): ?TaskCategory
    {
        return $this->taskCategory;
    }

    public function setTaskCategory(?TaskCategory $taskCateogry): self
    {
        $this->taskCateogry = $taskCateogry;

        return $this;
    }

}
