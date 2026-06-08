(function () {
    const PH_TIMEZONE = 'Asia/Manila';
    const DEFAULT_REFRESH_SECONDS = 30;

    function formatPhilippineDateTime(dateInput, withSeconds = false) {
        if (!dateInput) return '';

        const date = new Date(dateInput);
        if (Number.isNaN(date.getTime())) return '';

        return new Intl.DateTimeFormat('en-PH', {
            timeZone: PH_TIMEZONE,
            month: 'short',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: withSeconds ? '2-digit' : undefined,
            hour12: true,
        }).format(date);
    }

    function formatPhilippineNow() {
        return new Intl.DateTimeFormat('en-PH', {
            timeZone: PH_TIMEZONE,
            weekday: 'long',
            month: 'long',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        }).format(new Date());
    }

    function formatRelativeOrAbsolute(dateInput) {
        if (!dateInput) return '';

        const date = new Date(dateInput);
        if (Number.isNaN(date.getTime())) return '';

        const now = new Date();
        const diff = now.getTime() - date.getTime();

        if (diff < 60_000) return 'Just now';
        if (diff < 3_600_000) {
            const minutes = Math.floor(diff / 60_000);
            return `${minutes} minute${minutes === 1 ? '' : 's'} ago`;
        }
        if (diff < 86_400_000) {
            const hours = Math.floor(diff / 3_600_000);
            return `${hours} hour${hours === 1 ? '' : 's'} ago`;
        }
        if (diff < 604_800_000) {
            const days = Math.floor(diff / 86_400_000);
            return `${days} day${days === 1 ? '' : 's'} ago`;
        }

        return formatPhilippineDateTime(dateInput);
    }

    function renderLogTimestamps() {
        document.querySelectorAll('[data-log-timestamp]').forEach((element) => {
            const timestamp = element.getAttribute('data-log-timestamp');
            const mode = element.getAttribute('data-log-mode') || 'absolute';
            const formatted = mode === 'relative'
                ? formatRelativeOrAbsolute(timestamp)
                : formatPhilippineDateTime(timestamp);

            if (!formatted) return;

            element.textContent = formatted;
            element.setAttribute('title', `${formatPhilippineDateTime(timestamp, true)} PH`);
        });
    }

    function renderPhilippineNow() {
        document.querySelectorAll('[data-ph-now]').forEach((element) => {
            element.textContent = formatPhilippineNow();
        });
    }

    function shouldSkipAutoRefresh() {
        if (document.hidden) return true;
        if (document.querySelector('.modal.active')) return true;

        const activeElement = document.activeElement;
        if (!activeElement) return false;

        const tagName = activeElement.tagName;
        return tagName === 'INPUT' || tagName === 'TEXTAREA' || tagName === 'SELECT';
    }

    function initAutoRefresh() {
        const liveRoot = document.querySelector('[data-live-logs-root]');
        if (!liveRoot) return;

        const refreshSeconds = Number.parseInt(
            liveRoot.getAttribute('data-refresh-seconds') || `${DEFAULT_REFRESH_SECONDS}`,
            10
        );

        if (!refreshSeconds || refreshSeconds < 5) return;

        window.setInterval(() => {
            if (shouldSkipAutoRefresh()) return;
            window.location.reload();
        }, refreshSeconds * 1000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderLogTimestamps();
        renderPhilippineNow();
        initAutoRefresh();

        window.setInterval(renderPhilippineNow, 1000);
        window.setInterval(renderLogTimestamps, 30_000);
    });

    window.buildWiseLogsRealtime = {
        formatPhilippineDateTime,
        formatRelativeOrAbsolute,
        renderLogTimestamps,
        renderPhilippineNow,
        timezone: PH_TIMEZONE,
    };
})();
