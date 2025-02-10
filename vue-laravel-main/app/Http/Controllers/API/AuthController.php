<?php

namespace App\Http\Controllers\API;
  
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

  
class AuthController extends BaseController
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'address'=> 'required',
            'email' => 'required|email',
            'password' => 'required',
          
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['user'] =  $user;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = Auth::attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
  
        $success = $this->respondWithToken($token);
   

        // Set a cookie with the token
    return $this->sendResponse($success, 'User login successfully.')
    ->cookie('token', $token, config('jwt.ttl') * 60);
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function    getUser()
    {
        $success = Auth::user();
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();


        // Remove the cookie
        $cookie = Cookie::forget('access_token');
        
        return response()->json([
            'success'=> true,
            'message' => 'Successfully logged out.'
        ])->withCookie($cookie);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(Auth::refresh());
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => config('jwt.ttl') * 60
    ]);
}

/**
 * Change the password
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->input('current_password'), $user->password)) {
        return response()->json(['message' => 'Invalid current password'], 400);
    }

    $user->password = Hash::make($request->input('new_password'));
    $user->save();

    return response()->json(['message' => 'Password changed successfully']);
}

/**
 * Change user email.
 * 
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 * 
 */

 public function changeEmail(Request $request)
 {
     $user = Auth::user();

     // Validate the request data
     $validator = Validator::make($request->all(), [
         'new_email' => 'required|email|unique:users,email,' . $user->id,
     ]);

     if ($validator->fails()) {
         return $this->sendError('Validation Error.', $validator->errors(), 422); // Return a 422 status code for validation errors
     }

     try {
         // Update the user's email
         $user->email = $request->input('new_email');
         $user->save();

         return $this->sendResponse([], 'Email changed successfully');
     } catch (\Exception $e) {
         // Handle any exceptions that occur during the update process
         return $this->sendError('Failed to change email.', ['error' => $e->getMessage()], 500);
     }
 }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $user = User::where('email', $request->email)->first();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return $this->sendResponse([], 'Password reset link sent to your email.');
        } else {
            return $this->sendError('Email Sending Failed', ['error' => __($status)], 500);
        }


        
    }
    public function updateUser(Request $request)
  {
      $user = Auth::user(); // Get the authenticated user

      // Validate the request data
      $request->validate([
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'address' => 'nullable|string|max:255', // Address is optional (nullable)
      ]);

      try {
          // Update the user's attributes
          $user->first_name = $request->input('first_name');
          $user->last_name = $request->input('last_name');
          $user->address = $request->input('address'); // Assign null if address is empty
          $user->save();  // Save the changes to the database

          return response()->json([
              'status' => 'success',
              'message' => 'Profile updated successfully',
              'data' => $user
          ]);
      } catch (\Exception $e) {
          // Handle any exceptions that occur during the update process
          return response()->json([
              'status' => 'error',
              'message' => 'Failed to update profile: ' . $e->getMessage(),
          ], 500); // Return a 500 Internal Server Error status code
      }
  }

}
