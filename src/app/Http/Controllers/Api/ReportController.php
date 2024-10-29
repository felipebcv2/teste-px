<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateTaskReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Solicita um relatório de tarefas.
     *
     * @OA\Post(
     *     path="/api/v1/reports/tasks",
     *     summary="Solicitar relatório de tarefas",
     *     description="Gera um relatório das tarefas do usuário autenticado e envia por e-mail.",
     *     tags={"Relatórios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=202,
     *         description="Solicitação de relatório aceita."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar solicitação de relatório."
     *     )
     * )
     */
    public function requestReport(): JsonResponse
    {
         $user = Auth::user();

        GenerateTaskReport::dispatch($user);

        return response()->json(['message' => 'Relatório solicitado com sucesso.'], 202);
    }
}
