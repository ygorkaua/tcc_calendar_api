<?php

namespace App\Models;

use DateTime;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SessionsToNotice
{
    private const TABLE_NAME = 'session_schedule';
    private const ENTITY_ID = 'entity_id';
    private const SESSION_DATE = 'session_date';
    private const SESSION_NOTICED = 'noticed';

    private Builder $table;

    public function __construct()
    {
        $this->table = Db::table(self::TABLE_NAME);
    }

    /**
     * @throws Exception
     */
    public function getSessionsToNotice(): array
    {
        $sessions = $this->table->select()->get()->all();

        $sessionsToNotice = [];
        foreach ($sessions as $session) {
            $now = new DateTime('-3 hour');
            $sessionDate = DateTime::createFromFormat('d/m/Y H:i', $session->session_date);
            $diff = $sessionDate->diff($now);

            if ($diff->y === 0 && $diff->m === 0 && $diff->d === 0 &&$diff->h === 0 && $diff->i <= 59) {
                if ($session->noticed == 0) {
                    $this->table->where(self::ENTITY_ID, '=', $session->entity_id)
                        ->update([self::SESSION_NOTICED => 1]);

                    $sessionsToNotice[] = $session;
                }
            }
        }

        return $sessionsToNotice;
    }
}
