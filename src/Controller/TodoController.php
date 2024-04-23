<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter un cle',
                'cours' => 'lire un livre',
                'correction' => 'corriger les exercice',
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "we just created the initial list");
        }
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $this->addFlash('error', "the todo with the id $name already exists!!");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "the todo with the id $name was added successfully!!");
                $session->set('todos', $todos);
            }
        } else {
            $this->addFlash('error', "there is a problem here");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "the todo with the id $name does not exist");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "the todo with the id $name was updated successfully!!");
            }
        } else {
            $this->addFlash('error', "there is a problem here");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "the todo with the id $name does not exist");
            } else {
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "the todo with the id $name was deleted successfully!!");
            }
        } else {
            $this->addFlash('error', "there is a problem here");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('app_todo');
    }
}
