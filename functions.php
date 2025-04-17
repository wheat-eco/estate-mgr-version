<?php
/**
 * Common functions for Love View Estate website
 */

// Function to get active class for current page
function isActive($pageName) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $pageName) ? 'active' : '';
}

// Function to format price
function formatPrice($price) {
    return '£' . number_format($price);
}