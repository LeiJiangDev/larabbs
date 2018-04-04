<?php

namespace App\Http\Requests;

use App\Models\Topic;

class TopicRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title'         =>  'required|min:2',
                    'body'          =>  'required|min:3',
                    'category_id'   =>  'required|numeric',
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }
    }

    public function messages()
    {
        return [
            'title.min'         =>'标题不能少于两个字符',
            'body.min'          =>'文章内容不能少于三个字符',
        ];
    }


}
