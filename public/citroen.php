<?php
require_once '../models/Event.php';

$event = new Event();
$events = $event->getApprovedByCampus('citroen');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Citroën - Événements</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta http-equiv="refresh" content="60">
</head>
<body>
    <div class="refresh-indicator">
        <span>Actualisation automatique</span>
    </div>
    
    <div class="container">
        <header class="header">
            <h1>Campus Citroën</h1>
            <p>Événements des associations - Omnes Education</p>
        </header>
        
        <main>
            <?php if (empty($events)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <h2 style="font-weight: 300; opacity: 0.7; font-size: 1.5rem;">
                        Aucun événement programmé pour le moment
                    </h2>
                    <p style="opacity: 0.5; margin-top: 10px;">
                        Les prochains événements apparaîtront ici automatiquement
                    </p>
                </div>
            <?php else: ?>
                <div class="events-grid">
                    <?php foreach ($events as $evt): ?>
                        <div class="event-card">
                            <h3 class="event-title"><?php echo htmlspecialchars($evt['title']); ?></h3>
                            
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><?php echo date('d/m/Y', strtotime($evt['event_date'])); ?></span>
                                </div>
                                
                                <div class="meta-item">
                                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><?php echo date('H:i', strtotime($evt['event_time'])); ?></span>
                                </div>
                                
                                <div class="meta-item">
                                    <svg class="meta-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><?php echo htmlspecialchars($evt['location']); ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($evt['description'])): ?>
                                <p class="event-description">
                                    <?php echo htmlspecialchars($evt['description']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="event-association">
                                <?php echo htmlspecialchars($evt['association_name']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Animation d'entrée pour les cartes d'événements
        const cards = document.querySelectorAll('.event-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Indicateur de dernière mise à jour
        const refreshIndicator = document.querySelector('.refresh-indicator');
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        refreshIndicator.innerHTML = `Dernière mise à jour: ${timeString}`;
    </script>
</body>
</html>
