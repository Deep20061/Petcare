<?php
// Sistema de Notificações PHP

class NotificationManager {
    private static $notifications = [];
    
    public static function addSuccess($title, $message) {
        self::$notifications[] = [
            'type' => 'success',
            'title' => $title,
            'message' => $message
        ];
    }
    
    public static function addError($title, $message) {
        self::$notifications[] = [
            'type' => 'error',
            'title' => $title,
            'message' => $message
        ];
    }
    
    public static function addWarning($title, $message) {
        self::$notifications[] = [
            'type' => 'warning',
            'title' => $title,
            'message' => $message
        ];
    }
    
    public static function addInfo($title, $message) {
        self::$notifications[] = [
            'type' => 'info',
            'title' => $title,
            'message' => $message
        ];
    }
    
    public static function render() {
        if (empty(self::$notifications)) {
            return '';
        }
        
        $html = '<div class="notification-container">';
        
        foreach (self::$notifications as $notification) {
            $iconMap = [
                'success' => 'fas fa-check-circle',
                'error' => 'fas fa-exclamation-triangle',
                'warning' => 'fas fa-exclamation-circle',
                'info' => 'fas fa-info-circle'
            ];
            
            $html .= '<div class="alert alert-' . $notification['type'] . '-custom alert-dismissible fade show" role="alert">';
            $html .= '<div class="alert-content">';
            $html .= '<i class="' . $iconMap[$notification['type']] . ' alert-icon"></i>';
            $html .= '<div class="alert-text">';
            $html .= '<div class="alert-title">' . htmlspecialchars($notification['title']) . '</div>';
            $html .= '<div class="alert-message">' . htmlspecialchars($notification['message']) . '</div>';
            $html .= '</div>';
            $html .= '<button type="button" class="btn-close-custom" onclick="closeAlert(this)">';
            $html .= '<i class="fas fa-times"></i>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        // Limpar notificações após renderizar
        self::$notifications = [];
        
        return $html;
    }
    
    public static function hasNotifications() {
        return !empty(self::$notifications);
    }
    
    // Métodos para trabalhar com sessão
    public static function addToSession($type, $title, $message) {
        if (!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = [];
        }
        
        $_SESSION['notifications'][] = [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
    }
    
    public static function getFromSession() {
        if (isset($_SESSION['notifications'])) {
            self::$notifications = $_SESSION['notifications'];
            unset($_SESSION['notifications']);
        }
    }
    
    public static function successSession($title, $message) {
        self::addToSession('success', $title, $message);
    }
    
    public static function errorSession($title, $message) {
        self::addToSession('error', $title, $message);
    }
    
    public static function warningSession($title, $message) {
        self::addToSession('warning', $title, $message);
    }
    
    public static function infoSession($title, $message) {
        self::addToSession('info', $title, $message);
    }
}

// Função helper para incluir os arquivos necessários
function includeNotificationAssets() {
    echo '<link href="css/notifications.css" rel="stylesheet">';
    echo '<script src="js/notifications.js"></script>';
}

// Função helper para renderizar notificações
function renderNotifications() {
    NotificationManager::getFromSession();
    echo NotificationManager::render();
}
?>
