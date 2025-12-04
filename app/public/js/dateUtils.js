// Convert UTC timestamp to local time format using Day.js
// Requires input date to be ISO 8601
// Example usage:

// <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
// <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
// <script src="script/dateUtils.js"></script>
//    <script>
//        document.write(localTime("<?= date("c", strtotime($timestamp)) ?>", "MMMM D, YYYY hh:mm A"));
//    </script>

// YYYY → full year (2025)
// YY → two-digit year (25)

// MM → month with leading zero (01–12)
// M → month without leading zero (1–12)
// MMM → abbreviated month (Jan, Feb…)
// MMMM → full month name (January, February…)

// DD → day with leading zero (01–31)
// D → day without leading zero (1–31)

// HH → 24-hour format with leading zero (00–23)
// hh → 12-hour format with leading zero (01–12)
// mm → minutes with leading zero (00–59)
// ss → seconds with leading zero (00–59)
// A → AM/PM
// a → am/pm

dayjs.extend(dayjs_plugin_customParseFormat);

function localTime(raw, format = "MMMM D, YYYY hh:mm A") {
    return dayjs(raw).format(format); 
}