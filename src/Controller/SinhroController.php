<?php

namespace Drupal\sinhromodul\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class SinhroController extends ControllerBase {

    public function posts() {
        $data = file_get_contents('https://jsonplaceholder.typicode.com/posts');
        $posts = json_decode($data, TRUE);
        // dsm($posts);

        foreach($posts as $post) {
            $values = \Drupal::entityQuery('node')->condition('field_id', $post['id'])->execute();
            $node_not_exists = empty($values);

            if ($node_not_exists) {
                $node = \Drupal::entityTypeManager()->getStorage('node')->create([
                    'type' => 'posts',
                    'title' => $post['title'],
                    'body' => $post['body'],
                    'field_id' => $post['id'],
                    'field_userid' => $post['userId']
                ]);
                $node->save();
            } else {
                $nid = reset($values);
                
                $node = Node::load($nid);
                $node->setTitle($post['title']);
                $node->set('body', $post['body']);
                $node->set('field_id', $post['id']);
                $node->set('field_userid', $post['userId']);
                $node->save();
            }
        }

        return [
            '#markup' => '<h1>Updejtovani postovi! </h1>'
        ];
    }

    public function tasks() {
        $data = file_get_contents('https://jsonplaceholder.typicode.com/todos');
        $tasks = json_decode($data, TRUE);
        //dsm($tasks); //userId, id, title, completed

        foreach($tasks as $task) {
            $values = \Drupal::entityQuery('node')->condition('field_task_id', $task['id'])->execute();
            $node_not_exists = empty($values);              //Proveravamo da li već postoji node gde se 'field_task_id' i $task['id'] poklapaju.

            $query = \Drupal::entityQuery('user');
            $uids = $query->execute();
            $assigned = '';

            if($node_not_exists) {

                // if(in_array($task['userId'], $uids)){
                //     $assigned = 'To this user';
                // } else {
                //     $assigned = 'Assigned to administrator';
                // };

                $node = \Drupal::entityTypeManager()->getStorage('node')->create([
                    'type' => 'tasks',
                    'field_user' => $task['userId'],
                    'field_task_id' => $task['id'],
                    'title' => $task['title'],
                    'field_completed' => $task['completed'],
                    'field_assigned' => $assigned
                ]);
                $node->save();
            } else {
                $nid = reset($values);

                $node = Node::load($nid);
                $node->set('title', $task['title']);
                $node->set('field_task_id', $task['id']);
                $node->set('field_user', $task['userId']);
                $node->set('field_completed', $task['completed']);
                $node->set('field_assigned', $assigned);
                $node->save();
            }
        }
        return [
            '#markup' => '<h1>Updejtovani taskovi! </h1>'
        ];
    }
    
};