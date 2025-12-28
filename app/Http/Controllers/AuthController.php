<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/login",
     *     summary="Login de Usuário (Admin)",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Authorized"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function loginUser(Request $request)
    {
        return $this->loginWithModel($request, User::class, 'user');
    }

    /**
     * @OA\Post(
     *     path="/customer/login",
     *     summary="Login de Cliente",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="customer@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Authorized"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function loginCustomer(Request $request)
    {
        return $this->loginWithModel($request, Customer::class, 'customer');
    }


    private function loginWithModel(Request $request, $modelClass, string $tokenPrefix)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $modelClass::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $accessToken = $user->createToken("{$tokenPrefix}-token")->plainTextToken;

        $responseData = [
            'status' => 200,
            'message' => 'Authorized',
            'token' => $accessToken,
            'data' => [
                'user' => $user,
            ]
        ];

        // Se for login de User (não Customer), adicionar informações de assinatura
        if ($modelClass === User::class && $user->company_id) {
            $currentSubscription = $user->company->currentSubscription;
            
            $responseData['data']['subscription'] = [
                'hasActiveSubscription' => $currentSubscription ? $currentSubscription->is_active : false,
                'currentSubscription' => $currentSubscription ? [
                    'id' => $currentSubscription->id,
                    'plan_id' => $currentSubscription->plan_id,
                    'plan_name' => $currentSubscription->plan->name ?? null,
                    'start_date' => $currentSubscription->start_date,
                    'end_date' => $currentSubscription->end_date,
                    'status' => $currentSubscription->status,
                ] : null,
            ];
        }

        return response()->json($responseData);
    }


    /**
     * @OA\Get(
     *     path="/check-token-validity",
     *     summary="Verificar validade do token",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token válido",
     *         @OA\JsonContent(
     *             @OA\Property(property="authenticated", type="boolean", example=true),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Token inválido ou ausente")
     * )
     */
    public function checkTokenValidity(Request $request)
    {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user()
        ]);
    }


    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout de Usuário/Cliente",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/user/register",
     *     summary="Registrar nova empresa e usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"company_name","company_fantasy_name","company_email","company_phone","user_name","user_email","user_phone","user_password","user_password_confirmation"},
     *             @OA\Property(property="company_name", type="string", example="Empresa Teste"),
     *             @OA\Property(property="company_fantasy_name", type="string", example="Teste"),
     *             @OA\Property(property="company_email", type="string", format="email", example="contato@empresa.com"),
     *             @OA\Property(property="company_phone", type="string", example="11999999999"),
     *             @OA\Property(property="user_name", type="string", example="João Silva"),
     *             @OA\Property(property="user_email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="user_phone", type="string", example="11999999999"),
     *             @OA\Property(property="user_password", type="string", format="password", example="password123"),
     *             @OA\Property(property="user_password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registro realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Company and user registered successfully"),
     *             @OA\Property(property="token", type="string", example="1|laravel_sanctum_token..."),
     *             @OA\Property(property="company", type="object"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(
     *                 property="subscription",
     *                 type="object",
     *                 @OA\Property(property="hasActiveSubscription", type="boolean", example=false),
     *                 @OA\Property(property="currentSubscription", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Dados inválidos"),
     *     @OA\Response(response=500, description="Erro interno")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_fantasy_name' => 'required|string|max:255|unique:companies,fantasy_name',
            'company_email' => 'required|string|email|max:255|unique:companies,email',
            'company_phone' => 'required|string|max:20',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,email',
            'user_phone' => 'required|string|max:20',
            'user_password' => 'required|string|min:8|max:255|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Gerar slug a partir do nome da empresa
            $slug = Str::slug($data['company_name']);

            // Garantir que o slug seja único
            $originalSlug = $slug;
            $count = 1;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $company = Company::create([
                'name' => $data['company_name'],
                'fantasy_name' => $data['company_fantasy_name'],
                'slug' => $slug,
                'email' => $data['company_email'],
                'phone' => $data['company_phone'],
            ]);

            $user = User::create([
                'name' => $data['user_name'],
                'email' => $data['user_email'],
                'phone' => $data['user_phone'],
                'password' => Hash::make($data['user_password']),
                'company_id' => $company->id,
            ]);

            DB::commit();

            // Buscar assinatura ativa da empresa (se existir)
            $currentSubscription = $company->currentSubscription;

            // Criar token para o usuário
            $token = $user->createToken('user-token')->plainTextToken;
            
            return response()->json([
                'status' => 201,
                'message' => 'Company and user registered successfully',
                'token' => $token,
                'company' => $company,
                'user' => $user,
                'subscription' => [
                    'hasActiveSubscription' => $currentSubscription ? $currentSubscription->is_active : false,
                    'currentSubscription' => $currentSubscription ? [
                        'id' => $currentSubscription->id,
                        'plan_id' => $currentSubscription->plan_id,
                        'plan_name' => $currentSubscription->plan->name ?? null,
                        'start_date' => $currentSubscription->start_date,
                        'end_date' => $currentSubscription->end_date,
                        'status' => $currentSubscription->status,
                    ] : null,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 500,
                'message' => 'Error registering company and user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/user/forgot-password",
     *     summary="Solicitar redefinição de senha (Usuário Admin)",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email de redefinição enviado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Link de redefinição de senha enviado para seu email")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Email não encontrado"),
     *     @OA\Response(response=429, description="Muitas tentativas. Tente novamente mais tarde")
     * )
     */
    public function forgotPasswordUser(Request $request)
    {
        return $this->forgotPassword($request, 'users');
    }

    /**
     * @OA\Post(
     *     path="/customer/forgot-password",
     *     summary="Solicitar redefinição de senha (Cliente)",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="customer@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email de redefinição enviado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Link de redefinição de senha enviado para seu email")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Email não encontrado"),
     *     @OA\Response(response=429, description="Muitas tentativas. Tente novamente mais tarde")
     * )
     */
    public function forgotPasswordCustomer(Request $request)
    {
        return $this->forgotPassword($request, 'customers');
    }

    /**
     * Método privado para processar solicitação de redefinição de senha
     */
    private function forgotPassword(Request $request, string $broker)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Usar o sistema padrão do Laravel que agora vai usar nossa notificação customizada
        $status = Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 200,
                'message' => 'Link de redefinição de senha enviado para seu email'
            ], 200);
        }

        if ($status === Password::INVALID_USER) {
            return response()->json([
                'status' => 404,
                'message' => 'Email não encontrado em nosso sistema'
            ], 404);
        }

        if ($status === Password::RESET_THROTTLED) {
            return response()->json([
                'status' => 429,
                'message' => 'Muitas tentativas. Por favor, aguarde alguns minutos antes de tentar novamente'
            ], 429);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Erro ao processar solicitação. Tente novamente mais tarde'
        ], 500);
    }

    /**
     * @OA\Post(
     *     path="/user/reset-password",
     *     summary="Redefinir senha (Usuário Admin)",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","token","password","password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="token", type="string", example="abc123..."),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha redefinida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Senha redefinida com sucesso")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Token inválido ou expirado"),
     *     @OA\Response(response=422, description="Dados inválidos")
     * )
     */
    public function resetPasswordUser(Request $request)
    {
        return $this->resetPassword($request, 'users');
    }

    /**
     * @OA\Post(
     *     path="/customer/reset-password",
     *     summary="Redefinir senha (Cliente)",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","token","password","password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="customer@example.com"),
     *             @OA\Property(property="token", type="string", example="abc123..."),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha redefinida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Senha redefinida com sucesso")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Token inválido ou expirado"),
     *     @OA\Response(response=422, description="Dados inválidos")
     * )
     */
    public function resetPasswordCustomer(Request $request)
    {
        return $this->resetPassword($request, 'customers');
    }

    /**
     * Método privado para processar redefinição de senha
     */
    private function resetPassword(Request $request, string $broker)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 200,
                'message' => 'Senha redefinida com sucesso'
            ], 200);
        }

        if ($status === Password::INVALID_TOKEN) {
            return response()->json([
                'status' => 400,
                'message' => 'Token inválido ou expirado. Solicite um novo link de redefinição'
            ], 400);
        }

        if ($status === Password::INVALID_USER) {
            return response()->json([
                'status' => 404,
                'message' => 'Email não encontrado em nosso sistema'
            ], 404);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Erro ao redefinir senha. Tente novamente mais tarde'
        ], 500);
    }

}
