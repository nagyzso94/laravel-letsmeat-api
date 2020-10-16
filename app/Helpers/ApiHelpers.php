<?php

namespace App\Helpers;

  class ApiHelpers {
      public static function createApiResponse($is_error,$code,$message,$content){
        $result = [];

        if ($is_error) {
          $result['success'] = false;
          $result['code'] = $code;
          $result['message'] = $message;
        } else {
          $result['success'] = true;
          $result['code'] = $code;

          if($content == null){
            $result['message'] = $message;
          } else {
            $result['data'] = $content;
          }
        }

        return $result;

      }

  }
