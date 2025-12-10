<?php
// Game Database Handler
session_start();

// Use simple JSON file in project directory
$db_file = __DIR__ . '/game_scores.json';

// Initialize database
function initDatabase() {
    global $db_file;
    if (!file_exists($db_file)) {
        $data = ['users' => [], 'scores' => []];
        file_put_contents($db_file, json_encode($data, JSON_PRETTY_PRINT));
    }
}

initDatabase();

// Read database
function getDatabase() {
    global $db_file;
    if (!file_exists($db_file)) {
        return ['users' => [], 'scores' => []];
    }
    $content = @file_get_contents($db_file);
    if ($content === false) return ['users' => [], 'scores' => []];
    $data = @json_decode($content, true);
    return ($data === null) ? ['users' => [], 'scores' => []] : $data;
}

// Save database
function saveDatabase($data) {
    global $db_file;
    @file_put_contents($db_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Register
if ($_POST['action'] === 'register') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    
    if (strlen($user) < 3 || strlen($pass) < 6) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    $db = getDatabase();
    foreach ($db['users'] as $u) {
        if ($u['username'] === $user) {
            echo json_encode(['success' => false, 'message' => 'User exists']);
            exit;
        }
    }
    
    $db['users'][] = [
        'id' => uniqid('user_'),
        'username' => $user,
        'password' => password_hash($pass, PASSWORD_DEFAULT),
        'created' => date('Y-m-d H:i:s'),
        'total_score' => 0,
        'games_played' => 0
    ];
    
    saveDatabase($db);
    echo json_encode(['success' => true, 'message' => 'Registered']);
    exit;
}

// Login
if ($_POST['action'] === 'login') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    
    $db = getDatabase();
    foreach ($db['users'] as $u) {
        if ($u['username'] === $user && password_verify($pass, $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['username'];
            echo json_encode(['success' => true, 'message' => 'Logged in']);
            exit;
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Wrong credentials']);
    exit;
}

// Current user
if ($_POST['action'] === 'current_user') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['user' => null]);
        exit;
    }
    
    $db = getDatabase();
    foreach ($db['users'] as $u) {
        if ($u['id'] === $_SESSION['user_id']) {
            echo json_encode(['user' => $u]);
            exit;
        }
    }
    
    echo json_encode(['user' => null]);
    exit;
}

// Save score
if ($_POST['action'] === 'save_score') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false]);
        exit;
    }
    
    $db = getDatabase();
    $score = (int)$_POST['score'];
    $password = $_POST['password'] ?? '';
    $attempts = (int)$_POST['attempts'];
    $time = (int)$_POST['time'];
    
    $db['scores'][] = [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'score' => $score,
        'password' => $password,
        'attempts_used' => $attempts,
        'time_taken' => $time,
        'date' => date('Y-m-d H:i:s')
    ];
    
    foreach ($db['users'] as &$u) {
        if ($u['id'] === $_SESSION['user_id']) {
            $u['total_score'] += $score;
            $u['games_played']++;
            break;
        }
    }
    
    saveDatabase($db);
    echo json_encode(['success' => true]);
    exit;
}

// Get user stats
if ($_POST['action'] === 'get_user_stats') {
    $user_id = $_POST['user_id'] ?? '';
    $db = getDatabase();
    
    $user = null;
    foreach ($db['users'] as $u) {
        if ($u['id'] === $user_id) {
            $user = $u;
            break;
        }
    }
    
    if (!$user) {
        echo json_encode(null);
        exit;
    }
    
    $user_scores = array_filter($db['scores'], fn($s) => $s['user_id'] === $user_id);
    
    $stats = [
        'user' => $user,
        'total_score' => $user['total_score'],
        'games_played' => $user['games_played'],
        'best_score' => 0,
        'average_score' => 0,
        'recent_games' => []
    ];
    
    if (!empty($user_scores)) {
        $scores_arr = array_values($user_scores);
        $stats['best_score'] = max(array_column($scores_arr, 'score'));
        $stats['average_score'] = round(array_sum(array_column($scores_arr, 'score')) / count($scores_arr), 2);
        usort($scores_arr, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        $stats['recent_games'] = array_slice($scores_arr, 0, 10);
    }
    
    echo json_encode($stats);
    exit;
}

// Get leaderboard
if ($_POST['action'] === 'get_leaderboard') {
    $period = $_POST['period'] ?? 'all';
    $limit = (int)($_POST['limit'] ?? 20);
    
    $db = getDatabase();
    
    $leaderboard = [];
    foreach ($db['users'] as $user) {
        $user_scores = array_filter($db['scores'], fn($s) => $s['user_id'] === $user['id']);
        
        if (!empty($user_scores)) {
            $scores_arr = array_values($user_scores);
            $best = max(array_column($scores_arr, 'score'));
            $avg = round(array_sum(array_column($scores_arr, 'score')) / count($scores_arr), 2);
            
            $leaderboard[] = [
                'username' => $user['username'],
                'total_score' => $user['total_score'],
                'games_played' => $user['games_played'],
                'best_score' => $best,
                'average_score' => $avg
            ];
        }
    }
    
    usort($leaderboard, fn($a, $b) => $b['total_score'] - $a['total_score']);
    echo json_encode(['leaderboard' => array_slice($leaderboard, 0, $limit)]);
    exit;
}

// Logout
if ($_POST['action'] === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>

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
