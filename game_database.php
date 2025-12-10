<?php
// Game Database Handler
session_start();

// Database configuration
$db_file = __DIR__ . '/game_scores.json';
$db_dir = __DIR__;

// Ensure directory is writable
if (!is_writable($db_dir)) {
    chmod($db_dir, 0755);
}

// Initialize database if it doesn't exist
if (!file_exists($db_file)) {
    $initial_data = [
        'users' => [],
        'scores' => []
    ];
    file_put_contents($db_file, json_encode($initial_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    chmod($db_file, 0666);
}

// Read database with error handling
function readDatabase() {
    global $db_file;
    try {
        if (!file_exists($db_file)) {
            return ['users' => [], 'scores' => []];
        }
        $data = file_get_contents($db_file);
        $decoded = json_decode($data, true);
        if ($decoded === null) {
            return ['users' => [], 'scores' => []];
        }
        return $decoded;
    } catch (Exception $e) {
        return ['users' => [], 'scores' => []];
    }
}

// Write database with error handling
function writeDatabase($data) {
    global $db_file;
    try {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new Exception('JSON encoding failed');
        }
        $result = file_put_contents($db_file, $json, LOCK_EX);
        if ($result === false) {
            throw new Exception('Failed to write to database');
        }
        @chmod($db_file, 0666);
        return true;
    } catch (Exception $e) {
        error_log('Database write error: ' . $e->getMessage());
        return false;
    }
}

// Register user
function registerUser($username, $password) {
    if (strlen($username) < 3) {
        return ['success' => false, 'message' => 'Username must be at least 3 characters'];
    }
    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters'];
    }
    
    $db = readDatabase();
    
    // Check if user exists
    foreach ($db['users'] as $user) {
        if ($user['username'] === $username) {
            return ['success' => false, 'message' => 'Username already exists'];
        }
    }
    
    // Add new user
    $newUser = [
        'id' => uniqid(),
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created' => date('Y-m-d H:i:s'),
        'total_score' => 0,
        'games_played' => 0
    ];
    
    $db['users'][] = $newUser;
    writeDatabase($db);
    
    return ['success' => true, 'message' => 'Registration successful', 'user_id' => $newUser['id']];
}

// Login user
function loginUser($username, $password) {
    $db = readDatabase();
    
    foreach ($db['users'] as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return ['success' => true, 'message' => 'Login successful', 'user_id' => $user['id']];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid username or password'];
}

// Get current user
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $db = readDatabase();
    foreach ($db['users'] as $user) {
        if ($user['id'] === $_SESSION['user_id']) {
            return $user;
        }
    }
    
    return null;
}

// Save score
function saveScore($score, $password_guessed, $attempts_used, $time_taken) {
    $user = getCurrentUser();
    if (!$user) {
        return ['success' => false, 'message' => 'Not logged in'];
    }
    
    $db = readDatabase();
    
    // Create score record
    $scoreRecord = [
        'id' => uniqid(),
        'user_id' => $user['id'],
        'username' => $user['username'],
        'score' => $score,
        'password' => $password_guessed,
        'attempts_used' => $attempts_used,
        'time_taken' => $time_taken,
        'date' => date('Y-m-d H:i:s')
    ];
    
    // Add score to database
    $db['scores'][] = $scoreRecord;
    
    // Update user stats
    foreach ($db['users'] as &$u) {
        if ($u['id'] === $user['id']) {
            $u['total_score'] += $score;
            $u['games_played']++;
            break;
        }
    }
    
    writeDatabase($db);
    
    return ['success' => true, 'message' => 'Score saved', 'score_id' => $scoreRecord['id']];
}

// Get user stats
function getUserStats($user_id) {
    $db = readDatabase();
    
    $stats = [
        'user' => null,
        'total_score' => 0,
        'games_played' => 0,
        'best_score' => 0,
        'average_score' => 0,
        'recent_games' => []
    ];
    
    // Get user
    foreach ($db['users'] as $user) {
        if ($user['id'] === $user_id) {
            $stats['user'] = $user;
            $stats['total_score'] = $user['total_score'];
            $stats['games_played'] = $user['games_played'];
            break;
        }
    }
    
    if (!$stats['user']) {
        return null;
    }
    
    // Get user scores
    $userScores = array_filter($db['scores'], function($score) use ($user_id) {
        return $score['user_id'] === $user_id;
    });
    
    // Get best score
    if (!empty($userScores)) {
        $stats['best_score'] = max(array_column($userScores, 'score'));
        $stats['average_score'] = round(array_sum(array_column($userScores, 'score')) / count($userScores), 2);
        
        // Get recent games (last 10)
        usort($userScores, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        $stats['recent_games'] = array_slice($userScores, 0, 10);
    }
    
    return $stats;
}

// Get leaderboard
function getLeaderboard($limit = 20, $time_period = 'all') {
    $db = readDatabase();
    
    // Filter by time period
    if ($time_period === 'today') {
        $today = date('Y-m-d');
        $db['scores'] = array_filter($db['scores'], function($score) use ($today) {
            return strpos($score['date'], $today) === 0;
        });
    } elseif ($time_period === 'week') {
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $db['scores'] = array_filter($db['scores'], function($score) use ($weekAgo) {
            return strtotime($score['date']) >= strtotime($weekAgo);
        });
    } elseif ($time_period === 'month') {
        $monthAgo = date('Y-m-d', strtotime('-30 days'));
        $db['scores'] = array_filter($db['scores'], function($score) use ($monthAgo) {
            return strtotime($score['date']) >= strtotime($monthAgo);
        });
    }
    
    // Calculate user stats for leaderboard
    $leaderboard = [];
    foreach ($db['users'] as $user) {
        $userScores = array_filter($db['scores'], function($score) use ($user) {
            return $score['user_id'] === $user['id'];
        });
        
        if (!empty($userScores)) {
            $leaderboard[] = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'total_score' => $user['total_score'],
                'games_played' => $user['games_played'],
                'best_score' => max(array_column($userScores, 'score')),
                'average_score' => round(array_sum(array_column($userScores, 'score')) / count($userScores), 2)
            ];
        }
    }
    
    // Sort by total score
    usort($leaderboard, function($a, $b) {
        return $b['total_score'] - $a['total_score'];
    });
    
    return array_slice($leaderboard, 0, $limit);
}

// Logout
function logout() {
    session_destroy();
    return ['success' => true, 'message' => 'Logged out successfully'];
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'register':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            echo json_encode(registerUser($username, $password));
            break;
            
        case 'login':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            echo json_encode(loginUser($username, $password));
            break;
            
        case 'current_user':
            $user = getCurrentUser();
            echo json_encode(['user' => $user]);
            break;
            
        case 'save_score':
            $score = intval($_POST['score'] ?? 0);
            $password = $_POST['password'] ?? '';
            $attempts = intval($_POST['attempts'] ?? 0);
            $time = intval($_POST['time'] ?? 0);
            echo json_encode(saveScore($score, $password, $attempts, $time));
            break;
            
        case 'get_user_stats':
            $user_id = $_POST['user_id'] ?? '';
            $stats = getUserStats($user_id);
            echo json_encode($stats);
            break;
            
        case 'get_leaderboard':
            $limit = intval($_POST['limit'] ?? 20);
            $period = $_POST['period'] ?? 'all';
            $leaderboard = getLeaderboard($limit, $period);
            echo json_encode(['leaderboard' => $leaderboard]);
            break;
            
        case 'logout':
            echo json_encode(logout());
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}
?>
