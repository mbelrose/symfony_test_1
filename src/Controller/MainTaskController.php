<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\TaskType;

/**
 * @Route("/task")
 */
class MainTaskController extends Controller {
    
    /**
     * static new task
     * name and date
     */
    
    /**
     * @Route("/create", name="create_rt")
     */
    public function createAction(Request $request) {
        
        $task = new Task;
        $task->setTask('Write task here.');
        $task->setDueDate(new \DateTime('tomorrow'));
        
        $form = $this->createForm(TaskType::class, $task, ['validation_groups' => 'create']);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('saveAndAdd')->isClicked()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
                //return $this->redirectToRoute('task_saved');
                return $this->render('generic.html.twig', ['text' => 'task_saved']);
            } else {
                //return $this->redirectToRoute('task_edited');
                return $this->render('generic.html.twig', ['text' => 'task_edited']);
            }
        } else {
            return $this->render('/task/create.html.twig', [
                    'form' => $form->createView()
                ]);
        }
        
        
        
    }
    
}

