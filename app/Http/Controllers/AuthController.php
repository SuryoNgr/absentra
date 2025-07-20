<?php
    namespace App\Http\Controllers ;

    use App\Http\Requests\UserAuthVerifyRequest;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\View\View;
    use Illuminate\Support\Facades\Log;


    class AuthController extends Controller
    {
        public function index() : View
        {
            return view ('auth.login');
        }

        public function verify(UserAuthVerifyRequest $request): RedirectResponse
        {
            $data = $request->validated();

            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                $request->session()->regenerate();
                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/home');
                }

                if ($user->role === 'supervisor') {
                    if ($user->must_change_password) {
                        return redirect()->route('supervisor.password.change');
                    }
                    return redirect()->intended('/supervisor/home');
                }

                // Tambahkan redirect untuk role lain jika ada
                return redirect()->intended('/');
            }

            return redirect(route('login'))->with('msg', 'Email atau Password salah');
        }


       public function logout(): RedirectResponse
            {
                Auth::logout();
                return redirect(route('login'));
            }

        }
