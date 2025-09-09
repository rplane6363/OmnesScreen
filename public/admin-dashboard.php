<?php
require_once '../includes/auth.php';
require_once '../models/Event.php';

$auth = new Auth();
$auth->requireAdmin();

$event = new Event();
$message = '';
$messageType = '';

// Traitement des actions administrateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'approve':
                if ($event->updateStatus($_POST['event_id'], 'approved')) {
                    $message = 'Événement approuvé avec succès.';
                    $messageType = 'success';
                } else {
                    $message = 'Erreur lors de l\'approbation.';
                    $messageType = 'error';
                }
                break;
                
            case 'reject':
                if ($event->updateStatus($_POST['event_id'], 'rejected')) {
                    $message = 'Événement refusé.';
                    $messageType = 'success';
                } else {
                    $message = 'Erreur lors du refus.';
                    $messageType = 'error';
                }
                break;
                
            case 'delete':
                if ($event->delete($_POST['event_id'])) {
                    $message = 'Événement supprimé.';
                    $messageType = 'success';
                } else {
                    $message = 'Erreur lors de la suppression.';
                    $messageType = 'error';
                }
                break;
        }
    }
}

// Récupération des données
$pendingEvents = $event->getAllPending();
$allEvents = $event->getAll();
$approvedCitadelle = $event->getApprovedByCampus('citadelle');
$approvedCitroen = $event->getApprovedByCampus('citroen');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - OmnesScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Administration OmnesScreen</h1>
            <p>Gestion des événements des associations d'Omnes Education</p>
            <div style="margin-top: 20px;">
                <a href="logout.php" class="btn btn-warning">Déconnexion</a>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="#pending" class="nav-button active">
                En attente (<?php echo count($pendingEvents); ?>)
            </a>
            <a href="#all-events" class="nav-button">
                Tous les événements (<?php echo count($allEvents); ?>)
            </a>
            <a href="#statistics" class="nav-button">Statistiques</a>
            <a href="citadelle.php" class="nav-button" target="_blank">Voir Campus Citadelle</a>
            <a href="citroen.php" class="nav-button" target="_blank">Voir Campus Citroën</a>
        </nav>
        
        <?php if ($message): ?>
            <div class="message message-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Événements en attente de validation -->
        <section id="pending">
            <div class="table-container">
                <h2 style="padding: 20px; margin: 0; background: rgba(93, 2, 131, 0.2);">
                    Événements en attente de validation
                </h2>
                
                <?php if (empty($pendingEvents)): ?>
                    <div style="padding: 40px; text-align: center; opacity: 0.7;">
                        <p>Aucun événement en attente de validation.</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Événement</th>
                                <th>Association</th>
                                <th>Date & Heure</th>
                                <th>Lieu</th>
                                <th>Campus</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingEvents as $evt): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($evt['title']); ?></strong>
                                        <?php if (!empty($evt['description'])): ?>
                                            <br><small style="opacity: 0.7;">
                                                <?php echo htmlspecialchars($evt['description']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($evt['association_name']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($evt['event_date'])); ?><br>
                                        <small><?php echo date('H:i', strtotime($evt['event_time'])); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($evt['location']); ?></td>
                                    <td><?php echo ucfirst($evt['campus']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($evt['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline-block;">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Approuver cet événement ?')">
                                                Approuver
                                            </button>
                                        </form>
                                        
                                        <form method="POST" style="display: inline-block;">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Refuser cet événement ?')">
                                                Refuser
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Tous les événements -->
        <section id="all-events" style="display: none;">
            <div class="table-container">
                <h2 style="padding: 20px; margin: 0; background: rgba(93, 2, 131, 0.2);">
                    Tous les événements
                </h2>
                
                <?php if (empty($allEvents)): ?>
                    <div style="padding: 40px; text-align: center; opacity: 0.7;">
                        <p>Aucun événement dans la base de données.</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Événement</th>
                                <th>Association</th>
                                <th>Date & Heure</th>
                                <th>Campus</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allEvents as $evt): ?>
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
                                    <td><?php echo htmlspecialchars($evt['association_name']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y H:i', strtotime($evt['event_date'] . ' ' . $evt['event_time'])); ?>
                                    </td>
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
                                    <td>
                                        <?php if ($evt['status'] === 'pending'): ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                                            </form>
                                            
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                            </form>
                                        <?php elseif ($evt['status'] === 'approved'): ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                                <button type="submit" class="btn btn-warning btn-sm">Masquer</button>
                                            </form>
                                        <?php elseif ($evt['status'] === 'rejected'): ?>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Réactiver</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" style="display: inline-block;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Supprimer définitivement cet événement ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Statistiques -->
        <section id="statistics" style="display: none;">
            <div class="form-container">
                <h2>Statistiques des événements</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                    <div style="background: rgba(40, 167, 69, 0.1); padding: 30px; border-radius: 12px; text-align: center; border: 1px solid rgba(40, 167, 69, 0.3);">
                        <h3 style="color: #28a745; margin-bottom: 10px;">Campus Citadelle</h3>
                        <div style="font-size: 2rem; font-weight: 600;"><?php echo count($approvedCitadelle); ?></div>
                        <p style="opacity: 0.7;">événements approuvés</p>
                    </div>
                    
                    <div style="background: rgba(40, 167, 69, 0.1); padding: 30px; border-radius: 12px; text-align: center; border: 1px solid rgba(40, 167, 69, 0.3);">
                        <h3 style="color: #28a745; margin-bottom: 10px;">Campus Citroën</h3>
                        <div style="font-size: 2rem; font-weight: 600;"><?php echo count($approvedCitroen); ?></div>
                        <p style="opacity: 0.7;">événements approuvés</p>
                    </div>
                    
                    <div style="background: rgba(255, 193, 7, 0.1); padding: 30px; border-radius: 12px; text-align: center; border: 1px solid rgba(255, 193, 7, 0.3);">
                        <h3 style="color: #ffc107; margin-bottom: 10px;">En attente</h3>
                        <div style="font-size: 2rem; font-weight: 600;"><?php echo count($pendingEvents); ?></div>
                        <p style="opacity: 0.7;">événements à valider</p>
                    </div>
                    
                    <div style="background: rgba(93, 2, 131, 0.1); padding: 30px; border-radius: 12px; text-align: center; border: 1px solid rgba(93, 2, 131, 0.3);">
                        <h3 style="color: #5d0283; margin-bottom: 10px;">Total</h3>
                        <div style="font-size: 2rem; font-weight: 600;"><?php echo count($allEvents); ?></div>
                        <p style="opacity: 0.7;">événements créés</p>
                    </div>
                </div>
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
    </script>
</body>
</html>
