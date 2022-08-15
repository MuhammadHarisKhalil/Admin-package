<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

trait CommonTrait {

    /**
     * Send Succes Message
     *
     * @param [type] $message
     * @param string $data
     * @return void
     */
    function sendSuccess($message, $data = '') {
        //return Response::json(array('status' => 200, 'successMessage' => $message, 'successData' => $data), 200, []);
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Show error message 
     *
     * @param [type] $error_message
     * @param string $code
     * @param [type] $data
     * @return void
     */
    function sendError($error_message, $code = '', $data = NULL) {
        //return Response::json(array('status' => 400, 'errorMessage' => $error_message), 400);
        return response()->json([
            'status' => 400,
            'message' => $error_message,
            'data' => $data,
        ]);
    }

    /**
     * Attempt the Login
     *
     * @param [type] $credentials
     * @return void
     */
    function auth_attempt($credentials) {
        $user = User::where('email', $credentials['email'])->first();
        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                return true;
            } else {
                return false;
            }
        } else {
            return $this->sendError('Invalid Email', null);
        }
    }

    /**
     * Add Image in local storage
     *
     * @param [type] $file
     * @param [type] $path
     * @return void
     */
    function addFile($file, $path) {
        if ($file) {
            if ($file->getClientOriginalExtension() != 'exe') {
                $type = $file->getClientMimeType();
                if ($type == 'image/jpg' || $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/bmp') {
                    $destination_path = $path;
                    $extension = $file->getClientOriginalExtension();
                    $fileName = Str::random(15) . '.' . $extension;
                    //$img=Image::make($file);
                    if (($file->getSize() / 1000) > 2000) {

                        //Image::make($file)->save('public/'.$destination_path. $fileName, 30);
                        $file->save($destination_path . $fileName, 30);
                        $file_path = $destination_path . $fileName;
                    } else {
                        $file->move($destination_path, $fileName);
                        $file_path = $destination_path . $fileName;}
                    return $file_path;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Send Notification 
     *
     * @param [type] $message
     * @param [type] $data
     * @param [type] $emails
     * @return void
     */
    function send_OneSignal_Notification($message, $data, $emails) {
        $content = array(
            "en" => $message,
        );

        $fields = array(
            'app_id' => env("ONESIGNAL_APP_ID"),
            'include_external_user_ids' => $emails,
            'channel_for_external_user_ids' => 'push',
            'data' => $data,
            'contents' => $content,
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . env("ONESIGNAL_REST_API_KEY")));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        info($response);
        return $response;
    }
}