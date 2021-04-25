<?php
namespace App\Table;

use App\Model\Post;
use App\PaginatedQuery;

final class PostTable extends Table{

    protected $table='post';
    protected $class=Post::class;

    public function findPaginated()
    {
        $paginatedQuery=new PaginatedQuery("SELECT * FROM {$this->table} ORDER BY created_at DESC","SELECT count(id) FROM {$this->table}",$this->pdo);
        $posts=$paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$paginatedQuery];
    }

    public function findPaginatedForCategory(int $categoryID)
    {
        $paginatedQuery=new PaginatedQuery("SELECT p.* 
            FROM {$this->table} p 
            JOIN post_category pc ON p.id=pc.post_id 
            WHERE pc.category_id={$categoryID} 
            ORDER BY created_at DESC",
            "SELECT count(category_id) 
            FROM post_category
            WHERE category_id={$categoryID}"
        );
        $posts=$paginatedQuery->getItems($this->class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$paginatedQuery];
    }

    public function updatePost(Post $post, array $categories):void
    {
        $this->pdo->beginTransaction();
        $this->update([
            'id'=>$post->getID(),
            'name'=>$post->getName(),
            'slug'=>$post->getSlug(),
            'content'=>$post->getContent(),
            'created_at'=>$post->getCreatedAt()->format('Y-m-d H:i:s')
        ],$post->getID());
        $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$post->getID()}");
            $query = $this->pdo->prepare("INSERT INTO post_category (post_id,category_id) VALUES ({$post->getID()},:categoryID)");
        foreach($categories as $category){
            $query->execute(['categoryID'=>$category]);
        }
        $this->pdo->commit();
    }

    public function createPost(Post $post,array $categories):void
    {
        $this->pdo->beginTransaction();
        $id=$this->create([
            'name'=>$post->getName(),
            'slug'=>$post->getSlug(),
            'content'=>$post->getContent(),
            'created_at'=>$post->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
        $post->setID($id);
        $query=$this->pdo->prepare("INSERT INTO post_category (post_id, category_id) VALUES({$id},:categoryID)");
        foreach($categories as $category){
            $query->execute(['categoryID'=>$category]);
        }
        $this->pdo->commit();
    }

}