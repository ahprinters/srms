<?php
/**
 * Fetch notifications from the database
 */

function getLatestNotifications($dbh, $limit = 5) {
    try {
        $sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT :limit";
        $query = $dbh->prepare($sql);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        return [];
    }
}

function displayNotifications($notifications) {
    $output = '';
    
    if (empty($notifications)) {
        return '<div class="text-center py-3">No notifications found</div>';
    }
    
    $output .= '<div class="list-group">';
    
    foreach ($notifications as $notification) {
        // Determine icon based on notification type
        $icon = 'fas fa-bell';
        if (isset($notification->type)) {
            switch ($notification->type) {
                case 'student':
                    $icon = 'fas fa-user-graduate';
                    break;
                case 'result':
                    $icon = 'fas fa-chart-bar';
                    break;
                case 'subject':
                    $icon = 'fas fa-book';
                    break;
                case 'event':
                    $icon = 'fas fa-calendar-alt';
                    break;
            }
        }
        
        // Calculate time ago
        $timeAgo = 'Just now';
        if (isset($notification->created_at)) {
            $notificationDate = new DateTime($notification->created_at);
            $now = new DateTime();
            $interval = $now->diff($notificationDate);
            
            if ($interval->days > 0) {
                $timeAgo = $interval->days . ' day' . ($interval->days > 1 ? 's' : '') . ' ago';
            } else if ($interval->h > 0) {
                $timeAgo = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } else if ($interval->i > 0) {
                $timeAgo = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            }
        }
        
        $output .= '<a href="#" class="list-group-item list-group-item-action">';
        $output .= '<div class="d-flex w-100 justify-content-between">';
        $output .= '<h6 class="mb-1"><i class="' . $icon . ' me-2"></i> ' . htmlentities($notification->title ?? 'Notification') . '</h6>';
        $output .= '<small>' . $timeAgo . '</small>';
        $output .= '</div>';
        $output .= '<p class="mb-1">' . htmlentities($notification->description ?? '') . '</p>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
?>