<?php

/**
 * Check is request comes from same canonical page
 *
 * @param string $currentUrl
 * @param string|null $prevUrl
 * @return bool
 */
function isRequestFromSameCanonical(string $currentUrl, ?string $prevUrl = null): bool
{
    if ($prevUrl === null) {
        return false;
    }

    $currParsed = parse_url($currentUrl);
    $currCanonical = $currParsed['host'] . $currParsed['path'];

    $prevParsed = parse_url($prevUrl);
    $prevCanonical = $prevParsed['host'] . $prevParsed['path'];

    return $currCanonical === $prevCanonical;
}