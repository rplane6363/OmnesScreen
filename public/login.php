<?php
require_once '../includes/auth.php';

$auth = new Auth();
$error = '';
$success = '';

// Redirection si déjà connecté
if ($auth->isLoggedIn()) {
    if ($auth->isAdmin()) {
        header('Location: admin-dashboard.php');
    } else {
        header('Location: association-dashboard.php');
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        if ($auth->login($username, $password)) {
            if ($auth->isAdmin()) {
                header('Location: admin-dashboard.php');
            } else {
                header('Location: association-dashboard.php');
            }
            exit();
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - OmnesScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>Connexion</h2>
            <p style="text-align: center; opacity: 0.8; margin-bottom: 30px;">
                Omnes Education - Gestion d'événements
            </p>
            
            <?php if ($error): ?>
                <div class="message message-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message message-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                       placeholder="Votre nom d'utilisateur" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Votre mot de passe" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                Se connecter
            </button>
            
            <div style="margin-top: 30px; text-align: center; font-size: 0.9rem; opacity: 0.7;">
                <p>Comptes de démonstration :</p>
                <p><strong>Admin :</strong> admin / password</p>
                <p><strong>Association :</strong> bde_citadelle / password</p>
            </div>
        </form>
    </div>
</body>
</html>
