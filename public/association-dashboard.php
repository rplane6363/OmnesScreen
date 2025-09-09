<?php
require_once '../includes/auth.php';
require_once '../models/Event.php';

$auth = new Auth();
$auth->requireLogin();

$event = new Event();
$message = '';
$messageType = '';

// Traitement du formulaire d'ajout d'événement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_event') {
    $event->title = trim($_POST['title']);
    $event->description = trim($_POST['description']);
    $event->event_date = $_POST['event_date'];
    $event->event_time = $_POST['event_time'];
    $event->location = trim($_POST['location']);
    $event->campus = $_POST['campus'];
    $event->association_name = $_SESSION['association_name'];
    $event->created_by = $_SESSION['user_id'];
    
    if (empty($event->title) || empty($event->event_date) || empty($event->event_time) || empty($event->location)) {
        $message = 'Veuillez remplir tous les champs obligatoires.';
        $messageType = 'error';
    } else {
        if ($event->create()) {
            $message = 'Événement ajouté avec succès ! Il sera visible après validation par un administrateur.';
            $messageType = 'success';
        } else {
            $message = 'Erreur lors de l\'ajout de l\'événement.';
            $messageType = 'error';
        }
    }
}

// Récupération des événements de l'association
$associationEvents = $event->getByAssociation($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Association - OmnesScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Espace Association</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['association_name']); ?></p>
            <div style="margin-top: 20px;">
                <a href="logout.php" class="btn btn-warning">Déconnexion</a>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="#add-event" class="nav-button active">Ajouter un événement</a>
            <a href="#my-events" class="nav-button">Mes événements</a>
            <a href="citadelle.php" class="nav-button" target="_blank">Voir Campus Citadelle</a>
            <a href="citroen.php" class="nav-button" target="_blank">Voir Campus Citroën</a>
        </nav>
        
        <?php if ($message): ?>
            <div class="message message-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire d'ajout d'événement -->
        <section id="add-event">
            <div class="form-container">
                <h2>Ajouter un nouvel événement</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add_event">
                    
                    <div class="form-group">
                        <label for="title">Titre de l'événement *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               placeholder="Titre de votre événement" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Description détaillée de l'événement"></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="event_date">Date *</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" 
                                   min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time">Heure *</label>
                            <input type="time" id="event_time" name="event_time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="location">Lieu *</label>
                            <input type="text" id="location" name="location" class="form-control" 
                                   placeholder="Salle, amphithéâtre..." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="campus">Campus *</label>
                            <select id="campus" name="campus" class="form-control" required>
                                <option value="">Sélectionner un campus</option>
                                <option value="citadelle">Campus Citadelle</option>
                                <option value="citroen">Campus Citroën</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Ajouter l'événement</button>
                </form>
            </div>
        </section>
        
        <!-- Liste des événements de l'association -->
        <section id="my-events">
            <div class="table-container">
                <h2 style="padding: 20px; margin: 0; background: rgba(93, 2, 131, 0.2);">
                    Mes événements (<?php echo count($associationEvents); ?>)
                </h2>
                
                <?php if (empty($associationEvents)): ?>
                    <div style="padding: 40px; text-align: center; opacity: 0.7;">
                        <p>Aucun événement créé pour le moment.</p>
                        <p>Utilisez le formulaire ci-dessus pour ajouter votre premier événement.</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Lieu</th>
                                <th>Campus</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($associationEvents as $evt): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($evt['title']); ?></strong>
                                        <?php if (!empty($evt['description'])): ?>
                                            <br><small style="opacity: 0.7;">
                                                <?php echo htmlspecialchars(substr($evt['description'], 0, 100)); ?>
                                                <?php if (strlen($evt['description']) > 100) echo '...'; ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($evt['event_date'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($evt['event_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($evt['location']); ?></td>
                                    <td><?php echo ucfirst($evt['campus']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $evt['status']; ?>">
                                            <?php
                                            switch($evt['status']) {
                                                case 'pending': echo 'En attente'; break;
                                                case 'approved': echo 'Approuvé'; break;
                                                case 'rejected': echo 'Refusé'; break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($evt['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </div>
    
    <script>
        // Navigation entre les sections
        const navButtons = document.querySelectorAll('.nav-button');
        const sections = document.querySelectorAll('section');
        
        navButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (button.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = button.getAttribute('href').substring(1);
                    
                    // Gestion de l'affichage des sections
                    sections.forEach(section => {
                        section.style.display = section.id === targetId ? 'block' : 'none';
                    });
                    
                    // Gestion des boutons actifs
                    navButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                }
            });
        });
        
        // Afficher par défaut la première section
        sections.forEach(section => {
            section.style.display = section.id === 'add-event' ? 'block' : 'none';
        });
    </script>
</body>
</html>
