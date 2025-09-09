<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmnesScreen - Accueil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .welcome-content {
            text-align: center;
            max-width: 800px;
        }
        
        .welcome-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 40px;
            transition: all 0.3s ease;
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(93, 2, 131, 0.2);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .welcome-card h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .welcome-card p {
            opacity: 0.8;
            margin-bottom: 30px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-content">
            <header class="header" style="margin-bottom: 0;">
                <h1>OmnesScreen</h1>
                <p>Plateforme d'affichage d'événements - Omnes Education</p>
            </header>
            
            <div class="welcome-grid">
                <div class="welcome-card">
                    <h3>Campus Citadelle</h3>
                    <p>Consultez les événements organisés par les associations du Campus Citadelle</p>
                    <a href="citadelle.php" class="btn btn-primary">Voir les événements</a>
                </div>
                
                <div class="welcome-card">
                    <h3>Campus Citroën</h3>
                    <p>Découvrez les événements du Campus Citroën organisés par nos associations</p>
                    <a href="citroen.php" class="btn btn-primary">Voir les événements</a>
                </div>
                
                <div class="welcome-card">
                    <h3>Espace Administration</h3>
                    <p>Accès réservé aux associations et administrateurs pour gérer les événements</p>
                    <a href="login.php" class="btn btn-primary">Se connecter</a>
                </div>
            </div>
            
            <div style="margin-top: 60px; opacity: 0.6; font-size: 0.9rem;">
                <p>&copy; 2025 Omnes Education - Tous droits réservés</p>
            </div>
        </div>
    </div>
</body>
</html>
