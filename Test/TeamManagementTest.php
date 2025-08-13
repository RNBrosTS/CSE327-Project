<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TeamManagementTest extends TestCase
{
    private mysqli $db;
    private int $coachId;

    public function testCreateTeamInsertsRow(): void
    {
        $teamName = 'Football A';

        $stmt = $this->db->prepare("INSERT INTO teams (name, coach_id) VALUES (?, ?)");
        $stmt->bind_param('si', $teamName, $this->coachId);
        $stmt->execute();
        $stmt->close();

        $res = $this->db->query("SELECT name, coach_id FROM teams WHERE name='Football A'");
        $row = $res->fetch_assoc();

        $this->assertSame('Football A', $row['name']);
        $this->assertSame($this->coachId, (int) $row['coach_id']);
    }
}
