<?php
/**
 * SIMPLIFIED VERSION - Works with Google Apps Script
 * Replace your existing saveToGoogleSheets() function with this
 */

/**
 * Save data to Google Sheets via Apps Script
 */
function saveToGoogleSheets($data) {
    // YOUR WEB APP URL FROM APPS SCRIPT
    $webAppUrl = 'https://script.google.com/macros/s/AKfycbzgwtMgGIceMjEvRHijpID8IbLI9KT56MNdDhqxxPOPzGCd3eMqHAGVWyviamS0pqPsZw/exec';
    
    // Prepare data as JSON
    $postData = json_encode($data);
    
    // Initialize cURL
    $ch = curl_init($webAppUrl);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development only
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        error_log("Google Sheets cURL error: " . $error);
        return false;
    }
    
    curl_close($ch);
    
    // Log response for debugging
    error_log("Google Sheets response: " . $response);
    error_log("HTTP Code: " . $httpCode);
    
    // Parse response
    $result = json_decode($response, true);
    
    if ($httpCode === 200 && isset($result['success']) && $result['success']) {
        return true;
    } else {
        error_log("Google Sheets save failed: " . ($result['message'] ?? 'Unknown error'));
        return false;
    }
}

// Example usage in your form handler:
/*
if (GOOGLE_SHEETS_ENABLED) {
    $sheetsResult = saveToGoogleSheets($formData);
    if ($sheetsResult) {
        error_log("Successfully saved to Google Sheets");
    } else {
        error_log("Failed to save to Google Sheets (form still submitted)");
    }
}
*/
?>