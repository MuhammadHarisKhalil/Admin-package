<?php

namespace App\Http\Controllers\ApiController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Traits\CommonTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use CommonTrait;

    /**
     * Register User
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
            'image'   => 'required|image'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }
        try {
            DB::beginTransaction();

            $check = User::where('email', $request->email)->first();
            if ($check) {
                return $this->sendError('Email already exists', NULL);
            }

            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->image = $this->addFile($request->image, 'uploads/users/');
            $user->save();

            DB::commit();
            return $this->sendSuccess('Successfully registered', $user);
        } catch (\Exception $exception) {
            DB::rollback();
            if (('APP_ENV') == 'local') {
                dd($exception);
            } else {
                return $this->sendError($exception->getMessage(), null);
            }
        }
    }

    /**
     * Login User
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->status == 'inactive') {
                return $this->sendError('Please verify your email to continue.', null);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $data['access_token'] = $tokenResult->accessToken;
            $data['token_type'] = 'Bearer';
            $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
            $data['user'] = $user;

            //Generate Hash Key 
            $hashKey = hash_hmac('sha256', $user->email, env("ONESIGNAL_APP_ID"));
            $data['oneSignalHash'] = $hashKey;

            return $this->sendSuccess('Successfully Loggedin', $data);
        }
        return $this->sendError('Incorrect Password.', null);
    }

    /**
     * Logout User
     * 
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->token()->revoke()) {
                return $this->sendSuccess('Logout successfully!', null);
            } else {
                return $this->sendError('Failed To Logout');
            }
        }
        return $this->sendError('User not found.', null);
    }

    /**
     * Change Password
     *
     * @param Request $request
     * @return void
     */
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }
        try {
            DB::beginTransaction();
            $user = Auth()->user();
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->new_password, $user->password)) {
                    return $this->sendError('New Password Should be Different from Old', null);
                } else {
                    $user->update([
                        'password' => Hash::make($request['new_password']),
                    ]);
                    DB::commit();
                    return $this->sendSuccess('Password Changed Successfully', $user);
                }
            } else {
                return $this->sendError('Old Password Do not Match', null);
            }
            $user->update();
        } catch (\Exception $exception) {
            DB::rollback();
            if (('APP_ENV') == 'local') {
                dd($exception);
            } else {
                return $this->sendError($exception->getMessage(), null);
            }
        }
    }

    /**
     * Update Profile
     *
     * @param Request $request
     * @return void
     */
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'email' => 'required'
        ]);
        try {
            DB::beginTransaction();

            $user = Auth()->user();
            $user->name = $request->name;
            $user->image = $this->addFile($request->image, 'uploads/users/');
            $user->email = $request->email;
            $user->update();

            DB::commit();
            return $this->sendSuccess('Successfully Updated', $user);
        } catch (\Exception $exception) {
            DB::rollback();
            if (('APP_ENV') == 'local') {
                dd($exception);
            } else {
                return $this->sendError($exception->getMessage(), null);
            }
        }
    }

    /**
     * Get User Profile
     *
     * @return void
     */
    public function get_user_profile()
    {
        $user = Auth()->user();
        return $this->sendsuccess('Get User Profile', $user);
    }

    /**
     * Social Login
     *
     * @param Request $request
     * @return void
     */
    public function social_login(Request $request)
    {
        //    return $request->all();
        $request->validate([
            'name' => 'required',
            'email' => 'sometimes',
            'google_id' => 'sometimes',
            'facebook_id' => 'sometimes',
            'is_google' => 'sometimes',
            'is_facebook' => 'sometimes',
            'is_apple' => 'sometimes',
            'apple_id' => 'sometimes'
        ]);
        DB::beginTransaction();
        try {
            if ($request->is_apple && $request->apple_id) {
                $user = User::where('apple_id', $request->apple_id)->first();
                if ($user) {
                    if ($user->status == 'inactive') {
                        return $this->sendError('Please verify your email to continue.', null);
                    } else if ($user->status == 'pending') {
                        return $this->sendError('Your account is pending approval from admin', null);
                    } else if ($user->status == 'block') {
                        return $this->sendError('Your account has been blocked.', null);
                    } else if ($user->type != $request->type) {
                        return $this->sendError('Your account has been registerd as a ' . $user->type, null);
                    }
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    $token->save();
                    $hashKey = hash_hmac('sha256', $user->email, env("ONESIGNAL_APP_ID"));

                    $data['access_token'] = $tokenResult->accessToken;
                    $data['token_type'] = 'Bearer';
                    $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                    $data['user'] = $user;
                    $data['oneSignalHash'] = $hashKey;
                    DB::commit();
                    return response()->json(['statusCode' => 200, 'Message' => 'User Login Successfully', 'Data' => $data]);
                } else {
                    $user = User::where('email', $request->email)->first();
                    if ($user) {
                        $user['apple_id'] = $request->apple_id;
                        $user->update();
                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->token;
                        $token->save();
                        $hashKey = hash_hmac('sha256', $user->email, env("ONESIGNAL_APP_ID"));

                        $data['access_token'] = $tokenResult->accessToken;
                        $data['token_type'] = 'Bearer';
                        $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                        $data['user'] = $user;
                        $data['oneSignalHash'] = $hashKey;
                        DB::commit();
                        return response()->json(['statusCode' => 200, 'Message' => 'User Login Successfully', 'Data' => $data]);
                    } else {
                        $user_data['name'] = $request->name;
                        $user_data['email'] = $request->email;
                        $user_data['apple_id'] = $request->apple_id;
                        $user_data['social_platform'] = 'apple';
                        $user_data['status'] = 'active';

                        $user = User::create($user_data);
                        // $user->assignRole('User');
                        $hashKey = hash_hmac('sha256', $request->email, env("ONESIGNAL_APP_ID"));
                        // -----Token After Registration
                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->token;
                        $token->save();
                        $data['access_token'] = $tokenResult->accessToken;
                        $data['token_type'] = 'Bearer';
                        $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                        $data['user'] = $user;
                        $data['oneSignalHash'] = $hashKey;

                        DB::commit();
                        return response()->json(['statusCode' => 200, 'Message' => 'User Login Successfully', 'Data' => $data]);
                    }
                }
            } else {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    if ($user->status == 'inactive') {
                        return $this->sendError('Please verify your email to continue.', null);
                    } else if ($user->status == 'pending') {
                        return $this->sendError('Your account is pending approval from admin', null);
                    } else if ($user->status == 'block') {
                        return $this->sendError('Your account has been blocked.', null);
                    } else if ($user->type != $request->type) {
                        return $this->sendError('Your account has been registerd as a ' . $user->type, null);
                    }
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    $token->save();
                    $hashKey = hash_hmac('sha256', $user->email, env("ONESIGNAL_APP_ID"));

                    $data['access_token'] = $tokenResult->accessToken;
                    $data['token_type'] = 'Bearer';
                    $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                    $data['user'] = $user;
                    $data['oneSignalHash'] = $hashKey;
                    DB::commit();
                    return response()->json(['statusCode' => 200, 'Message' => 'User Login Successfully', 'Data' => $data]);
                } else {
                    if ($request->has('avatar')) {

                        $image = $request->file('avatar');
                        $extension = $image->getClientOriginalExtension();
                        $name = Str::random(15);
                        $folder = '/uploads/profiles/';
                        $filePath = $folder . $name . '.' . $extension;
                        $destinationPath = public_path($folder);
                        $image->move($destinationPath, $name . '.' . $extension);
                        $user_data['image'] = $filePath;
                    } else {
                        $user_data['image'] = null;
                    }
                    $user_data['name'] = $request->name;
                    $user_data['email'] = $request->email;
                    $user_data['status'] = 'active';
                    if ($request->is_google) {
                        $user_data['social_platform'] = 'google';
                        $user_data['google_id'] = $request->google_id;
                    } elseif ($request->is_facebook) {
                        $user_data['social_platform'] = 'facebook';
                        $user_data['facebook_id'] = $request->facebook_id;
                    }
                    $user = User::create($user_data);
                    // $user->assignRole('User');
                    // ------Token after register
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    $token->save();
                    $hashKey = hash_hmac('sha256', $user->email, env("ONESIGNAL_APP_ID"));

                    $data['access_token'] = $tokenResult->accessToken;
                    $data['token_type'] = 'Bearer';
                    $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                    $data['user'] = $user;
                    $data['oneSignalHash'] = $hashKey;
                    DB::commit();
                    return response()->json(['statusCode' => 200, 'Message' => 'User Login Successfully', 'Data' => $data]);
                }
            }
        } catch (\Exception $exception) {
            DB::rollback();
            if (('APP_ENV') == 'local') {
                dd($exception);
            } else {
                return response()->json(['statusCode' => 500, 'Message' => 'Database Error Contact Support', 'Data' => '']);
            }
        }
    }

    /**
     * Verify Email
     *
     * @param Request $request
     * @return void
     */
    public function verify_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
        if ($user) {
            $user->email_verified_at = Carbon::now();
            if ($user->type == 'business') {
                $user->status = 'pending';
            }
            $user->otp = NULL;
            $user->save();
            return $this->sendSuccess('Email Verified Successfully', null);
        } else {
            return $this->sendError('Invalid code. Check your email and try again', null);
        }
    }

    /**
     * Send OTP forgot Password
     *
     * @param Request $request
     * @return void
     */
    public function send_forgot_password_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }

        $otp = mt_rand(1000, 9999);

        $user = User::where('email', $request->email)->first();
        $user->otp = $otp;
        $user->save();

        //Send OTP to Email
        try {
            Mail::to($request->email)->send(new ForgotPassword($user->name, $otp));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }

        return $this->sendSuccess('Forgot Password OTP sent successfully', null);
    }

    /**
     * Forgot Password Verifiy OTP
     *
     * @param Request $request
     * @return void
     */
    public function forgot_password_verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'otp' => 'required|exists:users,otp',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
        //$user = User::where('otp', $request->otp)->first();
        if ($user) {
            return $this->sendSuccess('Code verified successfully.', null);
        } else {
            return $this->sendError('Error. Invalid Code.', null);
        }
    }

    /**
     * Set New Password
     *
     * @param Request $request
     * @return void
     */
    public function set_new_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'new_password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendSuccess('Password changed successfully.', $user);
    }

    /**
     * Resend email Verfication
     *
     * @param Request $request
     * @return void
     */
    public function resend_email_verification_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first(), null);
        }

        $otp = mt_rand(1000, 9999);
        $user = User::where('email', $request->email)->first();
        $user->otp = $otp;
        $user->save();

        try {
            Mail::to($request->email)->send(new EmailVerification($user->name, $otp));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }

        return $this->sendSuccess('Email Verification OTP sent Successfully.', null);
    }
}
