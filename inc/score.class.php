<?php

require_once 'db.class.php';

class Score {
    private $gameId;
    private $userId;
    private $db;

    public function __construct($gameId, $userId = null) {
        $this->gameId = $gameId;
        $this->userId = $userId;
        $this->db = new DB();  // assuming the DB class is in the same directory
    }

    public function getTopScores() {
        $query = "
            SELECT hs.user_id, u.username, hs.score 
            FROM highscores hs 
            JOIN users u ON hs.user_id = u.id 
            WHERE hs.game_id = ? 
            ORDER BY hs.score DESC 
            LIMIT 10
        ";
        $result = $this->db->query($query, $this->gameId)->fetchAll();
        return $result;
    }

    public function getPlayerRank() {
        if ($this->userId === null) {
            return null;
        }

        // Get the rank of the player if they are not in the top 10
        $query = "
            SELECT COUNT(*) + 1 AS rank FROM highscores 
            WHERE game_id = ? 
            AND score > (SELECT score FROM highscores WHERE game_id = ? AND user_id = ?)
        ";
        $result = $this->db->query($query, $this->gameId, $this->gameId, $this->userId)->fetchArray();

        // Get the player's score and username
        $playerScoreQuery = "
            SELECT hs.score, u.username 
            FROM highscores hs 
            JOIN users u ON hs.user_id = u.id 
            WHERE hs.game_id = ? 
            AND hs.user_id = ?
        ";
        $playerScoreResult = $this->db->query($playerScoreQuery, $this->gameId, $this->userId)->fetchArray();

        return array_merge($result, $playerScoreResult);
    }

    public function getTopScoresWithPlayerRank() {
        $topScores = $this->getTopScores();
        $playerInTop = false;

        if ($this->userId !== null) {
            // Check if the player is in the top 10
            foreach ($topScores as $score) {
                if ($score['user_id'] == $this->userId) {
                    $playerInTop = true;
                    break;
                }
            }

            // If player is not in top 10, get their rank
            if (!$playerInTop) {
                $playerRank = $this->getPlayerRank();
                return ['topScores' => $topScores, 'playerRank' => $playerRank];
            }
        }

        return ['topScores' => $topScores];
    }

    public function displayScoresTable($title) {
        $result = $this->getTopScoresWithPlayerRank();
        $topScores = $result['topScores'];
        $playerRank = $result['playerRank'] ?? null;

        // Display title
    echo "<div class='score-table'>";
    echo "<h2>{$title}</h2>";

    // Display table
    echo "<table border='1'>";
    echo "<tr><th>Rank</th><th>Username</th><th>Score</th></tr>";
    $rank = 1;
    foreach ($topScores as $score) {
        echo "<tr><td>{$rank}</td><td>{$score['username']}</td><td>{$score['score']}</td></tr>";
        $rank++;
    }

    if ($playerRank) {
        echo "<tr><td>{$playerRank['rank']}</td><td>{$playerRank['username']}</td><td>{$playerRank['score']}</td></tr>";
    }

    echo "</table>";
    echo "</div>";
    }

    public function getPlayerScore() 
    {
        
        $result = $this->db->query("SELECT * FROM highscores WHERE user_id = ? AND game_id = ?", $this->userId, $this->gameId);

        if ($result->numRows() > 0) 
        {
            // SKORE EXISTUJE -> RETURNE SE
            return $result->fetchArray()['score'];
        } else 
        {
            // SKORE NEEXISTUJE -> VYTVORI SE S HODNOTOU NULA A NULA SE I VRATI
            $this->db->query("INSERT INTO highscores (user_id, game_id, score) VALUES (?, ?, 0)", $this->userId, $this->gameId);
            return 0;
        }
    }

    public function savePlayerScore($score)
    {
        $result = $this->db->query("UPDATE highscores SET score = ? WHERE user_id = ? AND game_id = ?", $score, $this->userId, $this->gameId);
    }
}