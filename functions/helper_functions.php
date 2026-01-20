<?php

/**
 * Formata uma data no formato brasileiro.
 *
 * @param string $date
 * @return string
 */
function formatDateBr($date) {
    return date('d/m/Y H:i', strtotime($date));
}

/**
 * Escapa uma string para evitar ataques XSS.
 *
 * @param string $string
 * @return string
 */
function escapeHtml($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Exibe uma mensagem de erro em um alerta estilizado.
 *
 * @param string $message
 */
function displayError($message) {
    echo '<div class="alert alert-danger">' . escapeHtml($message) . '</div>';
}

/**
 * Exibe uma mensagem informativa em um alerta estilizado.
 *
 * @param string $message
 */
function displayInfo($message) {
    echo '<div class="alert alert-info">' . escapeHtml($message) . '</div>';
}

/**
 * Valida se um valor está presente em um array de valores permitidos.
 *
 * @param mixed $value
 * @param array $allowedValues
 * @return bool
 */
function validateAllowedValue($value, $allowedValues) {
    return in_array($value, $allowedValues);
}

?>