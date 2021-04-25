<?php
namespace App\HTML;

use DateTimeInterface;

class Form{

    private $data;
    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data=$data;
        $this->errors=$errors;
    }

    public function input(string $key,string $label):string
    {
        $type = ($key==="password") ? "password" : "text";
        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <input type="{$type}" class="{$this->getInputClass($key)}" name="{$key}" id="field{$key}" required value="{$this->getValue($key)}">
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }
    public function textarea(string $key,string $label):string
    {
        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <textarea type="text" class="{$this->getInputClass($key)}" name="{$key}" id="field{$key}" required >{$this->getValue($key)}</textarea>
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }

    public function select(string $key, string $label, array $options=[]):string
    {
        $optionsHTML=[];
        foreach($options as $k => $option){
            $value=$this->getValue($key);
            $selected=in_array($k,$value) ? 'selected' : '';
            $optionsHTML[]="<option value=\"{$k}\" $selected>{$option}</option>";
        }
        $optionsHTML=implode('',$optionsHTML);
        return <<<HTML
        <div class="form-group">
            <label for="field{$key}">{$label}</label>
            <select multiple class="{$this->getInputClass($key)}" name="{$key}[]" id="field{$key}" required >$optionsHTML</select>
            {$this->getErrorFeedback($key)}
        </div>
HTML;
    }

    private function getValue(string $key)
    {
        if(is_array($this->data)){
            return $this->data['key'] ?? null;
        }
        $method='get' . str_replace(' ','',ucwords(str_replace('_',' ',$key)));
        $value=$this->data->$method();
        if($value instanceof DateTimeInterface){
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }

    private function getInputClass($key):string
    {
        $inputClass="form-control";
        if(!empty($this->errors[$key])){
            $inputClass.=" is-invalid";
        }
        return $inputClass;
    }
    private function getErrorFeedback($key):string
    {
        if(!empty($this->errors[$key])){
            return '<div class="invalid-feedback">' . implode('<br>',$this->errors[$key]) . '</div>';
        }
        return '';
    }

}