<?php
/**
 * @author Ygor Barcelos
 */
declare(strict_types=1);

require_once PROJECT_ROOT_PATH . "/Model/Collection/Database.php";

class SessionSchedule extends Database
{
    /**
     * Get sessions by user_id
     *
     * @param int $userId
     *
     * @return array
     * @throws Exception
     */
    public function getUserSessions(int $userId): array
    {
        return $this->select("SELECT * FROM session_schedule WHERE user_id = ? ASC", ["i", $userId]);
    }

    /**
     * Create a new session
     *
     * @param int $userId
     * @param int $professionalId
     * @param string $sessionDate
     *
     * @return bool
     * @throws Exception
     */
    public function createSession(int $userId, int $professionalId, string $sessionDate): bool
    {
        return $this->select("INSERT INTO session_schedule (user_id, professional_id, session_date) VALUES (?, ?, ?);", ["i", $userId]);
    }
}