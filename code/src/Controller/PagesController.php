<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path)
    {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function aboutus() {
      // This function should be empty.
    }

    public function browse() {

      // If this is the result of a search...
      if($this->request->getQuery('s') !== null || $this->request->getQuery('t') !== null) {
        $sTerm = ($this->request->getQuery('s'));
        $postsTable = TableRegistry::getTableLocator()->get('Posts');
        $recipesTable = TableRegistry::getTableLocator()->get('Recipes');
        $matchingPosts = $postsTable->find()
          ->select(['id', 'slug', 'title', 'author', 'description'])
          ->where(['title LIKE' => '%' . $sTerm . '%'])
          ->toList();

        $this->set('found_recipes', $matchingPosts);
      }

      // List all tags.
      $tagsTable = TableRegistry::getTableLocator()->get('Tags');
      $allTags = $tagsTable->find()
        ->select(['name', 'genre'])
        ->toList();

      $tagDictionary = [];
      foreach($allTags as $tag) {
        if(!isset($tagDictionary[$tag->genre])) {
          $tagDictionary[$tag->genre] = [$tag->name];
        } else {
          $tagDictionary[$tag->genre][] = $tag->name;
        }
      }

      $this->set('tags', $tagDictionary);
    }
}
