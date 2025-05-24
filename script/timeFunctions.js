function convertToLocalTime(utcDateStr, options = {}) {
    if (!utcDateStr) return 'Never';

    const date = new Date(utcDateStr);

    if (isNaN(date)) return 'Invalid date';

    // Use toLocaleString to include both date and time
    return date.toLocaleString(undefined, options);
}
