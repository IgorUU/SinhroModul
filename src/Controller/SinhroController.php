<?php

namespace Drupal\sinhromodul\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class SinhroController extends ControllerBase {

    public function newpage() {
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
            '#title' => 'Updejtovan kontent'
        ];
    }
    
};