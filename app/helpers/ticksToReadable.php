<?php
function ticksToReadable($ticks) {
    if (!is_numeric($ticks) || $ticks <= 0) return "0s";

        $seconds = floor($ticks / 20);
        $minutes = floor($seconds / 60);
        $hours   = floor($minutes / 60);

        $seconds = $seconds % 60;
        $minutes = $minutes % 60;

        $parts = [];
        if ($hours > 0)   $parts[] = "{$hours}h";
        if ($minutes > 0) $parts[] = "{$minutes}m";
        if ($seconds > 0 && empty($parts)) $parts[] = "{$seconds}s";

    return implode(' ', $parts);
}
?>