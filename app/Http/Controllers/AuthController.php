<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer as BaconQrCodeWriter;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
/* --------------------------login------------------------------------------ */

    public function login(Request $request)
    {  
           /* -----------------------------------------------metodo de login sin 2FA,funciona ------------------------------------------------------------------------------*/
         $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Failed ! Email or password not matches'], 401);
        }

        return $this->respondWithToken($token); 
    }
  


        /* ------------------------------------------------------------metodo de login con   2FA--------------------------------------------------------------------------------- */
        /*  $this->validateLogin($request);

    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);

        return $this->sendLockoutResponse($request);
    }

    $user = User::where($this->username(), '=', $request->email)->first();

    if (password_verify($request->password, optional($user)->password)) {
        $this->clearLoginAttempts($request);

        $user->update(['token_login' => (new Google2FA)->generateSecretKey()]);

        $urlQR = $this->createUserUrlQR($user);
        
        return response()->json([
            'urlQR' => $urlQR,
            'user' => $user
        ]);
    }
    
    $this->incrementLoginAttempts($request);
    
    return $this->sendFailedLoginResponse($request);

    }   */


    // -------------crear codigo QR para 2FA--------------------
    public function createUserUrlQR($user)
{
    $bacon = new BaconQrCodeWriter(new ImageRenderer(
        new RendererStyle(200),
        new ImagickImageBackEnd()
    ));

    $data = $bacon->writeString(
        (new Google2FA)->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->token_login 
        ), 'utf-8');

    return 'data:image/png;base64,' . base64_encode($data);
}







/* ----------------signup--------------- */
public function signup(Request $request)
    {

        $validated = $request->validate([
            'status' => 'required',
            'tipoUsuario' => 'required',
            
            'name' => 'required',
            'lastname' => 'required',
            'secondlastname' => 'required',

            'grado' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password',

            'validado' => 'required'
        ]);

        $userData = User::create($request->except('password_confirmation'));
        return response()->json(['message' => "User Added", 'userData' => $userData], 200);

    }


// ----------------------eliminar usuario por medio del id-----------------------------------
    public function deleteUser($id)
{
    // Busca el usuario por su ID
    $user = User::find($id);

    // Verifica si el usuario existe
    if ($user) {
        // Elimina el usuario de la base de datos
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
}

/* -------------------------eliminar usuario-por medio de ID-------------------- */
    public function editUser($id,Request $request)
{
    $validated = $request->validate([
        'status' => 'required',
        'tipoUsuario' => 'required',
        'name' => 'required',
        'lastname' => 'required',
        'secondlastname' => 'required',
        'grado' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
        'password' => 'required|confirmed',
        'password_confirmation' => 'required|same:password',
        'validado' => 'required'
    ]);

    $user = User::find($id);
    if ($user) {
        $user->fill($request->except('password_confirmation'));
        
        $user->save();
        return response()->json(['message' => "User Updated", 'userData' => $user], 200);
    } else {
        return response()->json(['message' => "User Not Found"], 404);
    }
}


    public function getAllUsers()
{
    $users = User::all();
    return response()->json($users, 200);
}




    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

     /* ------------------------cierra sesion y elimina el token---------------------- */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh($token)
    {
        return $this->respondWithToken(auth()->refresh());
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
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
