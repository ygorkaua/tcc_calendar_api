<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Session Schedule API", version="0.1")
 */
class ScheduleSession extends Controller
{
    private const TABLE_NAME = 'session_schedule';
    private const ENTITY_ID = 'entity_id';
    private const USER_ID = 'user_id';
    private const PROFESSIONAL_ID = 'professional_id';
    private const SESSION_DATE = 'session_date';
    private const MEET_ID = 'meet_id';

    private Builder $table;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(
        public Request $request,
        public Response $response
    )
    {
        $this->table = Db::table(self::TABLE_NAME);
    }

    /**
     * @OA\Get(
     *     path="/v1/session/{sessionId}",
     *     description="Return session with provided ID information",
     *     @OA\Parameter(
     *         name="sessionId",
     *         in="query",
     *         description="Session ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return session information"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if provided session ID was not found"
     *     ),
     * )
     */
    public function getSessionData(int $sessionId): JsonResponse
    {
        $session = $this->table->select()->where(self::ENTITY_ID, $sessionId)->get();

        return empty($session->all())
            ? response()->json(['error' => 'Session with provided ID not found'], 400)
            : response()->json($session->all());
    }

    /**
     * @OA\Get(
     *     path="/v1/session/user/{userId}",
     *     description="Return sessions of user with provided ID",
     *     @OA\Parameter(
     *         name="userId",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return user sessions"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if sessions with provided user ID was not found"
     *     ),
     * )
     */
    public function getUserSessions(string $userId): JsonResponse
    {
        $session = $this->table->select()->where(self::USER_ID, $userId)->get();

        return empty($session->all())
            ? response()->json(['error' => 'Sessions with provided user ID not found'], 400)
            : response()->json($session->all());
    }

    /**
     * @OA\Get(
     *     path="/v1/session/professional/{professionalId}",
     *     description="Return sessions of professional with provided ID",
     *     @OA\Parameter(
     *         name="professionalId",
     *         in="query",
     *         description="Professional ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return professional sessions"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if sessions with provided professional ID was not found"
     *     ),
     * )
     */
    public function getProfessionalSessions(string $professionalId): JsonResponse
    {
        $session = $this->table->select()->where(self::PROFESSIONAL_ID, $professionalId)->get();

        return empty($session->all())
                ? response()->json(['error' => 'Sessions with provided professional ID not found'], 400)
            : response()->json($session->all());
    }

    /**
     * @OA\Post(
     *     path="/v1/create/user/{userId}/professional/{professionalId}/sessionDate/{sessionDate}",
     *     description="Create a new session with provided parameters",
     *     @OA\Parameter(
     *         name="userId",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="professionalId",
     *         in="query",
     *         description="Professional ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sessionDate",
     *         in="query",
     *         description="Session date",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return session and meet ID"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if can not create session"
     *     ),
     * )
     * @throws Exception
     */
    public function createSession(string $userId, string $professionalId, string $sessionDate): JsonResponse
    {
        $sessionDate = new \DateTime($sessionDate);
        $meetId = rand();

        $sessionId = $this->table->insertGetId([
            self::USER_ID => $userId,
            self::PROFESSIONAL_ID => $professionalId,
            self::SESSION_DATE => $sessionDate->format('d/m/Y h:s'),
            self::MEET_ID => $meetId
        ]);

        return empty($sessionId) ? response()->json(['error' => 'Was not possible to create a new session'], 400)
            : response()->json(['success' => "Session with id $sessionId created successfully", 'id' => $sessionId, 'meetId' => $meetId]);
    }

    /**
     * @OA\Post(
     *     path="/v1/session/{sessionId}/sessionDate/{sessionDate}",
     *     description="Update session date",
     *     @OA\Parameter(
     *         name="sessionId",
     *         in="query",
     *         description="Session ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sessionDate",
     *         in="query",
     *         description="New session date",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return a success message"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if can not update session date"
     *     ),
     * )
     */
    public function updateSessionDate(int $sessionId, string $sessionDate): JsonResponse
    {
        $affectedRows = $this->table->where(self::ENTITY_ID, '=', $sessionId)
            ->update([self::SESSION_DATE => $sessionDate]);

        return empty($affectedRows) ? response()->json(['error' => "Session with ID $sessionId not found"], 400)
            : response()->json(['success' => "Session with id $sessionId date successfully updated to $sessionDate"]);
    }

    /**
     * @OA\Delete(
     *     path="/v1/session/{sessionId}",
     *     description="Delete session",
     *     @OA\Parameter(
     *         name="sessionId",
     *         in="query",
     *         description="Session ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return a success message"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Return a error if can not delete session"
     *     ),
     * )
     */
    public function deleteSession(int $sessionId): JsonResponse
    {
        $affectedRows = $this->table->where(self::ENTITY_ID, '=', $sessionId)->delete();

        return empty($affectedRows) ? response()->json(['error' => "Session with ID $sessionId not found"], 400)
            : response()->json(['success' => "Session with id $sessionId successfully deleted"]);
    }
}
