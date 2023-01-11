<?php 
namespace App\Helpers;
class APIHelpers{
    public static function createAPIResponse($is_error,$code,$message,$content,$status){
        $result= [];
        if($is_error){
            $result['success']=false;
            $result['code']=$code;
            $result['message']=$message;
        }else{
            $result['success']=true;
            $result['code']=$code;
            if($content==null){
                $result['message']=$message;
                $result['status']=$status;
            }else{
                $result['data']=$content;
                $result['status']=$status;
            }
        }
        return $result;
    }

}
