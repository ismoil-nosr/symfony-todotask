<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Task;

class IndexController extends AbstractController
{

    public function index()
    {
    	$tasks = $this->getDoctrine()
	        ->getRepository(Task::class)
	        ->findAll();

        return $this->render('index.html.twig', [
        	'tasks' => $tasks
        ]);
    }

    public function add(Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$task = new Task();
    	$task->setName($request->get('name'));
    	$entityManager->persist($task);
    	$entityManager->flush($task);

    	return new JsonResponse(['id' => $task->getId()]);
    }

	public function update(Task $task, Request $request)
    {
        $task->setName($request->get('name'));
        
	    $entityManager = $this->getDoctrine()->getManager();
	    $entityManager->persist($task);
    	$entityManager->flush($task);

        return new JsonResponse([]);
    }

    public function delete(Task $task, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse([]);
    }

    public function complete(Task $task, Request $request)
    {
		$completed = $request->get('isComplete') == "true" ? 1 : 0;
        $task->setIsCompleted($completed);
        
	    $entityManager = $this->getDoctrine()->getManager();
	    $entityManager->persist($task);
    	$entityManager->flush($task);

        return new JsonResponse([]);
    }
}
