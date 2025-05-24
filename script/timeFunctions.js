function convertToLocalTime(utcDateStr, options = {}) {
    if (!utcDateStr) return 'Never';

    const utcWithTag = utcDateStr + ' UTC';
    const date = new Date(utcWithTag);

    if (isNaN(date)) {
        return 'Invalid date';
    }

    return date.toLocaleDateString(undefined, options);
}

