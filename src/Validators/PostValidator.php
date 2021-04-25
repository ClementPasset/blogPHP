<?php
namespace App\Validators;

use App\Table\PostTable;

class PostValidator extends AbstractValidator{

    protected $data;
    protected $validator;

    public function __construct(array $data,PostTable $table, ?int $postID=null,array $categories)
    {
        parent::__construct($data);
        $this->validator->rule('required',['name','slug']);
        $this->validator->rule('lengthBetween',['name','slug'],5,200);
        $this->validator->rule('slug','slug');
        $this->validator->rule(function($field,$value) use ($table,$postID){
            return !$table->exists($field,$value,$postID);
        },['slug','name'],'Cette valeur existe déjà.');
        $this->validator->rule('subset','categories_ids',array_keys($categories));
    }

}