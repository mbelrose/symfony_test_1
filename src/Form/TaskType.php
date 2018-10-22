<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Form\TaskCategoryType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TaskType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        // parent::buildForm($builder, $options);
        
        $builder
            ->add('task')
            ->add('dueDate')
            ->add('taskCategory', TaskCategoryType::class)
            ->add('keepEditing', FormTypes\SubmitType::class, [
                'label' => 'Keep Editing'
            ])
            ->add('saveAndAdd', FormTypes\SubmitType::class, [
                'label' => 'Create Task'
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        //parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Task::class
        ]);
    }
}
