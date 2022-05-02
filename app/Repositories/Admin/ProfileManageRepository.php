<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;
use App\Models\FieldSections;
use App\Models\Fields;
use App\Models\FieldOptions;
use App\Models\UserFields;
use App\Repositories\LanguageRepository;

class ProfileManageRepository {

    public function __construct(
        Fields $fields, 
        FieldSections $fieldSections, 
        FieldOptions $fieldOptions, 
        UserFields $userFields,
        LanguageRepository $langRepo
    ){
        $this->fields        = $fields;
        $this->fieldSections = $fieldSections;
        $this->fieldOptions  = $fieldOptions;
        $this->userFields    = $userFields;
        $this->langRepo      = $langRepo;
    }



    public function edit_field($arr)
    {
        $field = $this->fields->where('id',$arr['id'])->first();

        if(isset($arr['register']))
            $field->on_registration = $arr['register'];
        elseif(isset($arr['search']))
            $field->on_search = $arr['search'];
            
        $field->save();
        return $field;
    }

    public function post_add_section($name)
    {
        $section = clone $this->fieldSections;
        $section->name = $name;
        $section->code = $this->make_code($name);
        $section->save();
        $this->addInLangFile($section->code,$section->name);
        return $section;
    }

    public function post_add_field ($arr) {

        $field = clone $this->fields;

        $field->name            = $arr['new_field'];
        $field->code            = $this->make_code($arr['new_field']);
        $field->section_id      = $arr['section_id'];
        $field->type            = $arr['type'];
        $field->on_registration = $arr['register'];
        $field->on_search       = $arr['search'];
        $field->on_search_type  = $arr['search_type'];
        $field->unit            = $arr['unit'];

        $field->save();

        $this->addInLangFile($field->code,$field->name);
        return $field;
    
    }

    
    public function post_add_field_option ($arr) {

        $option = clone $this->fieldOptions;

        $code             = (isset($arr['unit'])) ? $arr['optiontitle'].$arr['unit'] : $arr['optiontitle'];
        $option->name     = $arr['optiontitle'];
        $option->field_id = $arr["field"];
        $option->code     = $this->make_code($code);
        $option->save();

        $trans_text = (isset($arr['unit'])) 
                        ? $option->name. ' '. $arr['unit']
                        : $option->name;
       
        $this->addInLangFile($option->code,$trans_text);
        return $option;
        
    }

    public function delete_section($id)
    {
        $section = $this->fieldSections->where('id','=',$id)->first();
        foreach($section->fields as $field)
        {
            foreach($field->field_options as $option)
            {
                $option->delete();
            }
            Plugin::fire('custom_section_field_deleted', $field);

            $this->userFields->where('field_id', $field->id)->forceDelete();
            $field->delete();            
        }
        $section->delete();
    }

    public function delete_field($id)
    {
        $field = $this->fields->where('id','=',$id)->first();
        foreach($field->field_options as $option)
        {
            $option->delete();
        }
        Plugin::fire('custom_section_field_deleted', $field);
        $this->userFields->where('field_id', $field->id)->forceDelete();
        $field->delete();
    }

    public function delete_option($id)
    {
        $option = $this->fieldOptions->where('id','=',$id)->first();
        
        $this->userFields->where('value', $option->id)->forceDelete();
        $option->delete();
      
    }

    public function make_code($name)
    {
        //$name = preg_replace('/[^A-Za-z0-9]/', '', $name);        
        $name = strtolower("custom_".$name);
        return $name;
    }

    public function addInLangFile($code,$value)
    {
        $supportedLangs = $this->langRepo->getSupportedLanguages();
        foreach($supportedLangs as $lang) {

            $array = UtilityRepository::languageFileArray($lang, 'custom_profile');
            $data = $this->mergeArray($array, [$code => $value]);
            $string = $this->make_array($data);
            UtilityRepository::saveLanguageFile ($lang, 'custom_profile', $string);
        }        
        
    }


    public function mergeArray($oldArray, $newArray)
    {
        if(is_array($oldArray) && is_array($newArray)) {
            $mergedArray = array_merge($oldArray, $newArray);
            return $mergedArray;
        }

        return [];
    }


    public function make_array($lang)
    {
        $out = '<?php '."\nreturn\n";
        $out .= $this->build_array($lang);
        return $out . ";";
    }


    public function build_array($array)
    {
        return is_array($array) ? var_export($array, true) : var_export([], true);
    }


}