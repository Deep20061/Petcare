// Sistema de Notificações JavaScript
class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Criar container se não existir
        this.container = document.querySelector('.notification-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'notification-container';
            document.body.appendChild(this.container);
        }

        // Auto-fechar notificações existentes
        this.autoCloseExistingAlerts();
    }

    show(type, title, message, duration = 5000) {
        const alert = this.createAlert(type, title, message);
        this.container.appendChild(alert);

        // Auto-fechar após o tempo especificado
        if (duration > 0) {
            setTimeout(() => {
                this.closeAlert(alert);
            }, duration);
        }

        return alert;
    }

    createAlert(type, title, message) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}-custom alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');

        const iconMap = {
            'success': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-triangle',
            'warning': 'fas fa-exclamation-circle',
            'info': 'fas fa-info-circle'
        };

        alert.innerHTML = `
            <div class="alert-content">
                <i class="${iconMap[type]} alert-icon"></i>
                <div class="alert-text">
                    <div class="alert-title">${title}</div>
                    <div class="alert-message">${message}</div>
                </div>
                <button type="button" class="btn-close-custom" onclick="notificationSystem.closeAlert(this.closest('.alert'))">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        return alert;
    }

    closeAlert(alert) {
        if (alert && alert.parentNode) {
            alert.classList.add('alert-fade-out');
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 500);
        }
    }

    autoCloseExistingAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Adicionar botão de fechar se não existir
            if (!alert.querySelector('.btn-close-custom')) {
                const closeBtn = document.createElement('button');
                closeBtn.className = 'btn-close-custom';
                closeBtn.innerHTML = '<i class="fas fa-times"></i>';
                closeBtn.onclick = () => this.closeAlert(alert);
                
                const content = alert.querySelector('.alert-content');
                if (content) {
                    content.appendChild(closeBtn);
                }
            }

            // Auto-fechar após 5 segundos
            setTimeout(() => {
                this.closeAlert(alert);
            }, 5000);
        });
    }

    success(title, message, duration = 5000) {
        return this.show('success', title, message, duration);
    }

    error(title, message, duration = 7000) {
        return this.show('error', title, message, duration);
    }

    warning(title, message, duration = 6000) {
        return this.show('warning', title, message, duration);
    }

    info(title, message, duration = 5000) {
        return this.show('info', title, message, duration);
    }
}

// Inicializar sistema de notificações
let notificationSystem;
document.addEventListener('DOMContentLoaded', function() {
    notificationSystem = new NotificationSystem();
});

// Função global para fechar alertas (compatibilidade)
function closeAlert(button) {
    const alert = button.closest('.alert');
    if (notificationSystem) {
        notificationSystem.closeAlert(alert);
    }
}
