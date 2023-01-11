<?php

    namespace App\Helpers;

    class AppHelpers {
        protected static $resp = [
            'status_code' => null,
            'status' => null,
            'data' => null,
            // 'token' => null,
        ];

        public static function JsonApi($status_code = null, $status = null, $data = null)
        {
            self::$resp['status_code'] = $status_code;
            self::$resp['status'] = $status;
            self::$resp['data'] = $data;
            // self::$resp['token'] = $token;

            return response()->json(self::$resp, self::$resp['status_code']);
        }

        public static function JsonUnauthorized()
        {
            return response()->json([
                'status_code' => 401,
                'status' => 'Unauthorized',
                'data' => [
                    'message' => 'Invalid Access'
                ]
            ]);
        }

        public static function isAdmin($isadmin)
        {
            if ($isadmin == 1) {
                return true;
            } else {
                return false;
            }
        }
    }
