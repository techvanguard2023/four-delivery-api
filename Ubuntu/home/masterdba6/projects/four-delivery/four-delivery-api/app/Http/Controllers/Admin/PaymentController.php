<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Controlador responsável pelo gerenciamento de pagamentos
 */
class PaymentController extends Controller
{
    /**
     * Lista todos os pagamentos com paginação
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $payments = Payment::paginate($perPage);
            
            return response()->json($payments);
        } catch (Exception $e) {
            Log::error('Erro ao listar pagamentos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar pagamentos'], 500);
        }
    }

    /**
     * Cria um novo registro de pagamento
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'amount' => 'required|numeric|min:0.01',
                'status' => 'required|in:pending,completed,cancelled'
            ]);

            $payment = Payment::create($validatedData);
            Log::info('Novo pagamento criado', ['payment_id' => $payment->id]);

            return response()->json($payment, 201);
        } catch (Exception $e) {
            Log::error('Erro ao criar pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar pagamento'], 500);
        }
    }

    /**
     * Exibe um pagamento específico
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function show(Payment $payment)
    {
        try {
            return response()->json($payment);
        } catch (Exception $e) {
            Log::error('Erro ao exibir pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar pagamento'], 500);
        }
    }

    /**
     * Atualiza um pagamento existente
     *
     * @param Request $request
     * @param Payment $payment
     * @return JsonResponse
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'sometimes|exists:orders,id',
                'payment_method_id' => 'sometimes|exists:payment_methods,id',
                'amount' => 'sometimes|numeric|min:0.01',
                'status' => 'sometimes|in:pending,completed,cancelled'
            ]);

            $payment->update($validatedData);
            Log::info('Pagamento atualizado', ['payment_id' => $payment->id]);

            return response()->json($payment);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar pagamento'], 500);
        }
    }

    /**
     * Remove um pagamento
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function destroy(Payment $payment)
    {
        try {
            $paymentId = $payment->id;
            $payment->delete();
            Log::info('Pagamento removido', ['payment_id' => $paymentId]);

            return response()->json(null, 204);
        } catch (Exception $e) {
            Log::error('Erro ao excluir pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao excluir pagamento'], 500);
        }
    }
}