<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use illuminate\Database\Query\Builder;

class ScheduleSession extends Controller
{
    private const TABLE_NAME = 'session_schedule';
    private const ENTITY_ID = 'entity_id';
    private const USER_ID = 'user_id';
    private const PROFESSIONAL_ID = 'professional_id';
    private const SESSION_DATE = 'session_date';

    private Request $request;
    private Builder $table;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->table = Db::table(self::TABLE_NAME);
    }

    public function getSessionData(int $sessionId): string
    {
        $session = $this->table->select()->where(self::ENTITY_ID, $sessionId)->get();

        return json_encode($session->all());
    }

    public function getUserSessions(int $userId): string
    {
        $session = $this->table->select()->where(self::USER_ID, $userId)->get();

        return json_encode($session->all());
    }

    public function getProfessionalSessions(int $professionalId): string
    {
        $session = $this->table->select()->where(self::PROFESSIONAL_ID, $professionalId)->get();

        return json_encode($session->all());
    }

    public function createSession(): int
    {
        $userId = $this->request->input('userId');
        $professionalId = $this->request->input('professionalId');
        $sessionDate = $this->request->input('sessionDate');

        if (empty($userId) || empty($professionalId) || empty($sessionDate)) {
            return 0;
        }

        return $this->table->insertGetId([
            self::USER_ID => $userId, self::PROFESSIONAL_ID => $professionalId, self::SESSION_DATE => $sessionDate
        ]);
    }

    public function updateSessionDate(int $sessionId): bool
    {
        $sessionDate = $this->request->input('sessionDate');

        if (empty($sessionDate)) {
            return 0;
        }

        $affectedRows = $this->table->where(self::ENTITY_ID, '=', $sessionId)
            ->update([self::SESSION_DATE => $sessionDate]);

        return !empty($affectedRows);
    }

    public function deleteSession(int $sessionId): bool
    {
        $this->table->where(self::ENTITY_ID, '=', $sessionId)->delete();

        return !empty($affectedRows);
    }
}
